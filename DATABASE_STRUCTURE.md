# Database Structure - Multilingual Support

## Overview

Hệ thống sử dụng **Translation Tables** để hỗ trợ đa ngôn ngữ (tiếng Anh và tiếng Việt).

## Architecture

### Main Tables
- `monuments` - Lưu thông tin cơ bản của di tích
- `posts` - Lưu thông tin cơ bản của bài viết
- `users` - Người dùng
- `feedbacks` - Phản hồi
- `gallery` - Thư viện ảnh
- `site_settings` - Cài đặt hệ thống

### Translation Tables
- `monument_translations` - Nội dung đa ngôn ngữ của monuments
- `post_translations` - Nội dung đa ngôn ngữ của posts

## Monuments Structure

### Table: `monuments`
Lưu thông tin cơ bản và fallback content:

```sql
CREATE TABLE monuments (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255),           -- Fallback title (usually English)
    description TEXT,             -- Fallback description
    history LONGTEXT,             -- Fallback history
    content LONGTEXT,             -- Fallback content
    location VARCHAR(255),        -- Fallback location
    map_embed TEXT,               -- Google Maps embed code
    zone ENUM('East', 'North', 'West', 'South', 'Central'),
    image VARCHAR(255),           -- Main image
    created_by BIGINT,            -- Foreign key to users
    status ENUM('draft', 'pending', 'approved'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Table: `monument_translations`
Lưu nội dung theo từng ngôn ngữ:

```sql
CREATE TABLE monument_translations (
    id BIGINT PRIMARY KEY,
    monument_id BIGINT,           -- Foreign key to monuments
    language ENUM('en', 'vi'),    -- Language code
    title VARCHAR(255),           -- Translated title
    description TEXT,             -- Translated description
    history LONGTEXT,             -- Translated history
    content LONGTEXT,             -- Translated content
    location VARCHAR(255),        -- Translated location
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (monument_id, language)  -- One translation per language
);
```

## Posts Structure

### Table: `posts`
Lưu thông tin cơ bản và fallback content:

```sql
CREATE TABLE posts (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255),           -- Fallback title (usually English)
    content LONGTEXT,             -- Fallback content
    image VARCHAR(255),           -- Featured image
    created_by BIGINT,            -- Foreign key to users
    status ENUM('draft', 'pending', 'approved'),
    published_at TIMESTAMP,       -- Publication date
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Table: `post_translations`
Lưu nội dung theo từng ngôn ngữ:

```sql
CREATE TABLE post_translations (
    id BIGINT PRIMARY KEY,
    post_id BIGINT,               -- Foreign key to posts
    language ENUM('en', 'vi'),    -- Language code
    title VARCHAR(255),           -- Translated title
    description TEXT,             -- Translated description
    content LONGTEXT,             -- Translated content
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (post_id, language)  -- One translation per language
);
```

## How It Works

### 1. Data Storage

**When creating a Monument:**
```php
// Create main monument record
$monument = Monument::create([
    'title' => 'Angkor Wat',  // English fallback
    'description' => 'Ancient temple complex...',
    'zone' => 'East',
    'status' => 'approved',
]);

// Create English translation
$monument->translations()->create([
    'language' => 'en',
    'title' => 'Angkor Wat',
    'description' => 'Ancient temple complex in Cambodia...',
    'history' => 'Built in the 12th century...',
]);

// Create Vietnamese translation
$monument->translations()->create([
    'language' => 'vi',
    'title' => 'Angkor Wat',
    'description' => 'Quần thể đền cổ ở Campuchia...',
    'history' => 'Được xây dựng vào thế kỷ 12...',
]);
```

### 2. Data Retrieval

**Get monument with translations:**
```php
$monument = Monument::with('translations')->find(1);

// Get English content
$enTitle = $monument->getTitle('en');
$enDescription = $monument->getDescription('en');

// Get Vietnamese content
$viTitle = $monument->getTitle('vi');
$viDescription = $monument->getDescription('vi');

// Fallback to main table if translation not found
$title = $monument->getTitle('fr');  // Returns $monument->title if French not available
```

### 3. Search Implementation

**Search in both main table and translations:**
```php
$monuments = Monument::with('translations')
    ->where(function($q) use ($searchTerm) {
        // Search in main table (fallback content)
        $q->where('title', 'like', $searchTerm)
          ->orWhere('description', 'like', $searchTerm)
          ->orWhere('history', 'like', $searchTerm)
          
          // Search in translations table (all languages)
          ->orWhereHas('translations', function($tq) use ($searchTerm) {
              $tq->where('title', 'like', $searchTerm)
                 ->orWhere('description', 'like', $searchTerm)
                 ->orWhere('history', 'like', $searchTerm);
          });
    })
    ->get();
```

**Why this works:**
- Searches in main table (English fallback)
- Searches in translations table (all languages including Vietnamese)
- Returns monument if match found in ANY language
- User can search in English or Vietnamese and get results

## Benefits

### 1. Scalability
- Easy to add new languages (just add new translation records)
- No need to modify table structure
- No need to add new columns

### 2. Performance
- Main table stays small (only essential fields)
- Translations loaded only when needed (eager loading)
- Can index translation fields separately

### 3. Flexibility
- Can have different content for different languages
- Can have partial translations (some fields translated, others not)
- Fallback to main table if translation missing

### 4. Search
- Search works across all languages
- No need to know which language user is searching in
- Returns relevant results regardless of language

## Model Methods

### Monument Model

```php
// Get all translations
$monument->translations;

// Get specific language translation
$monument->translation('vi');

// Get title in specific language (with fallback)
$monument->getTitle('vi');

// Get description in specific language (with fallback)
$monument->getDescription('vi');

// Get history in specific language (with fallback)
$monument->getHistory('vi');
```

### Post Model

```php
// Get all translations
$post->translations;

// Get specific language translation
$post->translation('vi');

// Get title in specific language (with fallback)
$post->getTitle('vi');

// Get description in specific language (with fallback)
$post->getDescription('vi');

// Get content in specific language (with fallback)
$post->getContent('vi');
```

## Migration History

### Original Structure (Deprecated)
```php
// Old: Multilingual columns in main table
Schema::table('monuments', function (Blueprint $table) {
    $table->string('title');
    $table->string('title_vi');
    $table->text('description');
    $table->text('description_vi');
    // ... more _vi columns
});
```

**Problems:**
- Table becomes wide with many columns
- Hard to add new languages
- Difficult to maintain
- Search queries become complex

### New Structure (Current)
```php
// New: Separate translation table
Schema::create('monument_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('monument_id')->constrained()->onDelete('cascade');
    $table->enum('language', ['en', 'vi']);
    $table->string('title');
    $table->text('description');
    // ... other translatable fields
    $table->unique(['monument_id', 'language']);
});
```

**Benefits:**
- Clean main table
- Easy to add languages
- Better performance
- Easier to maintain

## Search Query Examples

### Example 1: Search for "temple"
```sql
SELECT * FROM monuments
WHERE title LIKE '%temple%'
   OR description LIKE '%temple%'
   OR EXISTS (
       SELECT 1 FROM monument_translations
       WHERE monument_translations.monument_id = monuments.id
         AND (title LIKE '%temple%' OR description LIKE '%temple%')
   );
```

### Example 2: Search for "đền" (Vietnamese)
```sql
SELECT * FROM monuments
WHERE title LIKE '%đền%'
   OR description LIKE '%đền%'
   OR EXISTS (
       SELECT 1 FROM monument_translations
       WHERE monument_translations.monument_id = monuments.id
         AND (title LIKE '%đền%' OR description LIKE '%đền%')
   );
```

Both queries work the same way - search in main table and translations table.

## Best Practices

### 1. Always Eager Load Translations
```php
// Good
Monument::with('translations')->get();

// Bad (N+1 query problem)
Monument::all(); // Then accessing $monument->translations in loop
```

### 2. Use Helper Methods
```php
// Good
$title = $monument->getTitle($language);

// Bad
$translation = $monument->translations()->where('language', $language)->first();
$title = $translation ? $translation->title : $monument->title;
```

### 3. Search in Both Tables
```php
// Good - searches everywhere
$query->where(function($q) use ($searchTerm) {
    $q->where('title', 'like', $searchTerm)
      ->orWhereHas('translations', function($tq) use ($searchTerm) {
          $tq->where('title', 'like', $searchTerm);
      });
});

// Bad - only searches main table
$query->where('title', 'like', $searchTerm);
```

## Conclusion

The translation table approach provides:
- ✅ Clean database structure
- ✅ Easy to maintain
- ✅ Scalable for multiple languages
- ✅ Efficient search across all languages
- ✅ Better performance
- ✅ Flexible content management

This is why the search now uses `orWhereHas('translations')` to search in both the main table and the translations table.

