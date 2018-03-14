<?php

namespace Rss\RecommendBundle\TextAnalysis;

use Rss\RecommendBundle\TextAnalysis\API\YahooAPI;
use Rss\RecommendBundle\TextAnalysis\API\ApitoreAPI;
use Rss\RecommendBundle\Log\Log;

/**
 * 機械学習API
 *
 * @author tiruha
 */
class MachineLearningApi {
    
    const SPRIT_LENGTH = 400;

    /**
     * 形態素解析
     * 
     * @param string $sentence
     * @return $result_array[pos][baseform]
     */
    public function morphological($sentence){
        $morpho_array = array();
        $api = new YahooAPI();
        // yahooAPIの文字数制限
        // 400文字ずつに本文を区切って配列に代入
        $sentence_split = self::mb_str_split($sentence, self::SPRIT_LENGTH);
        foreach ($sentence_split as $sentence_buf){
            Log::debug("文字列分割：" . $sentence_buf);
        }
        $keitaiso_i = 0;
        foreach ($sentence_split as $sentence_buf){
            $return_morpho = $api->morphologicalAPI($sentence_buf);
            foreach ($return_morpho as $morpho){
                // 品詞
                $morpho_array[$keitaiso_i][0] = $morpho[0];
                // 要素
                $morpho_array[$keitaiso_i][1] = $morpho[1];
                $keitaiso_i++;
            }
        }
        return $morpho_array;
    }
    
    /**
     * キーフレーズ抽出
     * 
     * @param string $sentence
     * @return $result_array[Keyphrase][Score]
     */
    public function keyphrase($sentence){
        $keyphrase_array = array();
        $api = new YahooAPI();
        // yahooAPIの文字数制限
        // 400文字ずつに本文を区切って配列に代入
        $sentence_split = self::mb_str_split($sentence, self::SPRIT_LENGTH);
        foreach ($sentence_split as $sentence_buf){
            Log::debug("文字列分割：" . $sentence_buf);
        }
        $kye_i = 0;
        foreach ($sentence_split as $sentence_buf){
            $return_keyphrase = $api->keyphraseAPI($sentence_buf);
            foreach ($return_keyphrase as $keyphrase){
                // キーフレーズ
                $keyphrase_array[$kye_i][0] = $keyphrase[0];
                // スコア
                $keyphrase_array[$kye_i][1] = $keyphrase[1];
                $kye_i++;
            }
        }
        return $keyphrase_array;
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
    
    /**
     * マルチバイト対応の文字列分割
     * 
     * @param string $str
     * @param int $split_len
     * @return type $str_split_array
     */
    private function mb_str_split($str, $split_len = 1) {
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
        if ($split_len <= 0) {
            $split_len = 1;
        }
        $strlen = mb_strlen($str, 'UTF-8');
        $ret = array();
        for ($i = 0; $i < $strlen; $i += $split_len) {
            $ret[] = mb_substr($str, $i, $split_len);
        }
        return $ret;
    }

}
