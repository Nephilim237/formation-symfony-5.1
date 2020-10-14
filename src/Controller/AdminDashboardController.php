<?php

namespace App\Controller;

use App\Service\Statisticator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param Statisticator $statsService
     * @return Response
     */
    public function index(Statisticator $statsService)
    {
        $stats = $statsService->getStats();
        $bestAds = $statsService->getAdsClassification('DESC');
        $worstAds = $statsService->getAdsClassification('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
        ]);
    }
}
