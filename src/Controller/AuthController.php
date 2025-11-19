<?php

namespace App\Controller;


use App\Dto\LoginRequest;
use App\Dto\SignupRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('/signup', name: 'api_auth_signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        try {
            /** @var SignupRequest $signupRequest */
            $signupRequest = $this->serializer->deserialize(
                $request->getContent(),
                SignupRequest::class,
                'json'
            );

            $errors = $this->validator->validate($signupRequest);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return $this->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errorMessages
                ], Response::HTTP_BAD_REQUEST);
            }

            // Check if user already exists
            $existingUser = $this->userRepository->findByEmail($signupRequest->email);
            if ($existingUser) {
                return $this->json([
                    'success' => false,
                    'message' => 'An account with this email already exists.'
                ], Response::HTTP_CONFLICT);
            }

            // Create new user
            $user = new User();
            $user->setFullName($signupRequest->fullName);
            $user->setEmail($signupRequest->email);
            $user->setUserType($signupRequest->userType);
            $user->setModule($signupRequest->module);

            // Hash password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $signupRequest->password);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Account created successfully! You can now login.',
                'user' => [
                    'id' => $user->getId(),
                    'fullName' => $user->getFullName(),
                    'email' => $user->getEmail(),
                    'userType' => $user->getUserType(),
                    'module' => $user->getModule()
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

//    #[Route('/login', name: 'api_auth_login', methods: ['POST'])]
//    public function login(Request $request): JsonResponse
//    {
//        try {
//            /** @var LoginRequest $loginRequest */
//            $loginRequest = $this->serializer->deserialize(
//                $request->getContent(),
//                LoginRequest::class,
//                'json'
//            );
//
//            $errors = $this->validator->validate($loginRequest);
//            if (count($errors) > 0) {
//                $errorMessages = [];
//                foreach ($errors as $error) {
//                    $errorMessages[] = $error->getMessage();
//                }
//                return $this->json([
//                    'success' => false,
//                    'message' => 'Validation failed',
//                    'errors' => $errorMessages
//                ], Response::HTTP_BAD_REQUEST);
//            }
//
//            // Manually find and verify the user
//            $user = $this->userRepository->findByEmail($loginRequest->email);
//
//            if (!$user || !$this->passwordHasher->isPasswordValid($user, $loginRequest->password)) {
//                return $this->json([
//                    'success' => false,
//                    'message' => 'Invalid email or password.'
//                ], Response::HTTP_UNAUTHORIZED);
//            }
//
//            return $this->json([
//                'success' => true,
//                'message' => 'Login successful!',
//                'user' => [
//                    'id' => $user->getId(),
//                    'fullName' => $user->getFullName(),
//                    'email' => $user->getEmail(),
//                    'userType' => $user->getUserType(),
//                    'module' => $user->getModule()
//                ]
//            ]);
//
//        } catch (\Exception $e) {
//            return $this->json([
//                'success' => false,
//                'message' => 'An error occurred during login.',
//                'error' => $e->getMessage()
//            ], Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }

    #[Route('/me', name: 'api_auth_me', methods: ['GET'])]
    public function me(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'fullName' => $user->getFullName(),
                'email' => $user->getEmail(),
                'userType' => $user->getUserType(),
                'module' => $user->getModule()
            ]
        ]);
    }
}
