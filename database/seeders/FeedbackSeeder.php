<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\Monument;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monuments = Monument::all();

        $feedbacks = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'subject' => 'Amazing Monument Experience',
                'message' => 'Amazing experience visiting this monument! The history and architecture are breathtaking. Highly recommend to anyone interested in cultural heritage.',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'subject' => 'Great Wall Visit',
                'message' => 'The Great Wall is truly one of the wonders of the world. The engineering feat is incredible and the views are spectacular.',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily@example.com',
                'subject' => 'Taj Mahal Experience',
                'message' => 'Visiting the Taj Mahal was a dream come true. The beauty and craftsmanship are beyond words. A must-see destination!',
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david@example.com',
                'subject' => 'Machu Picchu Adventure',
                'message' => 'Machu Picchu is absolutely stunning. The trek was challenging but worth every step. The ancient Inca civilization was remarkable.',
            ],
            [
                'name' => 'Lisa Wang',
                'email' => 'lisa@example.com',
                'subject' => 'Colosseum History',
                'message' => 'The Colosseum brings history to life. Standing where gladiators once fought is an incredible experience.',
            ],
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@example.com',
                'subject' => 'Petra Visit',
                'message' => 'Petra is a hidden gem in the desert. The rose-red city carved into rock is absolutely magnificent.',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria@example.com',
                'subject' => 'Chichen Itza Marvel',
                'message' => 'Chichen Itza showcases the incredible knowledge of the Maya civilization. The pyramid is an architectural marvel.',
            ],
            [
                'name' => 'Robert Brown',
                'email' => 'robert@example.com',
                'subject' => 'Christ the Redeemer',
                'message' => 'Christ the Redeemer offers amazing views of Rio. The statue is an iconic symbol of Brazil.',
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer@example.com',
                'subject' => 'Website Feedback',
                'message' => 'Thank you for this wonderful website! It helped me plan my trip and learn about the history of these amazing places.',
            ],
            [
                'name' => 'Carlos Silva',
                'email' => 'carlos@example.com',
                'subject' => 'Great Resource',
                'message' => 'Great resource for travelers interested in history and culture. Keep up the excellent work!',
            ],
        ];

        foreach ($feedbacks as $feedback) {
            Feedback::create($feedback);
        }
    }
}
