<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monument;
use App\Models\User;

class MonumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $monuments = [
            [
                'name' => 'Angkor Wat',
                'description' => 'Angkor Wat is a temple complex in Cambodia and one of the largest religious monuments in the world.',
                'location' => 'Siem Reap, Cambodia',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Great Wall of China',
                'description' => 'The Great Wall of China is a series of fortifications made of stone, brick, tamped earth, wood, and other materials.',
                'location' => 'China',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Taj Mahal',
                'description' => 'The Taj Mahal is an ivory-white marble mausoleum on the right bank of the river Yamuna in the Indian city of Agra.',
                'location' => 'Agra, India',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Machu Picchu',
                'description' => 'Machu Picchu is a 15th-century Inca citadel located in the Eastern Cordillera of southern Peru.',
                'location' => 'Cusco Region, Peru',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Colosseum',
                'description' => 'The Colosseum is an oval amphitheatre in the centre of the city of Rome, Italy.',
                'location' => 'Rome, Italy',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Petra',
                'description' => 'Petra is a famous archaeological site in Jordan\'s southwestern desert.',
                'location' => 'Ma\'an Governorate, Jordan',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Chichen Itza',
                'description' => 'Chichen Itza was a large pre-Columbian city built by the Maya people of the Terminal Classic period.',
                'location' => 'Yucatán, Mexico',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Christ the Redeemer',
                'description' => 'Christ the Redeemer is an Art Deco statue of Jesus Christ in Rio de Janeiro, Brazil.',
                'location' => 'Rio de Janeiro, Brazil',
                'status' => 'published',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($monuments as $monument) {
            Monument::create($monument);
        }
    }
}
