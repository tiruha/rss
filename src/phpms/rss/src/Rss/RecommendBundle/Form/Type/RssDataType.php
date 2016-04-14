<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RssDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('URL', 'url', array('label'  => '登録サイトURL', 'required' => false));
        $builder->add('Synonym', 'text', array('label'  => '類義語', 'required' => false));
        $builder->add('SimilarURL', 'url', array('label'  => '類似サイトURL', 'required' => false));
    }

    public function getName()
    {
        return 'rss_data';
    }
}
