<?php

/*
 * This file is part of the MsalsasVotingBundle package.
 *
 * (c) Manolo Salsas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Msalsas\VotingBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Msalsas\VotingBundle\Entity\Click;
use Msalsas\VotingBundle\Entity\ReferenceClicks;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Clicker
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TokenStorageInterface
     */
    protected $token;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $token, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->token = $token;
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
    }

    public function getClicks($referenceArticleId)
    {
        /** @var ReferenceClicks|null $referenceClicks */
        $referenceClicks = $this->em->getRepository(ReferenceClicks::class)->findOneBy(array('referenceArticle' => $referenceArticleId));
        if (!$referenceClicks) {
            return 0;
        }

        return $referenceClicks->getClicks();
    }

    public function addClick($referenceArticleId)
    {
        $user = $this->token->getToken()->getUser();
        $user = $user instanceof UserInterface ? $user : null;

        try {
            $this->validateClick($user, $referenceArticleId);
        } catch (\Exception $e) {
            return $this->getClicks($referenceArticleId);
        }

        $click = new Click();
        $click->setReferenceArticle($referenceArticleId);
        $click->setUser($user);
        $click->setUserIP($this->request->getClientIp());

        /** @var \Msalsas\VotingBundle\Entity\ReferenceClicks $referenceClicks */
        $referenceClicks = $this->addReferenceClick($referenceArticleId);

        $this->em->persist($click);
        $this->em->persist($referenceClicks);
        $this->em->flush();

        return $referenceClicks->getClicks();
    }

    private function validateClick($user, $referenceArticleId)
    {
        if (!$user instanceof UserInterface && !$this->request->getClientIp()) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.no_ip_defined_for_anon'));
        }

        if ($this->userHasClicked($user, $referenceArticleId)) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.already_clicked'));
        }
    }

    private function userHasClicked($user, $referenceArticleId)
    {
        $clickRepository = $this->em->getRepository(Click::class);

        if ($user instanceof UserInterface) {
            if ($clickRepository->findOneBy(
                array(
                    'user' => $user,
                    'referenceArticle' => $referenceArticleId
                )
            )) {
                return true;
            }
        } else {
            if ($clickRepository->findOneBy(
                array(
                    'user' => null,
                    'userIP' => $this->request->getClientIp(),
                    'referenceArticle' => $referenceArticleId
                )
            )) {
                return true;
            }
        }

        return false;
    }

    private function addReferenceClick($referenceArticleId)
    {
        /** @var ReferenceClicks|null $referenceClicks */
        $referenceClicks = $this->em->getRepository(ReferenceClicks::class)->findOneBy(array('referenceArticle' => $referenceArticleId));
        if ($referenceClicks) {
            $referenceClicks->addClick();
        } else {
            $referenceClicks = new ReferenceClicks();
            $referenceClicks->setReferenceArticle($referenceArticleId);
            $referenceClicks->addClick();
        }

        return $referenceClicks;
    }
}
