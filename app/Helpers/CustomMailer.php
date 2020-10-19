<?php
namespace App\Helpers;

use App\Mail\AppMail;
use App\Mail\PasswordRequestMail;

class CustomMailer
{
    public function sendPasswordResetEmail(string $email, string $username, string $text)
    {
        $mailData = array('username' => $username, 'text' => $text);
        \Mail::to($email)->send(new PasswordRequestMail($mailData));
    }
}
