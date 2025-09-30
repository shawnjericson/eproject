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
                'title' => 'Angkor Wat',
                'description' => 'Angkor Wat is a temple complex in Cambodia and one of the largest religious monuments in the world.',
                'history' => 'Originally constructed as a Hindu temple dedicated to the god Vishnu for the Khmer Empire, it was gradually transformed into a Buddhist temple towards the end of the 12th century.',
                'zone' => 'East',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Great Wall of China',
                'description' => 'The Great Wall of China is a series of fortifications made of stone, brick, tamped earth, wood, and other materials.',
                'history' => 'Built across the historical northern borders of ancient Chinese states and Imperial China as protection against various nomadic groups from the Eurasian Steppe.',
                'zone' => 'East',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Taj Mahal',
                'description' => 'The Taj Mahal is an ivory-white marble mausoleum on the right bank of the river Yamuna in the Indian city of Agra.',
                'history' => 'It was commissioned in 1632 by the Mughal emperor Shah Jahan to house the tomb of his favourite wife, Mumtaz Mahal.',
                'zone' => 'South',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Machu Picchu',
                'description' => 'Machu Picchu is a 15th-century Inca citadel located in the Eastern Cordillera of southern Peru.',
                'history' => 'Built in the 15th century and later abandoned, it is renowned for its sophisticated dry-stone walls that fuse huge blocks without the use of mortar.',
                'zone' => 'West',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Colosseum',
                'description' => 'The Colosseum is an oval amphitheatre in the centre of the city of Rome, Italy.',
                'history' => 'Built of travertine limestone, tuff, and brick-faced concrete, it was the largest amphitheatre ever built.',
                'zone' => 'North',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Petra',
                'description' => 'Petra is a famous archaeological site in Jordan\'s southwestern desert.',
                'history' => 'Dating to around 300 B.C., it was the capital of the Nabataean Kingdom. Accessed via a narrow canyon called Al Siq.',
                'zone' => 'North',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Chichen Itza',
                'description' => 'Chichen Itza was a large pre-Columbian city built by the Maya people of the Terminal Classic period.',
                'history' => 'The archaeological site is located in Tinúm Municipality, Yucatán State, Mexico.',
                'zone' => 'West',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Christ the Redeemer',
                'description' => 'Christ the Redeemer is an Art Deco statue of Jesus Christ in Rio de Janeiro, Brazil.',
                'history' => 'Created by French sculptor Paul Landowski and built between 1922 and 1931, the statue is 30 metres tall.',
                'zone' => 'South',
                'status' => 'approved',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($monuments as $monument) {
            Monument::create($monument);
        }
    }
}
