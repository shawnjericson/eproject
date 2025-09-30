<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslateViews extends Command
{
    protected $signature = 'translate:views';
    protected $description = 'Automatically translate common text in admin views';

    protected $translations = [
        // Common buttons and actions
        'Create New' => "{{ __('admin.create_new') }}",
        'Add New' => "{{ __('admin.add_new') }}",
        'Edit' => "{{ __('admin.edit') }}",
        'Delete' => "{{ __('admin.delete') }}",
        'View' => "{{ __('admin.view') }}",
        'Save' => "{{ __('admin.save') }}",
        'Update' => "{{ __('admin.update') }}",
        'Cancel' => "{{ __('admin.cancel') }}",
        'Back' => "{{ __('admin.back') }}",
        'Filter' => "{{ __('admin.filter') }}",
        'Reset' => "{{ __('admin.reset') }}",
        'Clear' => "{{ __('admin.clear') }}",
        'Search' => "{{ __('admin.search') }}",
        'Actions' => "{{ __('admin.actions') }}",
        
        // Status labels
        'Active' => "{{ __('admin.active') }}",
        'Inactive' => "{{ __('admin.inactive') }}",
        'Published' => "{{ __('admin.published') }}",
        'Draft' => "{{ __('admin.draft') }}",
        'Pending' => "{{ __('admin.pending') }}",
        'Approved' => "{{ __('admin.approved') }}",
        'Rejected' => "{{ __('admin.rejected') }}",
        'All Status' => "{{ __('admin.all_status') }}",
        
        // Common fields
        'Title' => "{{ __('admin.title') }}",
        'Description' => "{{ __('admin.description') }}",
        'Content' => "{{ __('admin.content') }}",
        'Image' => "{{ __('admin.image') }}",
        'Status' => "{{ __('admin.status') }}",
        'Author' => "{{ __('admin.author') }}",
        'Created' => "{{ __('admin.created_at') }}",
        'Updated' => "{{ __('admin.updated_at') }}",
        'Name' => "{{ __('admin.name') }}",
        'Email' => "{{ __('admin.email') }}",
        'Role' => "{{ __('admin.role') }}",
        
        // Placeholders
        'Search posts...' => "{{ __('admin.search_posts') }}",
        'Search monuments...' => "{{ __('admin.search_monuments') }}",
        'Search users...' => "{{ __('admin.search_users') }}",
        'Search images...' => "{{ __('admin.search_images') }}",
        
        // Messages
        'No data found' => "{{ __('admin.no_data_found') }}",
        'Are you sure?' => "{{ __('admin.confirm_delete') }}",
        'Success' => "{{ __('admin.success') }}",
        'Error' => "{{ __('admin.error') }}",
        
        // Specific pages
        'Posts Management' => "{{ __('admin.posts_management') }}",
        'Monuments Management' => "{{ __('admin.monuments_management') }}",
        'Gallery Management' => "{{ __('admin.gallery_management') }}",
        'Users Management' => "{{ __('admin.users_management') }}",
        'Settings Management' => "{{ __('admin.settings_management') }}",
        'Feedbacks Management' => "{{ __('admin.feedbacks_management') }}",
    ];

    public function handle()
    {
        $viewsPath = resource_path('views/admin');
        $files = $this->getAllBladeFiles($viewsPath);
        
        $this->info("Found " . count($files) . " blade files to process...");
        
        foreach ($files as $file) {
            $this->processFile($file);
        }
        
        $this->info("Translation complete!");
    }
    
    protected function getAllBladeFiles($directory)
    {
        $files = [];
        
        if (!File::exists($directory)) {
            return $files;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    protected function processFile($filePath)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        
        foreach ($this->translations as $search => $replace) {
            // Replace in HTML content (not in PHP code)
            $content = preg_replace(
                '/(?<![\'"@{])' . preg_quote($search, '/') . '(?![\'"@}])/',
                $replace,
                $content
            );
            
            // Replace in title attributes and placeholders
            $content = str_replace(
                "'" . $search . "'",
                $replace,
                $content
            );
            
            $content = str_replace(
                '"' . $search . '"',
                $replace,
                $content
            );
        }
        
        if ($content !== $originalContent) {
            File::put($filePath, $content);
            $this->line("Updated: " . basename($filePath));
        }
    }
}
