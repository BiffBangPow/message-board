<?php

namespace BiffBangPow\MessageBoard\Model;

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

    public function __construct()
    {
        $this->postedAt = new \DateTime();
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
}