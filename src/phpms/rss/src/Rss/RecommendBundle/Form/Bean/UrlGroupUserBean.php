<?php

namespace Rss\RecommendBundle\Form\Bean;

/**
 * UrlGroupUserBean
 *
 */
class UrlGroupUserBean
{

    /**
     * @var string
     *
     */
    private $urlGroup;

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
     * Set urlGroup
     *
     * @param string $urlGroup
     */
    public function setUrlGroup($urlGroup)
    {
        return $this->urlGroup = $urlGroup;
    }
}
