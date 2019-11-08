<?php

/*
 * This file is part of the MsalsasVotingBundle package.
 *
 * (c) Manolo Salsas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Msalsas\VotingBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

class Click
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * @var integer
     */
    protected $referenceArticle;

    /**
     * @var integer
     */
    protected $referenceComment;

    /**
     * @var string
     */
    protected $userIP;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return null|UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null|UserInterface $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getReferenceArticle(): int
    {
        return $this->referenceArticle;
    }

    /**
     * @param int $referenceArticle
     */
    public function setReferenceArticle(int $referenceArticle)
    {
        $this->referenceArticle = $referenceArticle;
    }

    /**
     * @return int
     */
    public function getReferenceComment(): int
    {
        return $this->referenceComment;
    }

    /**
     * @param int $referenceComment
     */
    public function setReferenceComment(int $referenceComment)
    {
        $this->referenceComment = $referenceComment;
    }

    /**
     * @return string
     */
    public function getUserIP(): string
    {
        return $this->userIP;
    }

    /**
     * @param string $userIP
     */
    public function setUserIP(string $userIP)
    {
        $this->userIP = $userIP;
    }

}
