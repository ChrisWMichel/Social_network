<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/28/2018
 * Time: 11:22 AM
 */

namespace AppBundle\Form;

use BackendBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivateMessageType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['empty_data'];

        $builder
          ->add('receiver', EntityType::class, [
            'class' => 'BackendBundle\Entity\User',
              'query_builder' => function($er) use($user){
                  return $er->createQueryBuilder('u');
                    //return $er->getFollowingUsers($user);
              },
              'choice_label' => function($user){
                return $user->getFirstname() . " " . $user->getLastname() . " - " . $user->getNick();
              },
              'label' => 'For:',
              'attr' => ['class' => "form-control"]
          ])
          ->add('message', TextareaType::class, [
            'label' => 'Message',
            'required' => 'required',
            'attr' => [
              'class' => 'form-control form-message'
            ]
          ])
          ->add('image', FileType::class, [
            'data_class' => null,
            'required' => FALSE,
            'label' => 'Image',
            'attr' => [
              'class' => 'form-control form-message'
            ]
          ])
          ->add('file', FileType::class, [
            'data_class' => null,
            'required' => FALSE,
            'label' => 'Archive',
            'attr' => [
              'class' => 'form-control form-message'
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'BackendBundle\Entity\PrivateMessage'
        ));
        //$resolver->setRequired('entity_manager');
    }
}