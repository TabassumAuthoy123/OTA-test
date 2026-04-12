<?php

namespace Database\Seeders;

use App\Models\CmsPromotion;
use Illuminate\Database\Seeder;

class DealsSeeder extends Seeder
{
    public function run(): void
    {
        $deals = [
            [
                'title' => 'Dhaka → Cox\'s Bazar Summer Special',
                'description' => 'Escape to the longest sea beach in the world! Book your summer getaway with exclusive discounted fares on US-Bangla and Novoair flights.',
                'discount_text' => '20% OFF',
                'badge_color' => '#dc3545',
                'image' => null,
                'link' => null,
                'position' => 1,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
            ],
            [
                'title' => 'Dhaka → Bangkok Early Bird',
                'description' => 'Plan ahead and save! Get amazing fares on Thai Airways and Biman flights to Bangkok. Perfect for your next Thai adventure.',
                'discount_text' => '15% OFF',
                'badge_color' => '#6366f1',
                'image' => null,
                'link' => null,
                'position' => 2,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(45),
            ],
            [
                'title' => 'Dhaka → Dubai Winter Sale',
                'description' => 'Experience the luxury of Dubai at unbeatable prices. Special fares on Emirates and flydubai flights from Dhaka.',
                'discount_text' => 'BDT 5000 OFF',
                'badge_color' => '#f59e0b',
                'image' => null,
                'link' => null,
                'position' => 3,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(60),
            ],
            [
                'title' => 'Biman Bangladesh Exclusive Fares',
                'description' => 'Fly the national carrier at special promotional fares. Available on select domestic and international routes from Dhaka.',
                'discount_text' => 'EXCLUSIVE',
                'badge_color' => '#10b981',
                'image' => null,
                'link' => null,
                'position' => 4,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
            ],
            [
                'title' => 'Last Minute Deals — Grab Before Gone!',
                'description' => 'Spontaneous traveler? Score incredible last-minute deals on flights departing within the next 7 days. Limited seats available!',
                'discount_text' => 'UP TO 30% OFF',
                'badge_color' => '#ef4444',
                'image' => null,
                'link' => null,
                'position' => 5,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(7),
            ],
            [
                'title' => 'Hajj & Umrah Special Packages',
                'description' => 'Fulfill your spiritual journey with our curated Hajj and Umrah flight packages. Group booking discounts available on Saudi Airlines.',
                'discount_text' => 'SPECIAL RATE',
                'badge_color' => '#0ea5e9',
                'image' => null,
                'link' => null,
                'position' => 6,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(90),
            ],
            [
                'title' => 'Student Discount — Fly Smart, Save More',
                'description' => 'Valid student ID holders get exclusive fare reductions on international flights. Available for destinations including UK, USA, Canada, and Australia.',
                'discount_text' => '10% STUDENT',
                'badge_color' => '#8b5cf6',
                'image' => null,
                'link' => null,
                'position' => 7,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(120),
            ],
            [
                'title' => 'Dhaka → Kolkata Weekend Getaway',
                'description' => 'Quick cross-border escape! Enjoy special weekend fares on flights to Kolkata. Perfect for shopping trips and family visits.',
                'discount_text' => 'FROM BDT 6999',
                'badge_color' => '#14b8a6',
                'image' => null,
                'link' => null,
                'position' => 8,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(21),
            ],
        ];

        CmsPromotion::truncate();

        foreach ($deals as $deal) {
            CmsPromotion::create($deal);
        }
    }
}
