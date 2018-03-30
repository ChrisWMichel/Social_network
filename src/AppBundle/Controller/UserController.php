<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/17/2018
 * Time: 2:20 PM
 */

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\UserType;

class UserController extends Controller {

    private $entityManager;

    private $session;

    public function __construct(EntityManagerInterface $em) {
        $this->entityManager = $em;
        $this->session = new Session();
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request) {

        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
          'user/login.html.twig',
          [
            'last_username' => $lastUsername,
            'error' => $error,
          ]
        ); // AppBundle:User:login.html.twig   App::user:login.html.twig
    }

    /**
     * @Route("/login_check", name="loginCheck")
     */
    public function loginCheckAction() {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {

    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request) {

        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $query = $this->entityManager->createQuery(
              'SELECT u FROM BackendBundle:USER u WHERE u.email = :email OR u.nick = :nick'
            )
              ->setParameter('email', $form->get("email")->getData())
              ->setParameter('nick', $form->get("nick")->getData());

            $user_isset = $query->getResult();

            if (count($user_isset) == 0) {

                $factory = $this->get("security.encoder_factory");
                $encoder = $factory->getEncoder($user);

                $password = $encoder->encodePassword(
                  $form->get('password')->getData(),
                  $user->getSalt()
                );

                $user->setPassword($password);
                $user->setRole("ROLE_USER");
                $user->setImage(NULL);

                $em->persist($user);
                $flush = $em->flush();

                if ($flush == NULL) {
                    $status = 'Registration was successful!';

                    $this->session->getFlashBag()->add("status", $status);

                    return $this->redirect("login");
                }
                else {
                    $status = 'Registration failed.';
                }
            }
            else {
                $status = "The user already exists.";
            }
            $this->session->getFlashBag()->add("status", $status);
        }

        //

        return $this->render(
          'user/register.html.twig',
          ['form' => $form->createView()]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/nick-test", name="nickTest")
     */
    public function nickTestAction(Request $request) {
        $nick = $request->get("nick");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("BackendBundle:User");

        $user_isset = $user_repo->findOneBy(['nick' => $nick]);

        $result = "used";
        if ($user_isset !== NULL && is_object($user_isset)) {
            $result = "used";
        }
        else {
            $result = "unused";
        }

        return new JsonResponse(['result' => $result]);
    }

    /**
     * @Route("/edit-user", name="editUser")
     */
    public function editUserAction(Request $request) {

        $user = $this->getUser();
        $user_image = $user->getImage();
        $user_password = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $query = $this->entityManager->createQuery(
              'SELECT u FROM BackendBundle:USER u WHERE u.email = :email OR u.nick = :nick'
            )
              ->setParameter('email', $form->get("email")->getData())
              ->setParameter('nick', $form->get("nick")->getData());

            $user_isset = $query->getResult();


            if (count($user_isset) == 0 || ($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick()) ) {
//
                // upload file
                $file = $form["image"]->getData();

                if(!empty($file) && $file != null){

                    $file_name = $this->generateUniqueFileName().'.'.$file->guessExtension();

                    $file->move(
                      $this->getParameter('image_directory'),
                      $file_name
                    );
                    $user->setImage($file_name);

                }else{
                    $user->setImage($user_image);
                }
      // PASSWORD
                $new_pass = $form->get("password")->getData();

                if(!empty($new_pass) && $new_pass != null){
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($new_pass, $user->getSalt() );

                     $user->setPassword($password);
                }else{
                    $user->setPassword($user_password);
                }


                $em->persist($user);
                $flush = $em->flush();

                if ($flush == NULL) {
                    $status = 'Your profile has been updated.';
                }
                else {
                    $status = 'Something went wrong. Your profile was not updated.';
                }
            }
            else {
                $status = "Your profile has not been updated";
            }
            $this->session->getFlashBag()->add("status", $status);

            return $this->redirect("/edit-user");
        }

        return $this->render(
          'user/edit.html.twig',
          ['form' => $form->createView()]
        );
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/user-list", name="userList")
     */
    public function usersAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $stmt = "SELECT u FROM BackendBundle:User u ORDER BY u.id ASC";
        $query = $em->createQuery($stmt);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

       return $this->render('user/users.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/user-search", name="search")
     */
    public function searchAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $search = trim($request->query->get("search", null));

        if($search == null){
            return $this->redirect($this->generateUrl('home'));
        }

        $stmt = "SELECT u FROM BackendBundle:User u 
                 WHERE u.firstname LIKE :search 
                 OR u.lastname LIKE :search 
                 OR u.nick LIKE :search";
        $query = $em->createQuery($stmt)->setParameter('search', "%$search%");

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        return $this->render('user/users.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/user/{nickname}", name="profile")
     */
    public function profileAction(Request $request, $nickname = null){
        $em = $this->getDoctrine()->getManager();

        if($nickname != null){
            $user = $em->getRepository('BackendBundle:User')->findOneBy(['nick' => $nickname]);

        }else{
            $user = $this->getUser();
        }

        if(empty($user)){
            return $this->redirect($this->generateUrl('home'));
        }

        $user_id = $user->getId();
        $sql = "SELECT p FROM BackendBundle:Publication p WHERE p.user = $user_id ORDER BY p.id DESC";
        $query = $em->createQuery($sql);

        $paginator = $this->get('knp_paginator');
        $publications = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        return $this->render('user/profile.html.twig', [
          'user' => $user,
          'pagination' => $publications
        ]);

    }
}