<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/23/2018
 * Time: 2:20 PM
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\Following;


class FollowingController extends Controller{

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    /**
     * @Route("/follow", name="follow")
     */
    public function followAction(Request $request){
        $user = $this->getUser();

        $followed_id = $request->get('followed');

        $followed = $this->getDoctrine()->getRepository('BackendBundle:User')->find($followed_id);

        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);

        $em = $this->getDoctrine()->getManager();

        $em->persist($following);
        $flush = $em->flush();

        if($flush == null){
            $notification = $this->get('app.notification_service');
            $notification->set($followed, 'follow', $user->getId());

            $status = "You are now following this user!";
        }else{
            $status = "You are not following this user.";
        }

        return new Response($status);

    }

    /**
     * @Route("/unfollow", name="unfollow")
     */
    public function unfollowAction(Request $request){
        $user = $this->getUser();

        $followed_id = $request->get('followed');

        $followed = $this->getDoctrine()->getRepository('BackendBundle:Following')->findOneBy([
                      'user'=>$user,
                      'followed' => $followed_id
                    ]);



        $em = $this->getDoctrine()->getManager();

        $em->remove($followed);
        $flush = $em->flush();

        if($flush == null){
            $status = "You are no longer following this user!";
        }else{
            $status = "You are still following this user.";
        }

        return new Response($status);

    }

    /**
     * @Route("/following/{nickname}", name="following")
     */
    public function followingAction(Request $request, $nickname = null){
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
        $sql = "SELECT f FROM BackendBundle:Following f WHERE f.user = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($sql);

        $paginator = $this->get('knp_paginator');
        $following = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        //dump($paginator); die;

        return $this->render('Following/following.html.twig', [
          'type' => 'following',
          'profile_user' => $user,
          'pagination' => $following
        ]);

    }

    /**
     * @Route("/followed/{nickname}", name="followed")
     */
    public function followedAction(Request $request, $nickname = null){
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
        $sql = "SELECT f FROM BackendBundle:Following f WHERE f.followed = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($sql);

        $paginator = $this->get('knp_paginator');
        $followed = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        //dump($paginator); die;

        return $this->render('Following/following.html.twig', [
          'type' => 'followed',
          'profile_user' => $user,
          'pagination' => $followed
        ]);

    }
}