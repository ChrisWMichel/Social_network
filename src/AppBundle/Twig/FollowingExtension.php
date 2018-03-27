<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/23/2018
 * Time: 4:49 PM
 */

namespace AppBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;

class FollowingExtension extends \Twig_Extension {

    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistry = $managerRegistry;
    }

    public function getFilters() {

        return [
          new \Twig_SimpleFilter('following', [$this, 'followingFilter'])
        ];

    }

    public function followingFilter($user, $followed){
        $following_info = $this->managerRegistry->getRepository('BackendBundle:Following');
        $user_following = $following_info->findOneBy([
                'user' => $user,
                'followed' => $followed
                ]);

        if(!empty($user_following) && is_object($user_following)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    /*public function getName(){
        return 'following_extension';
    }*/
}