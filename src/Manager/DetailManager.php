<?php 

namespace App\Manager; 

use App\Entity\Detail;
use Doctrine\ORM\EntityManagerInterface;


class DetailManager
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function setDetail($Detail){
        if($Detail instanceof Detail){
            $this->em->persist($Detail);
            $this->em->flush();
        }
    }
}