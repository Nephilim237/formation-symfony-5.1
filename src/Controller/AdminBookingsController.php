<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingsController extends AbstractController
{
    /**
     * Liste de toutes les reservations
     * @Route("/admin/bookings", name="admin_bookings_index")
     *
     * @param BookingRepository $repo
     * @return Response
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/bookings/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }

    /**
     * Edition d'une reservation par l'administrateur
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash('success', "La mise a jour de la reservation <strong>#{$booking->getId()}</strong> appartenant a <strong>{$booking->getBooker()->getFullName()}</strong> a bien ete prise en compte");

            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/bookings/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une reservation
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function delete(Booking $booking, ObjectManager $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La reservation <strong>#{$booking->getId()}</strong> de <strong>{$booking->getBooker()->getFullName()}</strong> a bien ete supprimée."
        );

        return $this->redirectToRoute('admin_bookings_index');
    }
}
