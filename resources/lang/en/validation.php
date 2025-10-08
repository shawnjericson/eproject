<?php

return [
    'custom' => [
        'language' => [
            'required' => 'Please choose a language.',
            'in' => 'Language must be English or Vietnamese.',
        ],
        'title' => [
            'required' => 'Please enter a title.',
            'max' => 'The title may not be greater than :max characters.',
        ],
        'content' => [
            'required' => 'Please enter the content.',
        ],
        'status' => [
            'required' => 'Please select a status.',
            'in' => 'Status must be one of draft, pending, or approved.',
        ],
        'image' => [
            'image' => 'The file must be an image.',
            'mimes' => 'Allowed formats: :values.',
            'max' => 'The image size may not be greater than :max kilobytes.',
        ],
        'translations' => [
            'required' => 'Please provide translation content.',
        ],
        'reply' => [
            'required' => 'Please enter your reply.',
        ],
        'key' => [
            'required' => 'Please provide a setting key.',
            'unique' => 'This setting key already exists.',
        ],
        'value' => [
            'required' => 'Please provide a setting value.',
        ],
        'email' => [
            'required' => 'Please enter an email address.',
            'email' => 'Please enter a valid email address.',
        ],
        'password' => [
            'required' => 'Please enter a password.',
            'confirmed' => 'Password confirmation does not match.',
            'min' => 'Password must be at least :min characters.',
        ],
        'current_password' => [
            'required' => 'Please enter your current password.',
        ],
        'new_password' => [
            'required' => 'Please enter a new password.',
            'confirmed' => 'New password confirmation does not match.',
            'min' => 'New password must be at least :min characters.',
        ],
    ],
];



