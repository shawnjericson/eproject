<?php

/**
 * Script to replace hardcoded English text with translation keys
 * Run: php replace_hardcode_text.php
 */

$replacements = [
    // Dashboard
    "Here's what's happening with Global Heritage today." => "{{ __('admin.dashboard_subtitle') }}",
    'No posts yet.' => "{{ __('admin.no_posts_yet') }}",
    'No monuments yet.' => "{{ __('admin.no_monuments_yet') }}",
    'No feedbacks yet.' => "{{ __('admin.no_feedbacks_yet') }}",
    'Create your first post!' => "{{ __('admin.create_first_post') }}",
    'Total Posts' => "{{ __('admin.total_posts') }}",
    'Total Monuments' => "{{ __('admin.total_monuments') }}",
    'Total Users' => "{{ __('admin.total_users') }}",
    'Total Feedbacks' => "{{ __('admin.total_feedbacks') }}",
    
    // Feedbacks
    'Manage user feedback and suggestions' => "{{ __('admin.manage_user_feedback') }}",
    'No feedbacks found' => "{{ __('admin.no_feedbacks_found') }}",
    'Feedbacks from visitors will appear here.' => "{{ __('admin.feedbacks_will_appear_here') }}",
    "Are you sure you want to delete this feedback? This action cannot be undone." => "{{ __('admin.confirm_delete_feedback') }}",
    
    // Gallery
    'Manage gallery images and media' => "{{ __('admin.manage_gallery_images_media') }}",
    'No gallery images found' => "{{ __('admin.no_gallery_images_found') }}",
    "Are you sure you want to delete this image? This action cannot be undone." => "{{ __('admin.confirm_delete_image') }}",
    'Optional description for the image' => "{{ __('admin.optional_description') }}",
    'Choose descriptive titles' => "{{ __('admin.choose_descriptive_titles') }}",
    'Add relevant descriptions' => "{{ __('admin.add_relevant_descriptions') }}",
    'Recommended: High resolution' => "{{ __('admin.recommended_high_resolution') }}",
    'Monument cannot be changed after creation.' => "{{ __('admin.monument_cannot_be_changed') }}",
    'Current image' => "{{ __('admin.current_image') }}",
    'Leave empty to keep current image.' => "{{ __('admin.leave_empty_keep_current') }}",
    'See full details' => "{{ __('admin.see_full_details') }}",
    'Permanently remove' => "{{ __('admin.permanently_remove') }}",
    'New image preview' => "{{ __('admin.new_image_preview') }}",
    
    // Monuments
    'Paste Google Maps embed iframe code here...' => "{{ __('admin.paste_google_maps_embed') }}",
    'Search for your location' => "{{ __('admin.search_for_location') }}",
    'Search for your monument location' => "{{ __('admin.search_monument_location') }}",
    'Step 1: Create monument in your primary language' => "{{ __('admin.step_1_create_monument') }}",
    'Step 3: Each language has separate content' => "{{ __('admin.step_3_separate_content') }}",
    'Benefit: Clean database structure and easy management' => "{{ __('admin.benefit_clean_structure') }}",
    'This will be used in monument previews, search results, and social media shares. Keep it concise and compelling.' => "{{ __('admin.description_usage_hint') }}",
    'Separate tags with commas' => "{{ __('admin.separate_tags_commas') }}",
    'Include specific dates and facts' => "{{ __('admin.include_dates_facts') }}",
    'Provide practical visitor information' => "{{ __('admin.provide_visitor_info') }}",
    'Cite reliable sources' => "{{ __('admin.cite_reliable_sources') }}",
    'Current featured image' => "{{ __('admin.current_featured_image') }}",
    'Additional monument photos' => "{{ __('admin.additional_monument_photos') }}",
    
    // Posts
    'Multilingual Content' => "{{ __('admin.multilingual_content') }}",
    'Title (English)' => "{{ __('admin.title_english') }}",
    'Title (Vietnamese)' => "{{ __('admin.title_vietnamese') }}",
    'Short Description (English)' => "{{ __('admin.description_english') }}",
    'Content (English)' => "{{ __('admin.content_english') }}",
    'Enter title in English' => "{{ __('admin.placeholder_title_en') }}",
    'Enter title in Vietnamese' => "{{ __('admin.placeholder_title_vi') }}",
    'Brief summary in English' => "{{ __('admin.placeholder_description_en') }}",
    'Quick Guide' => "{{ __('admin.quick_guide') }}",
    
    // Common
    'Approve' => "{{ __('admin.approve') }}",
    'Reject' => "{{ __('admin.reject') }}",
];

$files = [
    'resources/views/admin/dashboard_modern.blade.php',
    'resources/views/admin/feedbacks/index.blade.php',
    'resources/views/admin/feedbacks/show.blade.php',
    'resources/views/admin/gallery/index.blade.php',
    'resources/views/admin/gallery/create.blade.php',
    'resources/views/admin/gallery/edit.blade.php',
    'resources/views/admin/gallery/show.blade.php',
    'resources/views/admin/monuments/create_multilingual.blade.php',
    'resources/views/admin/monuments/create_professional.blade.php',
    'resources/views/admin/monuments/edit.blade.php',
    'resources/views/admin/monuments/edit_multilingual.blade.php',
    'resources/views/admin/posts/edit_multilingual.blade.php',
    'resources/views/admin/posts/create_simple.blade.php',
];

$totalReplacements = 0;

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "⚠️  File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileReplacements = 0;
    
    foreach ($replacements as $search => $replace) {
        $count = 0;
        $content = str_replace($search, $replace, $content, $count);
        $fileReplacements += $count;
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "✅ Updated: $file ($fileReplacements replacements)\n";
        $totalReplacements += $fileReplacements;
    } else {
        echo "⏭️  No changes: $file\n";
    }
}

echo "\n";
echo "=================================\n";
echo "Summary\n";
echo "=================================\n";
echo "Total files processed: " . count($files) . "\n";
echo "Total replacements: $totalReplacements\n";
echo "\n";
echo "✅ Done!\n";

