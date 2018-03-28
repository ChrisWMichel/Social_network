<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/27/2018
 * Time: 11:58 AM
 */

namespace AppBundle\Services;

use BackendBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;

class NotificationService {

    public $manager;

    public function __construct($manager) {

        $this->manager = $manager;
    }

    public function set($user, $type, $typeId, $extra = null){

        $em = $this->manager;

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType($type);
        $notification->setTypeId($typeId);
        $notification->setReaded(0);
        $notification->setCreatedAt(new \DateTime('now'));
        $notification->setExtra($extra);

        $em->persist($notification);
        $flush = $em->flush();

        if($flush == null){
            $status = true;
        }else{
            $status = false;
        }

        return $status;
    }

    public function read($user){
        $em = $this->manager;

        $notifications = $em->getRepository('BackendBundle:Notification')->findBy(['user' => $user]);

        foreach($notifications as $notice){
            $notice->setReaded(1);

            $em->persist($notice);
        }
        $em->flush();

        return true;
    }
}