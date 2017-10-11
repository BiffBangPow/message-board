<?php
/**
 * Created by PhpStorm.
 * User: placement
 * Date: 10/10/2017
 * Time: 16:02
 */

namespace BiffBangPow\MessageBoard\Model;

/**
* @Entity
* @Table(name="reports")
*/
class Report
{
    /**
     * @Id
     * @ManyToOne (targetEntity="User", inversedBy="reports")
     */
    private $user;

    /**
     * @Id
     * @ManyToOne (targetEntity="Comment", inversedBy="reports")
     */
    private $comment;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    private $reportedAt;

    /**
     * @Column(type="text")
     * @var string
     */
    private $complaint;

    public function __construct()
    {
        $this->reportedAt = new \DateTime();
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
     * @return \DateTime
     */
    public function getReportedAt(): \DateTime
    {
        return $this->reportedAt;
    }

    /**
     * @param \DateTime $reportedAt
     */
    public function setReportedAt(\DateTime $reportedAt)
    {
        $this->reportedAt = $reportedAt;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComplaint(): string
    {
        return $this->complaint;
    }

    /**
     * @param string $complaint
     */
    public function setComplaint(string $complaint)
    {
        $this->complaint = $complaint;
    }
}
