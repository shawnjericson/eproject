<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixTranslationSyntax extends Command
{
    protected $signature = 'fix:translation-syntax';
    protected $description = 'Fix syntax errors caused by translation replacement';

    public function handle()
    {
        $viewsPath = resource_path('views/admin');
        $files = $this->getAllBladeFiles($viewsPath);
        
        $this->info("Found " . count($files) . " blade files to fix...");
        
        foreach ($files as $file) {
            $this->fixFile($file);
        }
        
        $this->info("Syntax fix complete!");
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
    
    protected function fixFile($filePath)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        
        // Fix common syntax errors
        $fixes = [
            // Fix missing quotes in attributes
            '/placeholder=\{\{([^}]+)\}\}/' => 'placeholder="{{ $1 }}"',
            '/value=\{\{([^}]+)\}\}/' => 'value="{{ $1 }}"',
            '/title=\{\{([^}]+)\}\}/' => 'title="{{ $1 }}"',
            '/alt=\{\{([^}]+)\}\}/' => 'alt="{{ $1 }}"',
            '/src=\{\{([^}]+)\}\}/' => 'src="{{ $1 }}"',
            '/href=\{\{([^}]+)\}\}/' => 'href="{{ $1 }}"',
            '/class=\{\{([^}]+)\}\}/' => 'class="{{ $1 }}"',
            '/id=\{\{([^}]+)\}\}/' => 'id="{{ $1 }}"',
            
            // Fix broken comments
            '/<!-- \{\{ __\([^}]+\) \}\}s -->/' => '<!-- Filters -->',
            '/<!-- \{\{ __\([^}]+\) \}\} -->/' => '<!-- Content -->',
            
            // Fix broken HTML tags
            '/<\{\{ __\([^}]+\) \}\}>/' => '<div>',
            '/<\/\{\{ __\([^}]+\) \}\}>/' => '</div>',
            
            // Fix broken form labels that got translated
            '/for=\{\{([^}]+)\}\}/' => 'for="$1"',
            
            // Fix broken JavaScript/CSS that got translated
            '/\{\{ __\(\'admin\.([^\']+)\'\) \}\}\(/' => '$1(',
            '/\{\{ __\(\'admin\.([^\']+)\'\) \}\}\./' => '$1.',
            '/\{\{ __\(\'admin\.([^\']+)\'\) \}\};/' => '$1;',
            
            // Fix broken array keys
            '/\[\{\{ __\([^}]+\) \}\}\]/' => '[\'key\']',
            
            // Fix broken function calls
            '/\{\{ __\(\'admin\.([^\']+)\'\) \}\}\s*\(/' => '$1(',
        ];
        
        foreach ($fixes as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Additional specific fixes
        $specificFixes = [
            // Fix common broken patterns
            '{{ __("admin.filter") }}s' => 'Filters',
            '{{ __("admin.search") }}...' => 'Search...',
            '{{ __("admin.all") }} {{ __("admin.status") }}' => 'All Status',
            '{{ __("admin.all") }} Zones' => 'All Zones',
            '{{ __("admin.no") }} {{ __("admin.data") }} {{ __("admin.found") }}' => 'No data found',
            
            // Fix broken blade directives
            '@{{ __(' => '@lang(',
            '}}{{ __(' => ', __(',
            
            // Fix broken quotes in translation calls
            "{{ __('admin.search_monuments') }}" => '"{{ __("admin.search_monuments") }}"',
            "{{ __('admin.search_posts') }}" => '"{{ __("admin.search_posts") }}"',
            "{{ __('admin.search_users') }}" => '"{{ __("admin.search_users") }}"',
            "{{ __('admin.search_images') }}" => '"{{ __("admin.search_images") }}"',
        ];
        
        foreach ($specificFixes as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
        // Fix any remaining quote issues in attributes
        $content = preg_replace('/(\w+)=\{\{([^}]+)\}\}(?!")/', '$1="{{ $2 }}"', $content);
        
        if ($content !== $originalContent) {
            File::put($filePath, $content);
            $this->line("Fixed: " . basename($filePath));
        }
    }
}
