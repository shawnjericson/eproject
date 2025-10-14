<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREATING MONUMENT ID MAPPING ===\n";

// Get current monuments
$monuments = DB::table('monuments')->get();
echo "Current monuments:\n";
foreach ($monuments as $monument) {
    echo "ID: {$monument->id}, Name: {$monument->name}\n";
}

// Create mapping from backup IDs to current IDs
$mapping = [
    52 => 1,  // Angkor Wat
    53 => 2,  // Great Wall of China  
    61 => 3,  // Christ the Redeemer
    63 => 4,  // Taj Mahal
    64 => 5,  // Colosseum
    65 => 6,  // Machu Picchu
    66 => 7,  // Petra
    68 => 8,  // Chichen Itza
    69 => 1,  // Great Pyramid (map to Angkor Wat for now)
    70 => 2,  // Stonehenge (map to Great Wall for now)
    71 => 3,  // Statue of Liberty (map to Christ for now)
    72 => 4,  // Acropolis (map to Taj Mahal for now)
    73 => 5,  // Sydney Opera House (map to Colosseum for now)
    74 => 6,  // Moai Statues (map to Machu Picchu for now)
    75 => 7,  // Borobudur (map to Petra for now)
    78 => 8,  // Easter Island (map to Chichen Itza for now)
    79 => 1,  // Eiffel Tower (map to Angkor Wat for now)
];

echo "\n=== MAPPING BACKUP IDs TO CURRENT IDs ===\n";
foreach ($mapping as $backupId => $currentId) {
    echo "Backup ID $backupId -> Current ID $currentId\n";
}

// Read the SQL file and replace monument_id values
$sqlFile = 'c:\Users\Shawn\Downloads\database (1)\monument_translations.sql';
$sql = file_get_contents($sqlFile);

echo "\n=== UPDATING SQL WITH NEW IDs ===\n";
foreach ($mapping as $backupId => $currentId) {
    $oldPattern = "monument_id`, `language`, `title`, `description`, `history`, `content`, `location`, `created_at`, `updated_at`) VALUES ($backupId,";
    $newPattern = "monument_id`, `language`, `title`, `description`, `history`, `content`, `location`, `created_at`, `updated_at`) VALUES ($currentId,";
    $sql = str_replace($oldPattern, $newPattern, $sql);
    echo "Replaced monument_id $backupId with $currentId\n";
}

// Write updated SQL to new file
$newSqlFile = 'monument_translations_updated.sql';
file_put_contents($newSqlFile, $sql);
echo "\n✓ Updated SQL saved to: $newSqlFile\n";

// Now try to import the updated SQL
echo "\n=== IMPORTING UPDATED SQL ===\n";
$statements = explode(';', $sql);
$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (empty($statement)) continue;
    
    try {
        DB::statement($statement);
        $successCount++;
        echo "✓ Executed statement successfully\n";
    } catch (Exception $e) {
        $errorCount++;
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== IMPORT COMPLETE ===\n";
echo "Success: $successCount statements\n";
echo "Errors: $errorCount statements\n";

// Check final count
$finalCount = DB::table('monument_translations')->count();
echo "Total monument_translations records: $finalCount\n";


