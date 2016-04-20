<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @ORM\Table(name="url", indexes={@ORM\Index(name="url", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\UrlRepository")
 */
class Url
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=1000, nullable=false)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updating_time", type="datetime", nullable=true)
     */
    private $lastUpdatingTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="website_bytes_number", type="smallint", nullable=true)
     */
    private $websiteBytesNumber;

    /**
     * @var \Rss\RecommendBundle\Entity\LoginUser
     *
     * @ORM\ManyToOne(targetEntity="Rss\RecommendBundle\Entity\LoginUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set lastUpdatingTime
     *
     * @param \DateTime $lastUpdatingTime
     * @return Url
     */
    public function setLastUpdatingTime($lastUpdatingTime)
    {
        $this->lastUpdatingTime = $lastUpdatingTime;

        return $this;
    }

    /**
     * Get lastUpdatingTime
     *
     * @return \DateTime 
     */
    public function getLastUpdatingTime()
    {
        return $this->lastUpdatingTime;
    }

    /**
     * Set websiteBytesNumber
     *
     * @param integer $websiteBytesNumber
     * @return Url
     */
    public function setWebsiteBytesNumber($websiteBytesNumber)
    {
        $this->websiteBytesNumber = $websiteBytesNumber;

        return $this;
    }

    /**
     * Get websiteBytesNumber
     *
     * @return integer 
     */
    public function getWebsiteBytesNumber()
    {
        return $this->websiteBytesNumber;
    }

    /**
     * Set user
     *
     * @param \Rss\RecommendBundle\Entity\LoginUser $user
     * @return Url
     */
    public function setUser(\Rss\RecommendBundle\Entity\LoginUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Rss\RecommendBundle\Entity\LoginUser 
     */
    public function getUser()
    {
        return $this->user;
    }
}
