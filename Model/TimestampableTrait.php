<?php

namespace Staffim\TimestampableBundle\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait TimestampableTrait
{
    /**
     * @MongoDB\Date
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
