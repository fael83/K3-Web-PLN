<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentReviewReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Document $document,
        public int $daysLeft
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
        // Nanti bisa ditambah 'mail' jika email reminder mau diaktifkan
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'document_review_reminder',
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'document_number' => $this->document->document_number,
            'review_date' => optional($this->document->review_date)->format('Y-m-d'),
            'days_left' => $this->daysLeft,
            'message' => "Dokumen '{$this->document->title}' akan memasuki review date dalam {$this->daysLeft} hari.",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Review Dokumen')
            ->line("Dokumen '{$this->document->title}' akan memasuki review date dalam {$this->daysLeft} hari.")
            ->line('Silakan lakukan peninjauan dokumen sesuai kebutuhan.');
    }
}