<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/27/2018
 * Time: 5:15 PM
 */

namespace AppBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;

class GetUserExtension extends \Twig_Extension {
    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistry = $managerRegistry;
    }

    public function getFilters() {
        return [
          new \Twig_SimpleFilter('get_user', [$this, 'getUserFilter'])
        ];
    }

    public function getUserFilter($user_id){
        $user = $this->managerRegistry->getRepository('BackendBundle:User')->findOneBy([
          "id" => $user_id,
        ]);

        if(!empty($user)){
            $result = $user;
        }else{
            $result = false;
        }

        return $result;
    }

}