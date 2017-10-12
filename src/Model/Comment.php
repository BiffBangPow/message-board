<?php

namespace BiffBangPow\MessageBoard\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="comments")
 */
class Comment
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @Column(type="text")
     * @var string
     */
    private $content;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    private $postedAt;

    /**
     * @ManyToOne(targetEntity="Thread", inversedBy="comments")
     * @JoinColumn(name="thread_id", referencedColumnName="id")
     */
    private $thread;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="comments")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @OneToMany(targetEntity="Report", mappedBy="comment", fetch="EXTRA_LAZY")
     * var ArrayCollection
     */
    private $reports;

    public function __construct()
    {
        $this->postedAt = new \DateTime();
        $this->reports = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param int $length
     * @return string
     */
    public function getExcerpt($length = 140)
    {
        return substr($this->content, 0, $length);
    }

    /**
     * @return mixed
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @param mixed $postedAt
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    /**
     * @return mixed
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param mixed $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * @param mixed $reports
     */
    public function setReports($reports)
    {
        $this->reports = $reports;
    }

    /**
     * @return bool
     */
    public function hasReports()
    {
        if (($this->reports->isEmpty())) {
            return true;
        } else {
            return false;
        }

        var_dump($this->reports);
        die();
    }
}
