<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_name', 'text');
        $builder->add('mail_address', 'email', array('required' => false));
        $builder->add('twitter_account', 'text', array('required' => false));
        $builder->add('password', 'password');
    }

    public function getName()
    {
        return 'login_user';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'login',
                ]
        );
    }
    
}
