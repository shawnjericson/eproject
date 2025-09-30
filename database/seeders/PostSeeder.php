<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $moderator = User::where('role', 'moderator')->first();

        $posts = [
            [
                'title' => 'Discovering the Wonders of Ancient Architecture',
                'content' => '<p>Ancient architecture continues to amaze us with its grandeur and engineering marvels. From the pyramids of Egypt to the temples of Greece, these structures tell stories of civilizations that have shaped our world.</p><p>The techniques used by ancient builders were often more sophisticated than we initially believed. Many of these monuments have withstood the test of time, surviving natural disasters and human conflicts.</p><p>Today, we can learn valuable lessons from these architectural masterpieces about sustainability, durability, and the importance of preserving our cultural heritage for future generations.</p>',
                'status' => 'approved',
                'published_at' => now()->subDays(5),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'The Cultural Significance of World Heritage Sites',
                'content' => '<p>World Heritage Sites represent the collective memory of humanity. These locations are not just tourist destinations; they are repositories of our shared cultural and natural heritage.</p><p>Each site tells a unique story about the people who built it, the civilization that flourished there, and the historical events that shaped its destiny.</p><p>Preserving these sites is crucial for maintaining cultural diversity and ensuring that future generations can learn from the achievements and mistakes of the past.</p>',
                'status' => 'approved',
                'published_at' => now()->subDays(3),
                'created_by' => $moderator->id,
            ],
            [
                'title' => 'Travel Tips for Visiting Historical Monuments',
                'content' => '<p>Visiting historical monuments can be an enriching experience if you prepare properly. Here are some essential tips for making the most of your cultural travels:</p><ul><li>Research the history before you visit</li><li>Respect local customs and regulations</li><li>Take guided tours when available</li><li>Bring appropriate clothing and gear</li><li>Support local communities</li></ul><p>Remember that these sites are fragile and irreplaceable. Every visitor has a responsibility to help preserve them for future generations.</p>',
                'status' => 'approved',
                'published_at' => now()->subDays(1),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'The Role of Technology in Monument Preservation',
                'content' => '<p>Modern technology is revolutionizing how we preserve and study historical monuments. From 3D scanning to virtual reality, new tools are helping us understand and protect our heritage.</p><p>Digital documentation allows us to create detailed records of monuments, which can be invaluable for restoration work and research.</p><p>Virtual tours and augmented reality experiences are also making these sites more accessible to people who cannot visit them in person.</p>',
                'status' => 'pending',
                'created_by' => $moderator->id,
            ],
            [
                'title' => 'Hidden Gems: Lesser-Known Historical Sites',
                'content' => '<p>While famous monuments like the Taj Mahal and Machu Picchu attract millions of visitors, there are countless lesser-known sites that are equally fascinating.</p><p>These hidden gems often offer more intimate experiences and deeper connections with local culture and history.</p><p>Exploring off-the-beaten-path destinations also helps distribute tourism benefits more evenly and reduces pressure on over-visited sites.</p>',
                'status' => 'draft',
                'created_by' => $moderator->id,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
