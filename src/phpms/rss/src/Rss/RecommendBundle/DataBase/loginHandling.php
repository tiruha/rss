<?php
namespace Rss\RecommendBundle\DataBase;

use Rss\RecommendBundle\DataBase\Database;
use Rss\RecommendBundle\Entity\LoginUser;
/** 
 * データベースにアクセスが必要なログイン操作を行うクラス
 * 
 */
class loginHandling
{
    /**
     * パスワードのハッシュ化時に付加する文字列
     * @see equalsPasswordHash
     */
    const SALT = "loginSalt";
    
    /**
     * パスワードにSALTを付加してハッシュ化を行い、パスワードの正しさをチェックする
     * @param LoginUser $login
     * @return bool パスワードのハッシュが一致した場合 TRUE を返す
     */
    public function equalsPasswordHash( LoginUser $login )
    {
        $pepper = hash( 'SHA256', 'saltandpepper' );
        $hash_pass = hash( 'SHA256', $pepper . $login->getPassword() . self::SALT );
        
        $login_repository = new Database().loginConnect();
        $user_login = $login_repository->findOneByUsername($login->getUsername());
        if ($user_login) {
            return $user_login->getPassword() === $hash_pass;
        } else {
            throw $this->createNotFoundException('No user infomation found for user_name '.$login->getUsername());
        }
    }
}
