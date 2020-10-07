<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AType;
use App\Repository\AdRepository;
use App\Service\Paginator;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     *
     * @param AdRepository $repo
     * @param Paginator $paginator
     * @param int $page
     * @return Response
     */
    public function index(AdRepository $repo, Paginator $paginator, $page)
    {
        $paginator  ->setEntityClass(Ad::class)
                    ->setCurrentPage($page)
        ;

        return $this->render('admin/ad/index.html.twig', [
            'dataContain' => $paginator
        ]);
    }

    /**
     * Affiche el formulaire d'edition d'un article pour un administrateur
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $slugify = new Slugify();
            $slug = $slugify->slugify($ad->getTitle());
            $ad->setSlug($slug);

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash("success", "Annonce <strong>{$ad->getTitle()}</strong> modifiee avec succès");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
//        if (count($ad->getBookings()) > 0){
//            $this->addFlash("warning", "L'annonce <strong> {$ad->getTitle()}</strong> possède deja des reservations");
//        } else {
//            $manager->remove($ad);
//            $manager->flush();
//
//            $this->addFlash('success', "L'annonce  <strong> {$ad->getTitle()}</strong> a ete supprimée avec success");
//        }


        $manager->remove($ad);
        $manager->flush();
        $this->addFlash('success', "L'annonce  <strong> {$ad->getTitle()}</strong> a ete supprimée avec success");

        return $this->redirectToRoute('admin_ads_index');
    }
}
