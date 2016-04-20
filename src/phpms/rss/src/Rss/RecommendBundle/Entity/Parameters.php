<?php

namespace Rss\RecommendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameters
 *
 * @ORM\Table(name="parameters", uniqueConstraints={@ORM\UniqueConstraint(name="url_id", columns={"url_id"})})
 * @ORM\Entity(repositoryClass="Rss\RecommendBundle\Repository\ParametersRepository")
 */
class Parameters
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
     * @var float
     *
     * @ORM\Column(name="matching_range_synonym_document", type="float", precision=10, scale=0, nullable=true)
     */
    private $matchingRangeSynonymDocument;

    /**
     * @var float
     *
     * @ORM\Column(name="range_synonym__sub_synonym_document_matched_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $rangeSynonymSubSynonymDocumentMatchedWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="range_synonym__main_synonym_document_matched_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $rangeSynonymMainSynonymDocumentMatchedWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="matching_range_keyword_document", type="float", precision=10, scale=0, nullable=true)
     */
    private $matchingRangeKeywordDocument;

    /**
     * @var float
     *
     * @ORM\Column(name="range_keyword__sub_synonym_document_matched_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $rangeKeywordSubSynonymDocumentMatchedWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="range_keyword__main_synonym_document_matched_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $rangeKeywordMainSynonymDocumentMatchedWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="synonym_keyword_matched_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $synonymKeywordMatchedWeight;

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
     * Set matchingRangeSynonymDocument
     *
     * @param float $matchingRangeSynonymDocument
     * @return Parameters
     */
    public function setMatchingRangeSynonymDocument($matchingRangeSynonymDocument)
    {
        $this->matchingRangeSynonymDocument = $matchingRangeSynonymDocument;

        return $this;
    }

    /**
     * Get matchingRangeSynonymDocument
     *
     * @return float 
     */
    public function getMatchingRangeSynonymDocument()
    {
        return $this->matchingRangeSynonymDocument;
    }

    /**
     * Set rangeSynonymSubSynonymDocumentMatchedWeight
     *
     * @param float $rangeSynonymSubSynonymDocumentMatchedWeight
     * @return Parameters
     */
    public function setRangeSynonymSubSynonymDocumentMatchedWeight($rangeSynonymSubSynonymDocumentMatchedWeight)
    {
        $this->rangeSynonymSubSynonymDocumentMatchedWeight = $rangeSynonymSubSynonymDocumentMatchedWeight;

        return $this;
    }

    /**
     * Get rangeSynonymSubSynonymDocumentMatchedWeight
     *
     * @return float 
     */
    public function getRangeSynonymSubSynonymDocumentMatchedWeight()
    {
        return $this->rangeSynonymSubSynonymDocumentMatchedWeight;
    }

    /**
     * Set rangeSynonymMainSynonymDocumentMatchedWeight
     *
     * @param float $rangeSynonymMainSynonymDocumentMatchedWeight
     * @return Parameters
     */
    public function setRangeSynonymMainSynonymDocumentMatchedWeight($rangeSynonymMainSynonymDocumentMatchedWeight)
    {
        $this->rangeSynonymMainSynonymDocumentMatchedWeight = $rangeSynonymMainSynonymDocumentMatchedWeight;

        return $this;
    }

    /**
     * Get rangeSynonymMainSynonymDocumentMatchedWeight
     *
     * @return float 
     */
    public function getRangeSynonymMainSynonymDocumentMatchedWeight()
    {
        return $this->rangeSynonymMainSynonymDocumentMatchedWeight;
    }

    /**
     * Set matchingRangeKeywordDocument
     *
     * @param float $matchingRangeKeywordDocument
     * @return Parameters
     */
    public function setMatchingRangeKeywordDocument($matchingRangeKeywordDocument)
    {
        $this->matchingRangeKeywordDocument = $matchingRangeKeywordDocument;

        return $this;
    }

    /**
     * Get matchingRangeKeywordDocument
     *
     * @return float 
     */
    public function getMatchingRangeKeywordDocument()
    {
        return $this->matchingRangeKeywordDocument;
    }

    /**
     * Set rangeKeywordSubSynonymDocumentMatchedWeight
     *
     * @param float $rangeKeywordSubSynonymDocumentMatchedWeight
     * @return Parameters
     */
    public function setRangeKeywordSubSynonymDocumentMatchedWeight($rangeKeywordSubSynonymDocumentMatchedWeight)
    {
        $this->rangeKeywordSubSynonymDocumentMatchedWeight = $rangeKeywordSubSynonymDocumentMatchedWeight;

        return $this;
    }

    /**
     * Get rangeKeywordSubSynonymDocumentMatchedWeight
     *
     * @return float 
     */
    public function getRangeKeywordSubSynonymDocumentMatchedWeight()
    {
        return $this->rangeKeywordSubSynonymDocumentMatchedWeight;
    }

    /**
     * Set rangeKeywordMainSynonymDocumentMatchedWeight
     *
     * @param float $rangeKeywordMainSynonymDocumentMatchedWeight
     * @return Parameters
     */
    public function setRangeKeywordMainSynonymDocumentMatchedWeight($rangeKeywordMainSynonymDocumentMatchedWeight)
    {
        $this->rangeKeywordMainSynonymDocumentMatchedWeight = $rangeKeywordMainSynonymDocumentMatchedWeight;

        return $this;
    }

    /**
     * Get rangeKeywordMainSynonymDocumentMatchedWeight
     *
     * @return float 
     */
    public function getRangeKeywordMainSynonymDocumentMatchedWeight()
    {
        return $this->rangeKeywordMainSynonymDocumentMatchedWeight;
    }

    /**
     * Set synonymKeywordMatchedWeight
     *
     * @param float $synonymKeywordMatchedWeight
     * @return Parameters
     */
    public function setSynonymKeywordMatchedWeight($synonymKeywordMatchedWeight)
    {
        $this->synonymKeywordMatchedWeight = $synonymKeywordMatchedWeight;

        return $this;
    }

    /**
     * Get synonymKeywordMatchedWeight
     *
     * @return float 
     */
    public function getSynonymKeywordMatchedWeight()
    {
        return $this->synonymKeywordMatchedWeight;
    }

    /**
     * Set url
     *
     * @param \Rss\RecommendBundle\Entity\Url $url
     * @return Parameters
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
