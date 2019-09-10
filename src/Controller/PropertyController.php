<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    private $repository;
    public function __construct(PropertyRepository $property_repo)
    {
        $this->repository = $property_repo;
    }

    /**
     * @Route("/biens", name="property.index")
     */
    public function index()
    {
        $properties = $this->repository->findAllVisible();

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/bien/{slug}-{id}" , name="bien.show", requirements={"id"="\d+","slug"="[a-z0-9\-]*"})
     */
    public function show(Property $propertie, string $slug){
        if($propertie->getSlug()!==$slug){
        return $this->redirectToRoute('bien.show',[
            'id' => $propertie->getId(),
            'slug'=>$propertie->getSlug()
        ],301);
        }
        $property = $this->repository->find($propertie);
        return $this->render('property/show.html.twig', [
            'current_menu' => 'properties',
            'property' => $property
        ]);

    }
}
