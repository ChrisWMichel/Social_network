<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 3/25/2018
 * Time: 5:15 PM
 */

namespace AppBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;

class LikedExtension extends \Twig_Extension {

    protected $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistry = $managerRegistry;
    }

    public function getFilters() {
        return [
          new \Twig_SimpleFilter('liked', [$this, 'likedFilter'])
        ];
    }

    public function likedFilter($user, $publication){
        $like = $this->managerRegistry->getRepository('BackendBundle:Like')->findOneBy([
                    "user" => $user,
                    "publication" => $publication
                ]);

        if(!empty($like)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }


}