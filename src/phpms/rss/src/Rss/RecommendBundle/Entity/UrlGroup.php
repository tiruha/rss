<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UrlGroup
 *
 * @ORM\Table(name="url_group", indexes={@ORM\Index(name="url_group", columns={"url_id"})})
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
     * @var string
     *
     * @ORM\Column(name="url_group", type="string", length=30, nullable=true)
     */
    private $urlGroup;

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
     * Set urlGroup
     *
     * @param string $urlGroup
     * @return UrlGroup
     */
    public function setUrlGroup($urlGroup)
    {
        $this->urlGroup = $urlGroup;

        return $this;
    }

    /**
     * Get urlGroup
     *
     * @return string 
     */
    public function getUrlGroup()
    {
        return $this->urlGroup;
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
