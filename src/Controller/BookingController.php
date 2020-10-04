<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();

            $booking->setAd($ad)
                    ->setBooker($user)
            ;

            if (!$booking->isBookableDays()){
                $this->addFlash('warning', "<h4 class='font-weight-normal'>L'hébergement <strong>{$ad->getTitle()}</strong> est non disponible a cette date</h4>");
            } else {
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show', [
                    'id'    => $booking->getId(),
                    'g'     => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad'    => $ad,
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser())
                    ->setAd($booking->getAd())
            ;

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Commentaire pris en compte. Merci');
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
