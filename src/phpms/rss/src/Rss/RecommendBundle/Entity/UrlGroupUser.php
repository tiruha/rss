<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UrlGroupUser
 *
 * @ORM\Table(name="url_group_user", indexes={@ORM\Index(name="url_group_user", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\UrlGroupUserRepository")
 */
class UrlGroupUser
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
     * @ORM\Column(name="url_group_name", type="string", length=30, nullable=true)
     */
    private $urlGroupName;

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
     * Set urlGroupName
     *
     * @param string $urlGroupName
     * @return UrlGroupUser
     */
    public function setUrlGroupName($urlGroupName)
    {
        $this->urlGroupName = $urlGroupName;

        return $this;
    }

    /**
     * Get urlGroupName
     *
     * @return string 
     */
    public function getUrlGroupName()
    {
        return $this->urlGroupName;
    }

    /**
     * Set user
     *
     * @param \Rss\RecommendBundle\Entity\LoginUser $user
     * @return UrlGroupUser
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
