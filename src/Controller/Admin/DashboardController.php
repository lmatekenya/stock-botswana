<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use App\Entity\Feature;
use App\Entity\Sector;
use App\Entity\Testimonial;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Counts
        $featureCount = $this->entityManager->getRepository(Feature::class)->count([]);
        $sectorCount = $this->entityManager->getRepository(Sector::class)->count([]);
        $testimonialCount = $this->entityManager->getRepository(Testimonial::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);

        // Monthly stats
        $monthlyUserStats = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT DATE_FORMAT(created_at, "%Y-%m") AS month, COUNT(id) AS total
             FROM users
             GROUP BY month
             ORDER BY month ASC'
        );

        $monthlyFeatureStats = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT DATE_FORMAT(created_at, "%Y-%m") AS month, COUNT(id) AS total
             FROM features
             GROUP BY month
             ORDER BY month ASC'
        );

        // Users by type
        $userTypeStats = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT user_type, COUNT(id) AS total
             FROM users
             GROUP BY user_type'
        );

        // Features by sector
        $featureSectorStats = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT s.name AS sector, COUNT(f.id) AS total
     FROM features f
     JOIN sectors s ON f.sector_id = s.id
     GROUP BY s.name'
        );


        // Testimonials by month
        $monthlyTestimonialStats = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT DATE_FORMAT(created_at, "%Y-%m") AS month, COUNT(id) AS total
             FROM testimonials
             GROUP BY month
             ORDER BY month ASC'
        );

        return $this->render('admin/dashboard.html.twig', [
            'featureCount' => $featureCount,
            'sectorCount' => $sectorCount,
            'testimonialCount' => $testimonialCount,
            'userCount' => $userCount,
            'monthlyUserStats' => $monthlyUserStats,
            'monthlyFeatureStats' => $monthlyFeatureStats,
            'userTypeStats' => $userTypeStats,
            'featureSectorStats' => $featureSectorStats,
            'monthlyTestimonialStats' => $monthlyTestimonialStats,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pula Market Admin Panel')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Content Management');
        yield MenuItem::linkToCrud('Features', 'fa fa-star', Feature::class);
        yield MenuItem::linkToCrud('Sectors', 'fa fa-industry', Sector::class);
        yield MenuItem::linkToCrud('Testimonials', 'fa fa-comment', Testimonial::class);

        yield MenuItem::section('User Management');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);

        yield MenuItem::section('External Links');
        yield MenuItem::linkToUrl('API Documentation', 'fa fa-book', '/api/docs');
        yield MenuItem::linkToUrl('Website', 'fa fa-globe', '/');
    }
}
