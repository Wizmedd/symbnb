<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Ad::class)
            ->setPage($page)
            ->setLimit(15);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete",name="admin_ads_delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {

        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                "warning",
                "L'annonce <strong>{$ad->getTitle()}</strong> ne peut pas être suprrimée car elle possède au moins une réservation ! "
            );
        } else {
            $manager->remove($ad);

            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }



        return $this->redirectToRoute("admin_ads_index");
    }
}
