<?php

namespace Rss\RecommendBundle\TextAnalysis;

use Rss\RecommendBundle\TextAnalysis\API\YahooAPI;
use Rss\RecommendBundle\TextAnalysis\API\ApitoreAPI;

/**
 * 機械学習API
 *
 * @author tiruha
 */
class MachineLearningApi {
    /**
     * 形態素解析
     * 
     * @param string $sentence
     * @return $result_array[pos][baseform]
     */
    public function morphological($sentence){
        $api = new YahooAPI();
        return $api->keyphraseAPI($sentence);
    }
    
    /**
     * キーフレーズ抽出
     * 
     * @param string $sentence
     * @return $result_array[Keyphrase][Score]
     */
    public function keyphrase($sentence){
        $api = new YahooAPI();
        return $api->keyphraseAPI($sentence);
    }
    
    /**
     * 類義語/同義語検索
     * 
     * @param string $sentence
     * @return $result_array[word][distance]
     */
    public function distance($sentence){
        $api = new ApitoreAPI();
        return $api->distanceAPI($sentence);
    }
    
    /**
     * 単語同士の類似度
     * 
     * @param String $word1
     * @param String $word2
     * @return double $result_similarity
     */
    public function similarity($word1, $word2){
        $api = new ApitoreAPI();
        return $api->similarityAPI($word1, $word2);
    }
}
