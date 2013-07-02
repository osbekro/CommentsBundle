<?php

namespace Osbekro\CommentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    private $anonymous;

    public function __construct($anonymous = false)
    {
        $this->anonymous = $anonymous;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body')
        ;
        if ($this->anonymous === true) {
            $builder->add('author_name');
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Osbekro\CommentsBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'osbekro_commentsbundle_commenttype';
    }
}
