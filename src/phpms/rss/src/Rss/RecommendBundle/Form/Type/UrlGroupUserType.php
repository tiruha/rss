<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Rss\RecommendBundle\Log\Log;

class UrlGroupUserType extends AbstractType
{
    private $doctrine, $service_container;
    private $user, $repository;

    public function __construct($doctrine, $service_container)
    {
        $this->doctrine = $doctrine;
        $this->service_container = $service_container;
        $this->user = $service_container->get('security.context')->getToken()->getUser();
        $this->repository = $doctrine->getRepository('RssRecommendBundle:UrlGroupUser');
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $query = $this->repository->createQueryBuilder('g')
            ->where('g.user = :user_id')
            ->setParameter('user_id', $this->user->getId())
            ->orderBy('g.urlGroupName', 'ASC')
            ->getQuery();
        $groups = $query->getResult();
        $groups_array = [];
        $group_counter = 0;
        foreach ($groups as $group) {
            $groups_array["userid" . $this->user->getId() . "_group" . $group_counter] = $group->getUrlGroupName();
            $group_counter++;
        }
        $builder->add('url_group', 'choice', array(
            'choices' => $groups_array,
            'label'    => 'グループ',
            'required' => false,
            'empty_value' => false,
            'expanded' => false,
            'multiple' => false,
        ));
    }

    public function getName()
    {
        return 'url_group_user';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'url_group',
                    'data_class' => 'Rss\RecommendBundle\Entity\UrlGroupUser',
                    'csrf_protection' => true,
                    'csrf_field_name' => '_token',
                ]
        );
    }
    
}
