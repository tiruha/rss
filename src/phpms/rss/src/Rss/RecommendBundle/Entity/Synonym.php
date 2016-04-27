<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Synonym
 *
 * @ORM\Table(name="synonym", indexes={@ORM\Index(name="synonym", columns={"url_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\SynonymRepository")
 */
class Synonym
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Rss\RecommendBundle\Repository\Id\IdCustomGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="synonym", type="string", length=5000, nullable=true)
     */
    private $synonym;

    /**
     * @var string
     *
     * @ORM\Column(name="synonym_severity", type="string", length=30, nullable=true)
     */
    private $synonymSeverity;

    /**
     * @var \Rss\RecommendBundle\Entity\Url
     *
     * @ORM\ManyToOne(targetEntity="Rss\RecommendBundle\Entity\Url", inversedBy="synonym")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="url_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
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
     * Set synonym
     *
     * @param string $synonym
     * @return Synonym
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
     * Set synonymSeverity
     *
     * @param string $synonymSeverity
     * @return Synonym
     */
    public function setSynonymSeverity($synonymSeverity)
    {
        $this->synonymSeverity = $synonymSeverity;

        return $this;
    }

    /**
     * Get synonymSeverity
     *
     * @return string 
     */
    public function getSynonymSeverity()
    {
        return $this->synonymSeverity;
    }

    /**
     * Set url
     *
     * @param \Rss\RecommendBundle\Entity\Url $url
     * @return Synonym
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
