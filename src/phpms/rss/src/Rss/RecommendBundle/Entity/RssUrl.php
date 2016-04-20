<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RssUrl
 *
 * @ORM\Table(name="rss_url", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\RssUrlRepository")
 */
class RssUrl
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
     * @ORM\Column(name="rss_url", type="string", length=1000, nullable=true)
     */
    private $rssUrl;

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
     * Set rssUrl
     *
     * @param string $rssUrl
     * @return RssUrl
     */
    public function setRssUrl($rssUrl)
    {
        $this->rssUrl = $rssUrl;

        return $this;
    }

    /**
     * Get rssUrl
     *
     * @return string 
     */
    public function getRssUrl()
    {
        return $this->rssUrl;
    }

    /**
     * Set user
     *
     * @param \Rss\RecommendBundle\Entity\LoginUser $user
     * @return RssUrl
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
