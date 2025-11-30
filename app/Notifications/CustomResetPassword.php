<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPasswordNotification
{
    // Override the method to customize the email message
    public function toMail($notifiable)
    {
        // Render a Blade view for the email content
        return (new MailMessage)
                    ->subject('Custom Reset Password Subject')  // Custom subject here
                    ->view('vendor.notifications.email', [
                        'actionUrl' => url(route('password.reset', $this->token, false)),
                        'userName' => $notifiable->name,  // Optionally, add user name to email
                    ]);
    }
}
