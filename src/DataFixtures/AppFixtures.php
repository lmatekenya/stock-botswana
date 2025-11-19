<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Sector;
use App\Entity\Feature;
use App\Entity\Testimonial;
use App\Entity\Slide;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Add sectors
        $sectors = [
            [
                'name' => 'Local Farming',
                'icon' => 'fas fa-seedling',
                'status' => 'live',
                'description' => 'Buy fresh produce directly from local farmers. Vegetables, fruits, grains, and more.'
            ],
            [
                'name' => 'Building & Housing',
                'icon' => 'fas fa-home',
                'status' => 'live',
                'description' => 'Find construction materials, home decor, furniture, and property listings.'
            ],
            [
                'name' => 'Healthcare',
                'icon' => 'fas fa-heartbeat',
                'status' => 'coming',
                'description' => 'Book online consultations, find medical supplies, and connect with healthcare providers.'
            ],
            [
                'name' => 'Car Sales',
                'icon' => 'fas fa-car',
                'status' => 'coming',
                'description' => 'Buy and sell vehicles with verified sellers and transparent pricing.'
            ],
            [
                'name' => 'Food Services',
                'icon' => 'fas fa-utensils',
                'status' => 'coming',
                'description' => 'Order from local restaurants, caterers, and food vendors across Botswana.'
            ]
        ];

        foreach ($sectors as $sectorData) {
            $sector = new Sector();
            $sector->setName($sectorData['name']);
            $sector->setIcon($sectorData['icon']);
            $sector->setStatus($sectorData['status']);
            $sector->setDescription($sectorData['description']);
            $manager->persist($sector);
        }

        // Add features
        $features = [
            [
                'title' => 'Wide Audience Reach',
                'icon' => 'fas fa-users',
                'description' => 'Connect with customers and suppliers across Botswana without geographical limitations.'
            ],
            [
                'title' => 'Better Value',
                'icon' => 'fas fa-money-bill-wave',
                'description' => 'Eliminate middlemen and get better prices whether you\'re buying or selling.'
            ],
            [
                'title' => 'Easy To Use',
                'icon' => 'fas fa-mobile-alt',
                'description' => 'Our platform is designed for simplicity with mobile-first approach for all users.'
            ],
            [
                'title' => 'Secure Transactions',
                'icon' => 'fas fa-lock',
                'description' => 'Integrated mobile money payments with Orange Money and MyZaka for safe transactions.'
            ],
            [
                'title' => 'Logistics Support',
                'icon' => 'fas fa-truck',
                'description' => 'We help coordinate delivery and logistics for physical products across sectors.'
            ],
            [
                'title' => 'Dedicated Support',
                'icon' => 'fas fa-headset',
                'description' => 'Our team provides assistance to both buyers and sellers for seamless transactions.'
            ]
        ];

        foreach ($features as $featureData) {
            $feature = new Feature();
            $feature->setTitle($featureData['title']);
            $feature->setIcon($featureData['icon']);
            $feature->setDescription($featureData['description']);
            $manager->persist($feature);
        }

        // Add testimonials
        $testimonials = [
            [
                'text' => "Since joining The Pula Market, I've doubled my customer base and increased my profits by 40%. Now I can focus on farming while the platform handles connecting me with buyers.",
                'author' => 'Kabelo Moeng',
                'role' => 'Vegetable Farmer, Gaborone',
                'avatar' => 'KM',
                'avatarType' => 'farmer'
            ],
            [
                'text' => "As a construction materials supplier, I'm excited about the upcoming Housing sector. The Pula Market will help me reach builders across Botswana who need quality materials.",
                'author' => 'Tshepo Mothibi',
                'role' => 'Building Materials Supplier, Francistown',
                'avatar' => 'TM',
                'avatarType' => 'builder'
            ],
            [
                'text' => "The healthcare sector can't come soon enough! This platform will revolutionize how patients access medical services in remote areas of Botswana.",
                'author' => 'Dr. Amantle Dintwa',
                'role' => 'Medical Practitioner, Maun',
                'avatar' => 'DM',
                'avatarType' => 'health'
            ]
        ];

        foreach ($testimonials as $testimonialData) {
            $testimonial = new Testimonial();
            $testimonial->setText($testimonialData['text']);
            $testimonial->setAuthor($testimonialData['author']);
            $testimonial->setRole($testimonialData['role']);
            $testimonial->setAvatar($testimonialData['avatar']);
            $testimonial->setAvatarType($testimonialData['avatarType']);
            $manager->persist($testimonial);
        }

        // Add slides
        $slides = [
            [
                'imageUrl' => 'https://images.unsplash.com/photo-1625246335526-8715df9c4f6d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Farming - Fresh produce from local farmers'
            ],
            [
                'imageUrl' => 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Healthcare - Quality medical services'
            ],
            [
                'imageUrl' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Cars - Find your perfect vehicle'
            ],
            [
                'imageUrl' => 'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Housing - Discover your dream home'
            ],
            [
                'imageUrl' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Food Services - Delicious cuisine from local restaurants'
            ]
        ];

        foreach ($slides as $slideData) {
            $slide = new Slide();
            $slide->setImageUrl($slideData['imageUrl']);
            $slide->setCaption($slideData['caption']);
            $manager->persist($slide);
        }

        $manager->flush();
    }
}
