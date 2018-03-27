<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/25/2018
 * Time: 7:07 PM
 */

namespace AppBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;

class UserStatsExtension extends \Twig_Extension{
    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistry = $managerRegistry;
    }

    public function getFilters() {
        return [
          new \Twig_SimpleFilter('user_stats', [$this, 'userStatsFilter'])
        ];
    }

    public function userStatsFilter($user){
        $following = $this->managerRegistry->getRepository('BackendBundle:Following')->findBy(['user' => $user]);
        $followers = $this->managerRegistry->getRepository('BackendBundle:Following')->findBy(['followed' => $user]);
        $publications = $this->managerRegistry->getRepository('BackendBundle:Publication')->findBy(['user' => $user]);
        $likes = $this->managerRegistry->getRepository('BackendBundle:Like')->findBy(['user' => $user]);

        $result = [
          'following' => count($following),
          'followers' => count($followers),
          'publications' => count($publications),
          'likes' => count($likes)
        ];

        return $result;
    }
}