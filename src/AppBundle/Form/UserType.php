<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
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
              'class' => 'form-control'
            ]
          ])
          ->add('bio', TextareaType::class, [
            'label' => 'Biography',
            'required' => FALSE,
            'attr' => [
              'class' => 'form-control from-bio'
            ]
          ])
          ->add('image', FileType::class, [
            'data_class' => null,
            'required' => FALSE,
            'label' => 'Image',
            'attr' => [
              'class' => 'form-control from-image'
            ]
          ])
          ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => 'required',
            'data_class' => null,
            'attr' => [
              'class' => 'form-control form-email'
            ]
          ])
          ->add('password', PasswordType::class, [
            'label' => 'Password',
            'required' => false,
            'attr' => [
              'class' => 'form-control'
            ]
          ])

          ->add('Update', SubmitType::class, [
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
