<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MANUAL IMPORT OF MONUMENT TRANSLATIONS ===\n";

// Get current monuments
$monuments = DB::table('monuments')->get();
echo "Available monuments:\n";
foreach ($monuments as $monument) {
    echo "ID: {$monument->id}, Name: {$monument->name}\n";
}

// Manual data insertion - only for monuments that exist
$translations = [
    // Angkor Wat (ID: 1)
    [
        'monument_id' => 1,
        'language' => 'vi',
        'title' => 'Angkor Wat – Kỳ quan của nền văn minh Khmer',
        'description' => 'Ẩn mình trong rừng già Campuchia, Angkor Wat là công trình tôn giáo lớn nhất thế giới và biểu tượng vĩnh hằng của Đế chế Khmer.',
        'history' => null,
        'content' => '<h2>Giới thiệu</h2><p>Angkor Wat, tọa lạc tại Siem Reap, Campuchia, là công trình tôn giáo lớn nhất thế giới và biểu tượng quốc gia xuất hiện trên cả quốc kỳ Campuchia.</p>',
        'location' => 'Siem Reap, Campuchia'
    ],
    [
        'monument_id' => 1,
        'language' => 'en',
        'title' => 'Angkor Wat – The Wonder of the Khmer Civilization',
        'description' => 'Nestled in the jungles of Cambodia, Angkor Wat is the largest religious monument in the world and an eternal symbol of the Khmer Empire.',
        'history' => null,
        'content' => '<h2>Introduction</h2><p>Angkor Wat, located in Siem Reap, Cambodia, is the largest religious monument in the world and a national symbol that even appears on the Cambodian flag.</p>',
        'location' => 'Siem Reap, Cambodia'
    ],
    
    // Great Wall of China (ID: 2)
    [
        'monument_id' => 2,
        'language' => 'vi',
        'title' => 'Vạn Lý Trường Thành – Bức tường vĩ đại của Trung Quốc',
        'description' => 'Trải dài hàng nghìn km qua các tỉnh miền Bắc Trung Quốc, Vạn Lý Trường Thành là công trình phòng thủ vĩ đại nhất trong lịch sử nhân loại.',
        'history' => null,
        'content' => '<h2>Giới thiệu</h2><p>Vạn Lý Trường Thành là một trong những kỳ quan kiến trúc vĩ đại nhất của nhân loại, được UNESCO công nhận là Di sản Thế giới.</p>',
        'location' => 'Trung Quốc'
    ],
    [
        'monument_id' => 2,
        'language' => 'en',
        'title' => 'Great Wall of China – The Mighty Fortress',
        'description' => 'Stretching thousands of kilometers across northern China, the Great Wall stands as the most magnificent defensive structure in human history.',
        'history' => null,
        'content' => '<h2>Introduction</h2><p>The Great Wall of China is one of the most magnificent architectural wonders of humanity, recognized as a UNESCO World Heritage Site.</p>',
        'location' => 'China'
    ],
    
    // Taj Mahal (ID: 4) - mapping to ID 3
    [
        'monument_id' => 3,
        'language' => 'vi',
        'title' => 'Taj Mahal – Viên ngọc trắng của Ấn Độ',
        'description' => 'Tọa lạc tại Agra, Ấn Độ, Taj Mahal là lăng mộ bằng cẩm thạch trắng được Hoàng đế Shah Jahan xây dựng để tưởng nhớ người vợ Mumtaz Mahal.',
        'history' => null,
        'content' => '<h2>Giới thiệu</h2><p>Taj Mahal, tọa lạc tại thành phố Agra, Ấn Độ, là một trong những công trình kiến trúc nổi tiếng nhất thế giới.</p>',
        'location' => 'Agra, Ấn Độ'
    ],
    [
        'monument_id' => 3,
        'language' => 'en',
        'title' => 'Taj Mahal – The White Jewel of India',
        'description' => 'Located in Agra, India, the Taj Mahal is a white marble mausoleum built by Emperor Shah Jahan in memory of his wife Mumtaz Mahal.',
        'history' => null,
        'content' => '<h2>Introduction</h2><p>The Taj Mahal, located in the city of Agra, India, is one of the most famous architectural landmarks in the world.</p>',
        'location' => 'Agra, India'
    ],
    
    // Colosseum (ID: 5) - mapping to ID 4
    [
        'monument_id' => 4,
        'language' => 'vi',
        'title' => 'Colosseum – Đấu trường huyền thoại của La Mã',
        'description' => 'Nằm ở trung tâm Rome, Colosseum là đấu trường vĩ đại nhất của La Mã cổ đại, nơi từng diễn ra những trận đấu gladiator và sự kiện hoành tráng.',
        'history' => null,
        'content' => '<h2>Giới thiệu</h2><p>Colosseum, hay còn gọi là Đấu trường La Mã, nằm ở trung tâm thành phố Rome (Ý), là một trong những công trình kiến trúc nổi tiếng nhất của Đế chế La Mã cổ đại.</p>',
        'location' => 'Rome, Ý'
    ],
    [
        'monument_id' => 4,
        'language' => 'en',
        'title' => 'Colosseum – The Legendary Arena of Rome',
        'description' => 'In the heart of Rome, the Colosseum stands as the greatest amphitheater of the ancient world, where gladiators once fought and grand spectacles captivated tens of thousands.',
        'history' => null,
        'content' => '<h2>Introduction</h2><p>The Colosseum, also known as the Flavian Amphitheatre, stands at the heart of Rome, Italy.</p>',
        'location' => 'Rome, Italy'
    ],
    
    // Machu Picchu (ID: 6) - mapping to ID 5
    [
        'monument_id' => 5,
        'language' => 'vi',
        'title' => 'Machu Picchu – Thành phố đã mất của người Inca',
        'description' => 'Ẩn mình trên dãy Andes hùng vĩ, Machu Picchu là "Thành phố đã mất của người Inca" với vẻ đẹp bí ẩn, kiến trúc tinh xảo và giá trị tâm linh sâu sắc.',
        'history' => null,
        'content' => '<h2>Giới thiệu</h2><p>Machu Picchu, nằm cheo leo trên dãy Andes ở Peru, là một trong những di tích khảo cổ nổi tiếng nhất thế giới.</p>',
        'location' => 'dãy Andes, Peru'
    ],
    [
        'monument_id' => 5,
        'language' => 'en',
        'title' => 'Machu Picchu – The Lost City of the Incas',
        'description' => 'Perched high in the Andes, Machu Picchu is the legendary "Lost City of the Incas" with breathtaking mountain setting, masterful stonework, and deep spiritual significance.',
        'history' => null,
        'content' => '<h2>Introduction</h2><p>Machu Picchu, nestled high in the Andes Mountains of Peru, is one of the most iconic archaeological sites in the world.</p>',
        'location' => 'Andes Mountains, Peru'
    ],
];

$successCount = 0;
$errorCount = 0;

foreach ($translations as $translation) {
    try {
        DB::table('monument_translations')->insert([
            'monument_id' => $translation['monument_id'],
            'language' => $translation['language'],
            'title' => $translation['title'],
            'description' => $translation['description'],
            'history' => $translation['history'],
            'content' => $translation['content'],
            'location' => $translation['location'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $successCount++;
        echo "✓ Inserted: {$translation['title']} ({$translation['language']})\n";
    } catch (Exception $e) {
        $errorCount++;
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== IMPORT COMPLETE ===\n";
echo "Success: $successCount records\n";
echo "Errors: $errorCount records\n";

// Check final count
$finalCount = DB::table('monument_translations')->count();
echo "Total monument_translations records: $finalCount\n";


