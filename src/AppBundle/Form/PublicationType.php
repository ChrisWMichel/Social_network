<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/24/2018
 * Time: 11:30 AM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PublicationType extends AbstractType{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('text', TextareaType::class, [
            'label' => 'Message',
            'required' => 'required',
            'attr' => [
              'class' => 'form-control'
            ]
          ])
          ->add('image', FileType::class, [
            'data_class' => null,
            'required' => FALSE,
            'label' => 'Image',
            'attr' => [
              'class' => 'form-control'
            ]
          ])
          ->add('document', FileType::class, [
            'data_class' => null,
            'required' => FALSE,
            'label' => 'Document',
            'attr' => [
              'class' => 'form-control'
            ]
          ])
          ->add('Submit', SubmitType::class, [
            "attr" => [
              "class" => "btn btn-success",
              "style" => "margin-top: 10px"
            ]
          ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'BackendBundle\Entity\Publication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    /*public function getBlockPrefix()
    {
        return 'backendbundle_publication';
    }*/
}