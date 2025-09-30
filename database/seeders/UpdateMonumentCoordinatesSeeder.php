<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monument;

class UpdateMonumentCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monumentsData = [
            [
                'id' => 52,
                'title' => 'Angkor Wat',
                'latitude' => 13.412469,
                'longitude' => 103.866986,
                'is_world_wonder' => true,
            ],
            [
                'id' => 53,
                'title' => 'Great Wall of China',
                'latitude' => 40.431908,
                'longitude' => 116.570375,
                'is_world_wonder' => true,
            ],
        ];

        foreach ($monumentsData as $data) {
            $monument = Monument::find($data['id']);
            
            if ($monument) {
                $monument->update([
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'is_world_wonder' => $data['is_world_wonder'],
                ]);
                
                $this->command->info("✅ Updated: {$monument->title} (Lat: {$data['latitude']}, Lng: {$data['longitude']})");
            } else {
                $this->command->warn("⚠️  Monument ID {$data['id']} not found");
            }
        }

        $totalWithCoordinates = Monument::whereNotNull('latitude')->count();
        $this->command->info("\n📊 Total monuments with coordinates: {$totalWithCoordinates}");
    }
}

