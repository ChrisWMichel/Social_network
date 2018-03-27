<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('firstname', TextType::class, [
            'label' => 'First Name',
              'required' => 'required',
              'attr' => [
                'class' => 'form-control'
              ]
          ])
          ->add('lastname', TextType::class, [
            'label' => 'Last Name',
              'required' => 'required',
              'attr' => [
                'class' => 'form-control'
              ]
          ])
          ->add('nick', TextType::class, [
            'label' => 'Nickname',
            'required' => 'required',
            'attr' => [
              'class' => 'form-control nick-input'
            ]
          ])
          ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => 'required',
            'attr' => [
              'class' => 'form-control'
            ]
          ])
          ->add('password', PasswordType::class, [
            'label' => 'Password',
              'required' => 'required',
            'attr' => [
              'class' => 'form-control'
            ]
          ])

          ->add('Register', SubmitType::class, [
            "attr" => [
              "class" => "form-submit btn btn-success",
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
            'data_class' => 'BackendBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_user';
    }


}
