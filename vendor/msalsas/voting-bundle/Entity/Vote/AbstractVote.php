<?php

/*
 * This file is part of the MsalsasVotingBundle package.
 *
 * (c) Manolo Salsas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Msalsas\VotingBundle\Entity\Vote;


use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractVote
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var UserInterface|null|string
     */
    protected $user = null;

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
     * @return string|UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string|UserInterface $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     *
     */
    public function getReferenceArticle()
    {
        return $this->referenceArticle;
    }

    /**
     * @param  $referenceArticle
     */
    public function setReferenceArticle( $referenceArticle)
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
     * @param  $referenceComment
     */
    public function setReferenceComment($referenceComment)
    {
        $this->referenceComment = $referenceComment;
    }

    /**
     * @return string|null
     */
    public function getUserIP(): string
    {
        return $this->userIP;
    }

    /**
     * @param string|null $userIP
     */
    public function setUserIP(string $userIP = null)
    {
        $this->userIP = $userIP;
    }

    public function isPositive()
    {
        if (get_class($this) === 'Msalsas\VotingBundle\Entity\VotePositive') {
            return true;
        }

        return false;
    }
}
