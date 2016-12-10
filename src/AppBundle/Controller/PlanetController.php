<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\Resource;
use AppBundle\Form\PlanetType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanetController extends Controller
{
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/planet/list", name="planet_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        /**
         * @var \AppBundle\Entity\Player $user
         */
        $user = $this->getUser();
        $planets = $user->getPlanets()->toArray();

        return $this->render('planet/list.html.twig', array('planets' => $planets));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/planet/create", name="planet_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        /**
         * @var \AppBundle\Entity\Planet $planet
         */
        $planet = new Planet();
        $form = $this->createForm(PlanetType::class, $planet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form['name']->getData()) {
                $this->addFlash('danger', 'Planet name cannot be empty.');
                return $this->redirectToRoute('planet_create');
            }

            $em = $this->getDoctrine()->getManager();

            // Assign random coordinates
            $planet->setX(rand(1, 100));
            $planet->setY(rand(1, 100));
            $planet->setPlayer($this->getUser());
            $em->persist($planet);
            $em->flush();

            // Set predefined amount of resources
            $resources = $this->getDoctrine()->getRepository(Resource::class)->findAll();
            /**
             * @var \AppBundle\Entity\Resource $resource
             */
            foreach ($resources as $resource) {
                $planetResource = new PlanetResource($planet->getId(), $resource->getId());
                $planetResource->setAmount(0);
                $planetResource->setPlanet($planet);
                $planetResource->setResource($resource);
                $em->persist($planetResource);
                $em->flush();
            }

            $this->addFlash('success', 'New planet successfully created.');
            return $this->redirectToRoute('planet_edit', array('id' => $planet->getId()));
        }

        return $this->render('planet/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/planet/edit/{id}", name="planet_edit")
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = 0, Request $request)
    {
        /**
         * @var \AppBundle\Entity\Planet $planet
         */
        $planet = $this->getDoctrine()->getRepository(Planet::class)->find($id);

        if ($planet === null) {
            return $this->redirectToRoute('planet_list');
        }

        /**
         * @var \AppBundle\Entity\Player $user
         */
        $user = $this->getUser();
        if (!$user->isPlanetOwner($planet)) {
            return $this->redirectToRoute('planet_list');
        }

        $x = $planet->getX();
        $y = $planet->getY();

        $form = $this->createForm(PlanetType::class, $planet);
        $form->handleRequest($request);

        $resources = $planet->getPlanetResources()->toArray();

/*        foreach ($resources as $resource) {
            $resource->setAmount(212);
        }*/


        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form['name']->getData()) {
                $this->addFlash('danger', 'Planet name cannot be empty.');
                return $this->redirectToRoute('planet_edit', array('id' => $id));
            }

            $em = $this->getDoctrine()->getManager();

            $planet->setX($x);
            $planet->setY($y);
            $planet->setPlayer($this->getUser());

            $em->persist($planet);
            $em->flush();

            return $this->redirectToRoute('planet_list');
        }

        return $this->render('planet/edit.html.twig',
            array(
                'planet' => $planet,
                'form' => $form->createView(),
                'resources' => $resources
            ));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/planet/delete/{id}", name="planet_delete")
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id = 0, Request $request)
    {
        /**
         * @var \AppBundle\Entity\Planet $planet
         */
        $planet = $this->getDoctrine()->getRepository(Planet::class)->find($id);

        if ($planet === null) {
            return $this->redirectToRoute('planet_list');
        }

        /**
         * @var \AppBundle\Entity\Player $user
         */
        $user = $this->getUser();
        if (!$user->isPlanetOwner($planet)) {
            return $this->redirectToRoute('planet_list');
        }

        $form = $this->createForm(PlanetType::class, $planet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planet);
            $em->flush();

            return $this->redirectToRoute('planet_list');
        }

        return $this->render('planet/delete.html.twig',
            array(
                'planet' => $planet,
                'form' => $form->createView()
            ));
    }
}
