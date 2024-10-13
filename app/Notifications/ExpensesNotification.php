<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Expenses;

class ExpensesNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $expenses;

    public function __construct(Expenses $expenses)
    {
        $this->expenses = $expenses;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)
            ->line('Sending expense email')
            ->line('Hey, ' . $this->expenses->user->name)
            ->line('Goat registered from R$ ' . number_format($this->expenses->value, 2, ',', '.'))
            ->action('Site', url('/expenses/' . $this->expenses->id))
            ->line('Thank you for using our application!');
    }
}
