<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/27/2018
 * Time: 3:58 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends Controller{

    /**
     * @Route("/notification", name="notification")
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $user_id = $user->getId();

        $sql = "SELECT n FROM BackendBundle:Notification n WHERE n.user = $user_id ORDER BY n.id DESC";
        $query = $em->createQuery($sql);

        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        $notification = $this->get('app.notification_service');
        $notification->read($user);

        return $this->render('Notification/notification.html.twig', [
          'user' => $user,
          'pagination' => $notifications
        ]);
    }

    /**
     * @Route("/notification/count", name="notificationCount")
     */
    public function countNotificationsAction(){
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('BackendBundle:Notification')->findBy([
          'user' => $this->getUser(),
            'readed' => 0
        ]);

        return new JsonResponse(['count' => count($notifications)]);
    }

    /**
     * @Route("/notification/remove", name="notificationRemove")
     */
    public function removeNotificationAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('BackendBundle:Notification')->findOneBy([
          'id' => $request->get('id')
        ]);

        $em->remove($notifications);
        $em->flush();

        return new JsonResponse(['status' => 'Notification has been removed.']);
    }
}