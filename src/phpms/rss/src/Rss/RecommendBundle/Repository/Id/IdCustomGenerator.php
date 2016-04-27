<?php

namespace Rss\RecommendBundle\Repository\Id;

use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\EntityManager;
use Rss\RecommendBundle\Log\Log;

/**
 * AUTO_INCREMENTのID（主キー）の次の値を取得するためのクラス
 * $EntityManager->persist(),merge()などを実行する毎に処理が実行される
 * エンティティに値を設定する場合は、$EntityManager->refresh()が必要
 */
class IdCustomGenerator extends AbstractIdGenerator 
{
    public function generate(EntityManager $em, $entity)
    {
        $id = $entity->getId();
        if ( isset($id) ) {
            Log::info("SET ID:" . $id);
            return $id;
        }
        // エンティティのメタデータからtable名を取得
        $cmd = $em->getMetadataFactory();
        $meta = $cmd->getMetadataFor(get_class($entity));
        $table_name = $meta->table['name'];
        Log::info("TABLE NAME:" . $table_name);
        // Auto_increment の値を取得
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('Auto_increment', 'Auto_increment');
        $query = $em->createNativeQuery("SHOW TABLE STATUS LIKE :table_name", $rsm);
        $query->setParameter("table_name", $table_name);
        $result = $query->getResult();
        $next_id = $result[0]['Auto_increment'];
        Log::info("NEXT ID:" . $next_id);
        // Auto_increment の値を更新
        $con = $em->getConnection();
        $update_next_id = intval($next_id) + 1;
        // テーブル名は引用符('')で囲まれるため文字列結合する
        $con->executeUpdate(
            "ALTER TABLE " . $table_name . " AUTO_INCREMENT=:update_next_id",
            ["update_next_id" => $update_next_id],
            ["update_next_id" => "integer"]
        );
        Log::info("UPDATE ID:" . $update_next_id);
        return $next_id;
    }
}
