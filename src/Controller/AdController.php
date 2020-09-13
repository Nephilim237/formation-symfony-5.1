<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $repo
     * @return Response
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Pour l'ajout d'une nouvelle annonce
     *
     * @Route("ads/new", name="ads_new")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success', "l'annonce <strong>{$ad->getTitle()}</strong> à été enregistrée avec succès");
            return $this->redirectToRoute('ads_show', ['slug' => $ad->getSlug()]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Pour l'edition et la mise a jour d'un article
     *
     * @Route("ads/{slug}/edit", name="ads_edit")
     *
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Droit d'accès refusé")
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

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success', "Article <strong> <em>{$ad->getTitle()}</em></strong> modifie avec succès");
            return $this->redirectToRoute('ads_show', ['slug' => $ad->getSlug()]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * @param Ad $ad
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     *
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Besoin d'une autorisation verifiee pour supprimer")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash('success', "L'annonce numéro <strong> {$ad->getId()}, {$ad->getTitle()} </strong> a ete supprimée avec succès.");

        return $this->redirectToRoute('account_index');
    }
}
