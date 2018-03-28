<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/25/2018
 * Time: 3:40 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\Like;
use Symfony\Component\HttpFoundation\JsonResponse;

class LikeController extends Controller{

    /**
     * @Route("/like", name="like")
     */
    public function likeAction(Request $request){
        $user = $this->getUser();
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $publication = $em->getRepository('BackendBundle:Publication')->find($id);

        $like = new Like();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $flush = $em->flush();

        if($flush == NULL){
            $notification = $this->get('app.notification_service');
            $notification->set($publication->getUser(), 'like', $user->getId(), $publication->getId());

            $status = 'You like this post';
        }else{
            $status = 'Could not save the like.';
        }

        return new JsonResponse(['status' => $status]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/unlike", name="unlike")
     */
    public function unlikeAction(Request $request){
        $user = $this->getUser();
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $like = $em->getRepository('BackendBundle:Like')->findOneBy(['user' => $user, 'publication' => $id]);

        $em->remove($like);
        $flush = $em->flush();

        if($flush == null){
            $status = 'You unliked this post.';
        }else{
            $status = 'There was a problem with unliking this post.';
        }

        return new JsonResponse(['status' => $status]);
    }

    /**
     * @Route("/likes/{nickname}", name="likes")
     */
    public function likesAction(Request $request, $nickname = null){
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
        $sql = "SELECT l FROM BackendBundle:Like l WHERE l.user = $user_id ORDER BY l.id DESC";
        $query = $em->createQuery($sql);

        $paginator = $this->get('knp_paginator');
        $likes = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        //dump($paginator); die;

        return $this->render('Likes/likes.html.twig', [
          'profile_user' => $user,
          'pagination' => $likes
        ]);

    }

}