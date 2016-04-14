<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_name', 'text', array('label'  => 'ユーザ名', 'max_length' => 32));
        $builder->add('mail_address', 'email', array('label'  => 'メールアドレス', 'required' => false));
        $builder->add('twitter_account', 'text', array('label'  => 'Twitterアカウント', 'required' => false));
        $builder->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'first_options'  => array('label' => 'パスワード'),
                'second_options' => array('label' => 'パスワード(確認用)'),
            )
        );
    }

    public function getName()
    {
        return 'login_user';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'login',
                    'data_class' => 'Rss\RecommendBundle\Entity\LoginUser',
                ]
        );
    }
    
}
