<?php

namespace Rss\RecommendBundle\TextAnalysis\API;

require_once 'HTTP/Request2.php';
#autoload.php';

/**
 * YahooAPIs
 *
 * @author tiruha
 */
class YahooAPI {
    // ここにあなたのアプリケーションIDを設定してください。
    const APP_ID = 'dj0zaiZpPU5rVXRad3VROFBnRSZkPVlXazljMkZuWVdoeE5tc21jR285TUEtLSZzPWNvbnN1bWVyc2VjcmV0Jng9ZWQ-';

    /**
     * 形態素解析
     * 
     * @param string $sentence
     */
    public function morphologicalAPI($sentence){
        
        try{
            $url = "http://jlp.yahooapis.jp/MAService/V1/parse";
            $sentence_encode = mb_convert_encoding($sentence, 'utf-8');
            $ma_response = "pos,baseform";
            $ma_filter = "9|10";
            $parameter = [
                    "appid" => self::APP_ID,
                    "results" => "ma",
                    "ma_response" => $ma_response,
                    "ma_filter" => $ma_filter,
                    "sentence" => $sentence_encode
                ];
            $query_string = http_build_query( $parameter );
            
            $request = new \HTTP_Request2();
            $request->setMethod(\HTTP_Request2::METHOD_GET);
            $request->setUrl($url . $query_string);
            $response = $request->send();
            $data = $response->getBody();
            $xml = simplexml_load_string($data);
            $i = 0;
            if ($response->getStatus() == 200) {
                foreach ($xml->ma_result->word_list->word as $cur) {
                    $arry1[$i][0] = $cur->pos;
                    $arry1[$i][1] = $cur->baseform;
                    $i++;
                }
            }
            return $arry1;
        } catch (\HTTP_Request2_Exception $e) {
            die($e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
}
