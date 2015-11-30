<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RssData
 */
class RssData
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $uRL;

    /**
     * @var string
     */
    private $synonym;

    /**
     * @var string
     */
    private $similarURL;


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
     * Set uRL
     *
     * @param string $uRL
     * @return RssData
     */
    public function setURL($uRL)
    {
        $this->uRL = $uRL;

        return $this;
    }

    /**
     * Get uRL
     *
     * @return string 
     */
    public function getURL()
    {
        return $this->uRL;
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
     * Set similarURL
     *
     * @param string $similarURL
     * @return RssData
     */
    public function setSimilarURL($similarURL)
    {
        $this->similarURL = $similarURL;

        return $this;
    }

    /**
     * Get similarURL
     *
     * @return string 
     */
    public function getSimilarURL()
    {
        return $this->similarURL;
    }
}
