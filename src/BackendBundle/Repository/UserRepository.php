<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/28/2018
 * Time: 1:23 PM
 */

namespace BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{

    public function getFollowingUsers($user){
        $em = $this->getEntityManager();

        $following = $em->getRepository('BackendBundle:Following')->findBy(['user' => $user]);

        $following_array = [];
        foreach($following as $follow){
            $following_array = $follow->getFollowed();
        }

        $users_info = $em->getRepository('BackendBundle:User');

          $users = $users_info->createQueryBuilder('u')
                    ->where("u.id != :user AND u.id IN (:following)")
                    ->setParameter('user', $user->getId())
                    ->setParameter('following', $following_array)
                    ->orderBy('u.id', 'DESC');

        return $users;
    }
}