<?php

require_once 'vendor/autoload.php';

use App\Mail\ContactReplyMail;
use App\Models\Contact;

// Create test contact
$contact = new Contact([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Subject',
    'message' => 'This is a test message.',
    'status' => 'new'
]);

$reply = 'This is a test reply from Global Heritage.';

// Test email template rendering
$mail = new ContactReplyMail($contact, $reply);

echo "Email template created successfully!\n";
echo "Subject: " . $mail->envelope()->subject . "\n";
echo "Contact: " . $contact->name . " <" . $contact->email . ">\n";
echo "Reply: " . $reply . "\n";


