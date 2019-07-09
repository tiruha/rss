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
     * Get string
     *
     * @return string ["userid" . $this->user->getId() . "_group" . $group_counter . "_" . $group->getUrlGroupName()]
     */
    public function getUrlGroup()
    {
        return $this->urlGroup;
    }

    /**
     * Set string
     *
     * @param string $urlGroup
     */
    public function setUrlGroup($urlGroup)
    {
        return $this->urlGroup = $urlGroup;
    }
}
