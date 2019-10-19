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


use Doctrine\ORM\Mapping as ORM;

class ReferenceVotes
{
    /**
     * @var integer
     */
    protected $id;



    /**
     * @var integer
     */
    protected $referenceArticle;

    /**
     * @var integer
     */
    protected $referenceComment;


    /**
     * @var integer
     */
    protected $positiveVotes = 0;

    /**
     * @var integer
     */
    protected $negativeVotes = 0;

    /**
     * @var integer
     */
    protected $userVotes = 0;

    /**
     * @var integer
     */
    protected $anonymousVotes = 0;

    /**
     * @var boolean
     */
    protected $published = false;

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
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getReferenceArticle(): int
    {
        return $this->referenceArticle;
    }

    /**
     * @param $referenceArticle
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
     * @param $referenceComment
     */
    public function setReferenceComment( $referenceComment)
    {
        $this->referenceComment = $referenceComment;
    }

    /**
     * @return int
     */
    public function getPositiveVotes(): int
    {
        return $this->positiveVotes;
    }

    /**
     * @param int $positiveVotes
     */
    public function setPositiveVotes(int $positiveVotes)
    {
        $this->positiveVotes = $positiveVotes;
    }

    /**
     * @return int
     */
    public function getNegativeVotes(): int
    {
        return $this->negativeVotes;
    }

    /**
     * @param int $negativeVotes
     */
    public function setNegativeVotes(int $negativeVotes)
    {
        $this->negativeVotes = $negativeVotes;
    }

    /**
     * @return int
     */
    public function getUserVotes(): int
    {
        return $this->userVotes;
    }

    /**
     * @param int $userVotes
     */
    public function setUserVotes(int $userVotes)
    {
        $this->userVotes = $userVotes;
    }

    /**
     * @return int
     */
    public function getAnonymousVotes(): int
    {
        return $this->anonymousVotes;
    }

    /**
     * @param int $anonymousVotes
     */
    public function setAnonymousVotes(int $anonymousVotes)
    {
        $this->anonymousVotes = $anonymousVotes;
    }

    /**
     * @param boolean $positive
     * @param boolean $anonymous
     */
    public function addVote(bool $positive, $anonymous = true)
    {
        if ($positive) {
            $this->positiveVotes++;
            if ($anonymous) {
                $this->anonymousVotes++;
            } else {
                $this->userVotes++;
            }
        } else {
            $this->negativeVotes++;
        }
    }

    /**
     * @return boolean
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }
}
