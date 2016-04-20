<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SimilarUrl
 *
 * @ORM\Table(name="similar_url", indexes={@ORM\Index(name="similar_url", columns={"url_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\SimilarUrlRepository")
 */
class SimilarUrl
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
     * @ORM\Column(name="similar_url", type="string", length=1000, nullable=true)
     */
    private $similarUrl;

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
     * Set similarUrl
     *
     * @param string $similarUrl
     * @return SimilarUrl
     */
    public function setSimilarUrl($similarUrl)
    {
        $this->similarUrl = $similarUrl;

        return $this;
    }

    /**
     * Get similarUrl
     *
     * @return string 
     */
    public function getSimilarUrl()
    {
        return $this->similarUrl;
    }

    /**
     * Set url
     *
     * @param \Rss\RecommendBundle\Entity\Url $url
     * @return SimilarUrl
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
