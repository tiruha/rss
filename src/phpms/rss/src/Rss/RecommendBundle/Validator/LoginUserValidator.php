<?php
namespace Rss\RecommendBundle\Validator;

use Symfony\Component\Validator\ExecutionContextInterface;
use Rss\RecommendBundle\Entity\LoginUser;

/**
 * Description of LoginUserValidator
 * LoginUser に対する Validation を定義
 */
class LoginUserValidator {
    /**
     * @param LoginUser $user_data
     * @param ExecutionContextInterface $context
     */
    public static function isContactAddressInputed(LoginUser $user_data, ExecutionContextInterface $context)
    {
        if ( empty($user_data->getMailAddress()) && empty($user_data->getTwitterAccount()) ) {
            $context->buildViolation('「Eメール」か「Twitterアカウント」のどちらかを指定してください。')
                ->atPath('mail_address')
                ->addViolation();
        }
    }
}
