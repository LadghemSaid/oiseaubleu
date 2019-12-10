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

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

use Doctrine\ORM\EntityManagerInterface;
use Msalsas\VotingBundle\Entity\ReferenceVotes;
use Msalsas\VotingBundle\Entity\VoteNegative;
use Msalsas\VotingBundle\Entity\VotePositive;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;


class Voter
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

    /**
     * @var integer
     */
    protected $anonPercentAllowed;

    /**
     * @var integer
     */
    protected $anonMinAllowed;

    /**
     * @var array
     */
    protected $negativeReasons;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $token, RequestStack $requestStack, TranslatorInterface $translator, $anonPercentAllowed, $anonMinAllowed, $negativeReasons = array())
    {
        $this->em = $em;
        $this->token = $token;
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->anonPercentAllowed = $anonPercentAllowed;
        $this->anonMinAllowed = $anonMinAllowed;
        $this->negativeReasons = $negativeReasons;
    }



    public function getPositiveVotes($referenceArticleId)
    {
        $cache = new FilesystemAdapter();
        $referenceVotes = $cache->get('likes', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findAll();
            return $referenceVotes;
        });
        $voteActive = false;
        foreach ($referenceVotes as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive = $vote;

            }
        }



        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        if (!$voteActive) {
            $voteActive = new ReferenceVotes();
            $voteActive->setReferenceArticle($referenceArticleId);
        }

        return $voteActive->getPositiveVotes();
    }

    public function getNegativeVotes($referenceArticleId)
    {
        $cache = new FilesystemAdapter();
        $referenceVotes = $cache->get('likes', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findAll();
            return $referenceVotes;
        });
        $voteActive = false;
        foreach ($referenceVotes as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive = $vote;

            }
        }

        if (!$voteActive) {
            $voteActive = new ReferenceVotes();
            $voteActive->setReferenceArticle($referenceArticleId);
        }

        return $voteActive->getNegativeVotes();
    }

    public function getUserPositiveVotes($referenceArticleId)
    {
        $cache = new FilesystemAdapter();
        $referenceVotes = $cache->get('likes', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findAll();
            return $referenceVotes;
        });
        $voteActive = false;
        foreach ($referenceVotes as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive = $vote;

            }
        }
        if (!$voteActive) {
            $voteActive = new ReferenceVotes();
            $voteActive->setReferenceArticle($referenceArticleId);
        }

        return $voteActive->getUserVotes();
    }

    public function getAnonymousVotes($referenceArticleId)
    {
        $cache = new FilesystemAdapter();
        $referenceVotes = $cache->get('likes', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findAll();
            return $referenceVotes;
        });
        $voteActive = false;
        foreach ($referenceVotes as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive = $vote;
            }
        }
        dd($referenceVotes);
        if (!$voteActive) {
            $voteActive = new ReferenceVotes();
            $voteActive->setReferenceArticle($referenceArticleId);
        }
        return $voteActive->getAnonymousVotes();
    }

    public function votePositive($referenceArticleId)
    {
        //var_dump($referenceArticleId);
        //die();
        $user = $this->token->getToken() ? $this->token->getToken()->getUser() : null;

        $this->validateVote($user, $referenceArticleId);

        $user = $user instanceof UserInterface ? $user : null;
        //var_dump($user);
        //die();
        $vote = new VotePositive();
        //var_dump($vote);
        $vote->setReferenceArticle($referenceArticleId);
        $vote->setReferenceComment(null);
        $vote->setUser($user);

        $vote->setUserIP($this->request ? $this->request->getClientIp() : null);

        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes $referenceVotes */
        $referenceVotes = $this->addReferenceVote($referenceArticleId, true, !$user);

        $this->em->persist($vote);
        $this->em->persist($referenceVotes);
        $this->em->flush();

        //Mise a jour du cache apres un like
        $cache = new FilesystemAdapter();
        $cache->delete('likes');
        $cache->delete('votesUserPositiveAnonyme');
        $cache->delete('votesUserPositive');
        // $cache->set('likes', function() use($vote){
        //    array_push($referenceVotes,$vote);
        //});
        return $referenceVotes->getPositiveVotes();
    }

    public function voteNegative($referenceArticleId, $reason)
    {
        $user = $this->token->getToken() ? $this->token->getToken()->getUser() : null;

        if (!$reason || !is_string($reason) || $reason === '0') {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.invalid_reason'));
        }

        $this->validateVote($user, $referenceArticleId);

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.anon_cannot_vote_negative'));
        }

        $vote = new VoteNegative();
        $vote->setReferenceArticle($referenceArticleId);
        $vote->setReason($reason);
        $vote->setUser($user);

        $vote->setUserIP($this->request->getClientIp());

        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes $referenceVotes */
        $referenceVotes = $this->addReferenceVote($referenceArticleId, false, !$user);

        $this->em->persist($referenceVotes);
        $this->em->persist($vote);
        $this->em->flush();

        $cache = new FilesystemAdapter();
        $cache->delete('likes');
        $cache->delete('voteUserNegative');

        return $referenceVotes->getNegativeVotes();
    }

    public function getUserVote($referenceArticleId)
    {

        $user = $this->token->getToken()->getUser();
        $user = $user instanceof UserInterface ? $user : null;

        $votePositiveRepository = $this->em->getRepository(VotePositive::class);
        $voteNegativeRepository = $this->em->getRepository(VoteNegative::class);

        $userIp = $this->request->getClientIp();
        $cache = new FilesystemAdapter();

        $votesUserPositiveAnonyme = $cache->get('votesUserPositiveAnonyme', function (ItemInterface $item) use ($referenceArticleId,$user,$votePositiveRepository,$userIp) {
            $item->expiresAfter(3600);
            $votesUserPositiveAnonyme = $votePositiveRepository->findBy(array( 'userIP' => $userIp, 'referenceComment' => null));
            return $votesUserPositiveAnonyme;
        });

        $votesUserPositive = $cache->get('votesUserPositive', function (ItemInterface $item) use ($referenceArticleId,$user,$votePositiveRepository) {
            $item->expiresAfter(3600);
            $votesUserPositive = $votePositiveRepository->findBy(array('user' => $user));
            return $votesUserPositive;
        });

        $voteUserNegative = $cache->get('voteUserNegative', function (ItemInterface $item) use ($referenceArticleId,$user,$voteNegativeRepository) {
            $item->expiresAfter(3600);
            $voteUserNegative = $voteNegativeRepository->findBy(array('user' => $user));
            return $voteUserNegative;
        });

        $voteActive_votesUserPositiveAnonyme = false;
        foreach ($votesUserPositiveAnonyme as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_votesUserPositiveAnonyme = $vote;

            }
        }

        $voteActive_votesUserPositive = false;
        foreach ($votesUserPositive as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_votesUserPositive = $vote;

            }
        }

        $voteActive_voteUserNegative = false;
        foreach ($voteUserNegative as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_voteUserNegative = $vote;

            }
        }

        if ($user && $voteActive_votesUserPositive)
        {
            return $voteActive_votesUserPositive;

        }
        else if (!$user && $voteActive_votesUserPositiveAnonyme)
        {
            return $voteActive_votesUserPositiveAnonyme;

        }
        else if ($voteActive_voteUserNegative)
        {
            return $voteActive_voteUserNegative;
        }

        return false;

    }

    public function getNegativeReasons()
    {
        return $this->negativeReasons;
    }

    public function userCanVoteNegative($referenceArticleId)
    {
        $user = $this->token->getToken()->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return !$this->userHasVoted($user, $referenceArticleId);
    }

    public function isPublished($referenceArticleId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceArticle' => $referenceArticleId));
        if (!$referenceVotes) {
            return false;
        }

        return $referenceVotes->isPublished();
    }

    public function setPublished($referenceArticleId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceArticle' => $referenceArticleId));
        if (!$referenceVotes) {
            throw new \Exception($this->translator->trans('msalsas_voting.errors.ref_does_not_exist', array('%reference%' => $referenceArticleId)));
        }

        $referenceVotes->setPublished(true);
    }

    protected function validateVote($user, $referenceArticleId)
    {

        if (!$user instanceof UserInterface && (!$this->request || !$this->request->getClientIp())) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.no_ip_defined_for_anon'));
        }

        if ($this->userHasVoted($user, $referenceArticleId)) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.already_voted'));
        }

 /*       if (!$user instanceof UserInterface) {
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceArticle' => $referenceArticleId));
            if ($referenceVotes instanceof ReferenceVotes && !$this->anonymousIsAllowed($referenceVotes)) {
                throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.too_much_anon_votes'));
            }
        }*/
    }

    protected function userHasVoted($user, $referenceArticleId)
    {
        $votePositiveRepository = $this->em->getRepository(VotePositive::class);
        $voteNegativeRepository = $this->em->getRepository(VoteNegative::class);

      /*  $cache = new FilesystemAdapter();

        $userIp = $this->request->getClientIp();

        $votesUserPositiveAnonyme = $cache->get('votesUserPositiveAnonyme', function (ItemInterface $item) use ($referenceArticleId,$user,$votePositiveRepository,$userIp) {
            $item->expiresAfter(3600);
            $votesUserPositiveAnonyme = $votePositiveRepository->findBy(array('user' => $user, 'userIP' => $userIp, 'referenceComment' => null));
            return $votesUserPositiveAnonyme;
        });

        $votesUserPositive = $cache->get('votesUserPositive', function (ItemInterface $item) use ($referenceArticleId,$user,$votePositiveRepository) {
            $item->expiresAfter(3600);
            $votesUserPositive = $votePositiveRepository->findBy(array('user' => $user));
            return $votesUserPositive;
        });

        $voteUserNegative = $cache->get('voteUserNegative', function (ItemInterface $item) use ($referenceArticleId,$user,$voteNegativeRepository) {
            $item->expiresAfter(3600);
            $voteUserNegative = $voteNegativeRepository->findBy(array('user' => $user));
            return $voteUserNegative;
        });


        $voteActive_votesUserPositiveAnonyme = false;
        foreach ($votesUserPositiveAnonyme as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_votesUserPositiveAnonyme = $vote;

            }
        }

        $voteActive_votesUserPositive = false;
        foreach ($votesUserPositive as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_votesUserPositive = $vote;

            }
        }

        $voteActive_voteUserNegative = false;
        foreach ($voteUserNegative as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive_voteUserNegative = $vote;

            }
        }

        if ($user instanceof UserInterface) {
            if ($voteActive_votesUserPositive) {

                return true;
            } else if ($voteActive_voteUserNegative) {
                return true;
            }
        } else {
            if ($voteActive_votesUserPositiveAnonyme) {
                return true;
            }
        }

        return false;*/
        if ($user instanceof UserInterface) {
            if ($votePositiveRepository->findOneBy(
                array(
                    'user' => $user,
                    'referenceArticle' => $referenceArticleId
                )
            )) {

                return true;
            } else if ($voteNegativeRepository->findOneBy(
                array(
                    'user' => $user,
                    'referenceArticle' => $referenceArticleId
                )
            )) {
                return true;
            }
        } else {
            if ($votePositiveRepository->findOneBy(
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

    protected function addReferenceVote($referenceArticleId, $positive, $anonymous = true)
    {
        $cache = new FilesystemAdapter();
        $referenceVotes = $cache->get('likes', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findAll();
            return $referenceVotes;
        });
        $voteActive = false;
        foreach ($referenceVotes as $key => $vote){
            if($referenceArticleId !== null && $referenceArticleId == $vote->getReferenceArticle()){
                $voteActive = $vote;

            }
        }


        if ($voteActive) {
            $voteActive->addVote($positive, $anonymous);
        } else {
            $voteActive = new ReferenceVotes();
            $voteActive->setReferenceArticle($referenceArticleId);
            $voteActive->setReferenceComment(null);
            $voteActive->addVote($positive, $anonymous);
            $cache = new FilesystemAdapter();
            $cache->delete('likes');
        }

        return $voteActive;
    }

    protected function anonymousIsAllowed(ReferenceVotes $referenceVotes)
    {
        $anonVotes = $referenceVotes->getAnonymousVotes();
        $userVotes = $referenceVotes->getUserVotes();

        if ($anonVotes < $this->anonMinAllowed) {
            return true;
        }

        $anonPercent = $anonVotes ? ($anonVotes / ($userVotes + $anonVotes)) * 100 : 0;
        if ($anonPercent < $this->anonPercentAllowed) {
            return true;
        }

        return false;
    }












    public function getPositiveVotesComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            $referenceVotes = new ReferenceVotes();
            $referenceVotes->setReferenceComment($referenceCommentId);
        }

        return $referenceVotes->getPositiveVotes();
    }

    public function getNegativeVotesComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            $referenceVotes = new ReferenceVotes();
            $referenceVotes->setReferenceComment($referenceCommentId);
        }

        return $referenceVotes->getNegativeVotesComment();
    }

    public function getUserPositiveVotesComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            $referenceVotes = new ReferenceVotes();
            $referenceVotes->setReferenceComment($referenceCommentId);
        }

        return $referenceVotes->getUserVotesComment();
    }

    public function getAnonymousVotesComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            $referenceVotes = new ReferenceVotes();
            $referenceVotes->setReferenceComment($referenceCommentId);
        }

        return $referenceVotes->getAnonymousVotes();
    }

    public function votePositiveComment($referenceCommentId)
    {
        $user = $this->token->getToken() ? $this->token->getToken()->getUser() : null;

        $this->validateVoteComment($user, $referenceCommentId);

        $user = $user instanceof UserInterface ? $user : null;

        $vote = new VotePositive();
        $vote->setReferenceComment($referenceCommentId);
        $vote->setReferenceArticle(null);
        $vote->setUser($user);
        $vote->setUserIP($this->request ? $this->request->getClientIp() : null);

        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes $referenceVotes */
        $referenceVotes = $this->addReferenceVoteComment($referenceCommentId, true, !$user);

        $this->em->persist($vote);
        $this->em->persist($referenceVotes);
        $this->em->flush();

        return $referenceVotes->getPositiveVotes();
    }

    public function voteNegativeComment($referenceCommentId, $reason)
    {
        $user = $this->token->getToken() ? $this->token->getToken()->getUser() : null;

        if (!$reason || !is_string($reason) || $reason === '0') {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.invalid_reason'));
        }

        $this->validateVoteComment($user, $referenceCommentId);

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.anon_cannot_vote_negative'));
        }

        $vote = new VoteNegative();
        $vote->setReferenceComment($referenceCommentId);
        $vote->setReason($reason);
        $vote->setUser($user);
        $vote->setUserIP($this->request->getClientIp());

        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes $referenceVotes */
        $referenceVotes = $this->addReferenceVoteComment($referenceCommentId, false, !$user);

        $this->em->persist($referenceVotes);
        $this->em->persist($vote);
        $this->em->flush();

        return $referenceVotes->getNegativeVotes();
    }

    public function getUserVoteComment($referenceCommentId)
    {
        $user = $this->token->getToken()->getUser();
        $user = $user instanceof UserInterface ? $user : null;
        $votePositiveRepository = $this->em->getRepository(VotePositive::class);
        $voteNegativeRepository = $this->em->getRepository(VoteNegative::class);

        if ($user && $vote = $votePositiveRepository->findOneBy(array('user' => $user, 'referenceComment' => $referenceCommentId))) {
            return $vote;
        } else if (!$user && $vote = $votePositiveRepository->findOneBy(array('user' => $user, 'referenceComment' => $referenceCommentId, 'userIP' => $this->request->getClientIp()))) {
            return $vote;
        } else if ($vote = $voteNegativeRepository->findOneBy(array('user' => $user, 'referenceComment' => $referenceCommentId))) {
            return $vote;
        }

        return false;
    }


    public function userCanVoteNegativeComment($referenceCommentId)
    {
        $user = $this->token->getToken()->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return !$this->userHasVotedComment($user, $referenceCommentId);
    }

    public function isPublishedComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            return false;
        }

        return $referenceVotes->isPublished();
    }

    public function setPublishedComment($referenceCommentId)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if (!$referenceVotes) {
            throw new \Exception($this->translator->trans('msalsas_voting.errors.ref_does_not_exist', array('%reference%' => $referenceCommentId)));
        }

        $referenceVotes->setPublished(true);
    }

    protected function validateVoteComment($user, $referenceCommentId)
    {
        if (!$user instanceof UserInterface && (!$this->request || !$this->request->getClientIp())) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.no_ip_defined_for_anon'));
        }

        if ($this->userHasVotedComment($user, $referenceCommentId)) {
            throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.already_voted'));
        }

        if (!$user instanceof UserInterface) {
            $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
            if ($referenceVotes instanceof ReferenceVotes && !$this->anonymousIsAllowed($referenceVotes)) {
                throw new AccessDeniedException($this->translator->trans('msalsas_voting.errors.too_much_anon_votes'));
            }
        }
    }

    protected function userHasVotedComment($user, $referenceCommentId)
    {
        $votePositiveRepository = $this->em->getRepository(VotePositive::class);
        $voteNegativeRepository = $this->em->getRepository(VoteNegative::class);

        if ($user instanceof UserInterface) {
            if ($votePositiveRepository->findOneBy(
                array(
                    'user' => $user,
                    'referenceComment' => $referenceCommentId
                )
            )) {
                return true;
            } else if ($voteNegativeRepository->findOneBy(
                array(
                    'user' => $user,
                    'referenceComment' => $referenceCommentId
                )
            )) {
                return true;
            }
        } else {
            if ($votePositiveRepository->findOneBy(
                array(
                    'user' => null,
                    'userIP' => $this->request->getClientIp(),
                    'referenceComment' => $referenceCommentId
                )
            )) {
                return true;
            }
        }

        return false;
    }

    protected function addReferenceVoteComment($referenceCommentId, $positive, $anonymous = true)
    {
        /** @var \Msalsas\VotingBundle\Entity\ReferenceVotes|null $referenceVotes */
        $referenceVotes = $this->em->getRepository(ReferenceVotes::class)->findOneBy(array('referenceComment' => $referenceCommentId));
        if ($referenceVotes) {
            $referenceVotes->addVote($positive, $anonymous);
        } else {
            $referenceVotes = new ReferenceVotes();
            $referenceVotes->setReferenceComment($referenceCommentId);
            $referenceVotes->setReferenceArticle(null);
            $referenceVotes->addVote($positive, $anonymous);
        }

        return $referenceVotes;
    }

    protected function anonymousIsAllowedComment(ReferenceVotes $referenceVotes)
    {
        $anonVotes = $referenceVotes->getAnonymousVotes();
        $userVotes = $referenceVotes->getUserVotes();

        if ($anonVotes < $this->anonMinAllowed) {
            return true;
        }

        $anonPercent = $anonVotes ? ($anonVotes / ($userVotes + $anonVotes)) * 100 : 0;
        if ($anonPercent < $this->anonPercentAllowed) {
            return true;
        }

        return false;
    }


}
