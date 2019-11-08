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


class ReferenceClicks
{
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
    protected $clicks = 0;

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
     * @return int
     */
    public function getClicks(): int
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     */
    public function setClicks(int $clicks)
    {
        $this->clicks = $clicks;
    }

    public function addClick()
    {
        $this->clicks++;
    }
}
