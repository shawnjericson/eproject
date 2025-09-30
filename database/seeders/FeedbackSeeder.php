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
                'message' => 'Amazing experience visiting this monument! The history and architecture are breathtaking. Highly recommend to anyone interested in cultural heritage.',
                'monument_id' => $monuments->where('title', 'Angkor Wat')->first()?->id,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'message' => 'The Great Wall is truly one of the wonders of the world. The engineering feat is incredible and the views are spectacular.',
                'monument_id' => $monuments->where('title', 'Great Wall of China')->first()?->id,
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily@example.com',
                'message' => 'Visiting the Taj Mahal was a dream come true. The beauty and craftsmanship are beyond words. A must-see destination!',
                'monument_id' => $monuments->where('title', 'Taj Mahal')->first()?->id,
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david@example.com',
                'message' => 'Machu Picchu is absolutely stunning. The trek was challenging but worth every step. The ancient Inca civilization was remarkable.',
                'monument_id' => $monuments->where('title', 'Machu Picchu')->first()?->id,
            ],
            [
                'name' => 'Lisa Wang',
                'email' => 'lisa@example.com',
                'message' => 'The Colosseum brings history to life. Standing where gladiators once fought is an incredible experience.',
                'monument_id' => $monuments->where('title', 'Colosseum')->first()?->id,
            ],
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@example.com',
                'message' => 'Petra is a hidden gem in the desert. The rose-red city carved into rock is absolutely magnificent.',
                'monument_id' => $monuments->where('title', 'Petra')->first()?->id,
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria@example.com',
                'message' => 'Chichen Itza showcases the incredible knowledge of the Maya civilization. The pyramid is an architectural marvel.',
                'monument_id' => $monuments->where('title', 'Chichen Itza')->first()?->id,
            ],
            [
                'name' => 'Robert Brown',
                'email' => 'robert@example.com',
                'message' => 'Christ the Redeemer offers amazing views of Rio. The statue is an iconic symbol of Brazil.',
                'monument_id' => $monuments->where('title', 'Christ the Redeemer')->first()?->id,
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer@example.com',
                'message' => 'Thank you for this wonderful website! It helped me plan my trip and learn about the history of these amazing places.',
                'monument_id' => null, // General feedback
            ],
            [
                'name' => 'Carlos Silva',
                'email' => 'carlos@example.com',
                'message' => 'Great resource for travelers interested in history and culture. Keep up the excellent work!',
                'monument_id' => null, // General feedback
            ],
        ];

        foreach ($feedbacks as $feedback) {
            Feedback::create($feedback);
        }
    }
}
