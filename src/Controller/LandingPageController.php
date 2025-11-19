<?php
// src/Controller/LandingPageController.php

namespace App\Controller;

use App\Entity\Sector;
use App\Entity\Feature;
use App\Entity\Testimonial;
use App\Entity\Slide;
use App\Repository\SectorRepository;
use App\Repository\FeatureRepository;
use App\Repository\TestimonialRepository;
use App\Repository\SlideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class LandingPageController extends AbstractController
{
    public function __construct(
        private SectorRepository $sectorRepository,
        private FeatureRepository $featureRepository,
        private TestimonialRepository $testimonialRepository,
        private SlideRepository $slideRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    // SECTOR ENDPOINTS

    #[Route('/sectors', name: 'api_sectors_get', methods: ['GET'])]
    public function getSectors(): JsonResponse
    {
        $sectors = $this->sectorRepository->findAll();

        $data = [];
        foreach ($sectors as $sector) {
            $data[] = [
                'id' => $sector->getId(),
                'name' => $sector->getName(),
                'icon' => $sector->getIcon(),
                'status' => $sector->getStatus(),
                'description' => $sector->getDescription(),
                'createdAt' => $sector->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/sectors', name: 'api_sectors_create', methods: ['POST'])]
    public function createSector(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $sector = new Sector();
        $sector->setName($data['name'] ?? '');
        $sector->setIcon($data['icon'] ?? '');
        $sector->setStatus($data['status'] ?? '');
        $sector->setDescription($data['description'] ?? '');

        $errors = $this->validator->validate($sector);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->persist($sector);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Sector created successfully',
            'sector' => [
                'id' => $sector->getId(),
                'name' => $sector->getName(),
                'icon' => $sector->getIcon(),
                'status' => $sector->getStatus(),
                'description' => $sector->getDescription(),
            ]
        ], 201);
    }

    #[Route('/sectors/{id}', name: 'api_sectors_update', methods: ['PUT'])]
    public function updateSector(int $id, Request $request): JsonResponse
    {
        $sector = $this->sectorRepository->find($id);

        if (!$sector) {
            return $this->json([
                'success' => false,
                'message' => 'Sector not found'
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) $sector->setName($data['name']);
        if (isset($data['icon'])) $sector->setIcon($data['icon']);
        if (isset($data['status'])) $sector->setStatus($data['status']);
        if (isset($data['description'])) $sector->setDescription($data['description']);

        $sector->setUpdatedAt(new \DateTimeImmutable());

        $errors = $this->validator->validate($sector);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Sector updated successfully',
            'sector' => [
                'id' => $sector->getId(),
                'name' => $sector->getName(),
                'icon' => $sector->getIcon(),
                'status' => $sector->getStatus(),
                'description' => $sector->getDescription(),
            ]
        ]);
    }

    #[Route('/sectors/{id}', name: 'api_sectors_delete', methods: ['DELETE'])]
    public function deleteSector(int $id): JsonResponse
    {
        $sector = $this->sectorRepository->find($id);

        if (!$sector) {
            return $this->json([
                'success' => false,
                'message' => 'Sector not found'
            ], 404);
        }

        $this->entityManager->remove($sector);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Sector deleted successfully'
        ]);
    }

    // FEATURE ENDPOINTS (similar pattern)

    #[Route('/features', name: 'api_features_get', methods: ['GET'])]
    public function getFeatures(): JsonResponse
    {
        $features = $this->featureRepository->findAll();

        $data = [];
        foreach ($features as $feature) {
            $data[] = [
                'id' => $feature->getId(),
                'title' => $feature->getTitle(),
                'icon' => $feature->getIcon(),
                'description' => $feature->getDescription(),
                'createdAt' => $feature->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/features', name: 'api_features_create', methods: ['POST'])]
    public function createFeature(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $feature = new Feature();
        $feature->setTitle($data['title'] ?? '');
        $feature->setIcon($data['icon'] ?? '');
        $feature->setDescription($data['description'] ?? '');

        $errors = $this->validator->validate($feature);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->persist($feature);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Feature created successfully',
            'feature' => [
                'id' => $feature->getId(),
                'title' => $feature->getTitle(),
                'icon' => $feature->getIcon(),
                'description' => $feature->getDescription(),
            ]
        ], 201);
    }

    // TESTIMONIAL ENDPOINTS

    #[Route('/testimonials', name: 'api_testimonials_get', methods: ['GET'])]
    public function getTestimonials(): JsonResponse
    {
        $testimonials = $this->testimonialRepository->findAll();

        $data = [];
        foreach ($testimonials as $testimonial) {
            $data[] = [
                'id' => $testimonial->getId(),
                'text' => $testimonial->getText(),
                'author' => $testimonial->getAuthor(),
                'role' => $testimonial->getRole(),
                'avatar' => $testimonial->getAvatar(),
                'avatarType' => $testimonial->getAvatarType(),
                'createdAt' => $testimonial->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/testimonials', name: 'api_testimonials_create', methods: ['POST'])]
    public function createTestimonial(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $testimonial = new Testimonial();
        $testimonial->setText($data['text'] ?? '');
        $testimonial->setAuthor($data['author'] ?? '');
        $testimonial->setRole($data['role'] ?? '');
        $testimonial->setAvatar($data['avatar'] ?? '');
        $testimonial->setAvatarType($data['avatarType'] ?? '');

        $errors = $this->validator->validate($testimonial);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->persist($testimonial);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Testimonial created successfully',
            'testimonial' => [
                'id' => $testimonial->getId(),
                'text' => $testimonial->getText(),
                'author' => $testimonial->getAuthor(),
                'role' => $testimonial->getRole(),
                'avatar' => $testimonial->getAvatar(),
                'avatarType' => $testimonial->getAvatarType(),
            ]
        ], 201);
    }

    // SLIDE ENDPOINTS

    #[Route('/slideshow', name: 'api_slideshow_get', methods: ['GET'])]
    public function getSlideshow(): JsonResponse
    {
        $slides = $this->slideRepository->findAll();

        $data = [];
        foreach ($slides as $slide) {
            $data[] = [
                'id' => $slide->getId(),
                'imageUrl' => $slide->getImageUrl(),
                'caption' => $slide->getCaption(),
                'createdAt' => $slide->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/slideshow', name: 'api_slideshow_create', methods: ['POST'])]
    public function createSlide(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $slide = new Slide();
        $slide->setImageUrl($data['imageUrl'] ?? '');
        $slide->setCaption($data['caption'] ?? '');

        $errors = $this->validator->validate($slide);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->persist($slide);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Slide created successfully',
            'slide' => [
                'id' => $slide->getId(),
                'imageUrl' => $slide->getImageUrl(),
                'caption' => $slide->getCaption(),
            ]
        ], 201);
    }
}
