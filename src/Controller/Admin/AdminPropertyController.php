<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    private $repo;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repo, ObjectManager $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * @Route("/admin/properties", name="admin.properties.index")
     */
    public function index()
    {
        $properties = $this->repo->findAll();

        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash("success","Modifier avec succés");
            return $this->redirectToRoute('admin.properties.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/create" , name="admin.property.create")
     */
    public function create(Request $request)
    {

        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();

            $this->addFlash("success","Créer avec succés");
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }


        return $this->render('admin/property/create.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/property/delete/{id}" , name="admin.property.delete", methods="DELETE")
     */
    public function deleteProperty(Property $property ,Request $request)
    {
        if($this->isCsrfTokenValid('delete',$property->getId(),$request->get('_token'))){
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash("success","Supprimer avec succés");
        }
            return $this->redirectToRoute('admin.properties.index');


    }
}

