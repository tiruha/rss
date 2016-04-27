<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SynonymType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('synonym', 'text',
            array(
                'label'    => '検索語(カンマ区切り)',
                'required' => false,
            )
        );
    }

    public function getName()
    {
        return 'synonym';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'url',
                    'data_class' => 'Rss\RecommendBundle\Entity\Synonym',
                    'csrf_protection' => true,
                    'csrf_field_name' => '_token',
                ]
        );
    }
    
}
