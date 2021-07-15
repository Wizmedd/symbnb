<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
// use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        //plus besoin d'utiliser get repository grace à l'injection de dépendances
        //$repo = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    //on veut créer une nouvelle annonce 
    //(on le place avant la function ads_show sinon pb ParamConverter)
    /**
     * @Route("ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request)
    {
        $ad = new Ad();
        /* $form = $this->createFormBuilder($ad)
            ->add('title')
            ->add('price')
            ->add('introduction')
            ->add('content')
            ->add('coverImage')
            ->add('rooms')
            ->add('latitude')
            ->add('longitude')
            ->add('city')
            ->add('address')
            ->add('postalCode')
            ->add('save', SubmitType::class, [
                'label' => 'Valider votre annonce',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
            */

        //  $image = new Image();
        //  $image->setUrl('http://place-hold.it/400x200')
        //    ->setCaption('titre test image');

        //  $ad->addImage($image);



        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        //dump($ad);






        if ($form->isSubmitted() && $form->isValid()) {
            //pb avec ObjectManager
            $manager = $this->getDoctrine()->getManager();

            //faire persister les collection d'images
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée."
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le formulaire d'une annonce
     * @Route("/ads/{slug}/edit",name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas ! Vous ne pouvez pas la modifier")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //faire persister les collection d'images
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée."
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }


    /**
     * Permet d'afficher une seule annonce
     * @Route("ads/{slug}", name="ads_show")
     * 
     */


    /* public function show($slug, AdRepository $repo)
    {
        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
    */

    //grace au ParamConverter on n'as plus besoin d'utiliser $repo
    public function show(Ad $ad)
    {


        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("/ads/{slug}/delete",name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor() ", message="Cette annonce ne vous appartient pas ! Vous ne pouvez pas la supprimer")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('ads_index');
    }
}
