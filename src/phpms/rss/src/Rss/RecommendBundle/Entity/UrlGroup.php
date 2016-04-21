<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UrlGroup
 *
 * @ORM\Table(name="url_group", indexes={@ORM\Index(name="url_group_group_id", columns={"group_id"}), @ORM\Index(name="url_group_url_id", columns={"url_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\UrlGroupRepository")
 */
class UrlGroup
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
     * @var \Rss\RecommendBundle\Entity\UrlGroupUser
     *
     * @ORM\ManyToOne(targetEntity="Rss\RecommendBundle\Entity\UrlGroupUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $group;

    /**
     * @var \Rss\RecommendBundle\Entity\Url
     *
     * @ORM\ManyToOne(targetEntity="Rss\RecommendBundle\Entity\Url")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="url_id", referencedColumnName="id")
     * })
     */
    private $url;



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
     * Set group
     *
     * @param \Rss\RecommendBundle\Entity\UrlGroupUser $group
     * @return UrlGroup
     */
    public function setGroup(\Rss\RecommendBundle\Entity\UrlGroupUser $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Rss\RecommendBundle\Entity\UrlGroupUser 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set url
     *
     * @param \Rss\RecommendBundle\Entity\Url $url
     * @return UrlGroup
     */
    public function setUrl(\Rss\RecommendBundle\Entity\Url $url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return \Rss\RecommendBundle\Entity\Url 
     */
    public function getUrl()
    {
        return $this->url;
    }
}
