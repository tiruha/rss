<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RssData
 *
 * @ORM\Table(name="rss_data")
 * @ORM\Entity
 */
class RssData
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
     * @ORM\Column(name="URL", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="Synonym", type="string", length=255, nullable=false)
     */
    private $synonym;

    /**
     * @var string
     *
     * @ORM\Column(name="SimilarURL", type="string", length=255, nullable=false)
     */
    private $similarurl;



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
     * @return RssData
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
     * Set synonym
     *
     * @param string $synonym
     * @return RssData
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;

        return $this;
    }

    /**
     * Get synonym
     *
     * @return string 
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Set similarurl
     *
     * @param string $similarurl
     * @return RssData
     */
    public function setSimilarurl($similarurl)
    {
        $this->similarurl = $similarurl;

        return $this;
    }

    /**
     * Get similarurl
     *
     * @return string 
     */
    public function getSimilarurl()
    {
        return $this->similarurl;
    }
}
