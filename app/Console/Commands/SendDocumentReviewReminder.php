<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\User;
use Illuminate\Console\Command;

class SendDocumentReviewReminder extends Command
{
    protected $signature = 'documents:review-reminder';
    protected $description = 'Kirim pengingat dokumen yang mendekati review date';

    public function handle(): int
    {
        $documents = Document::query()
            ->expiringSoon()
            ->get();

        if ($documents->isEmpty()) {
            $this->info('Tidak ada dokumen yang mendekati review date.');
            return self::SUCCESS;
        }

        $recipients = User::query()
            ->whereIn('role', ['sys_admin', 'k3_manager', 'k3_officer'])
            ->get();

        if ($recipients->isEmpty()) {
            $this->warn('Tidak ada penerima notifikasi.');
            return self::SUCCESS;
        }

        foreach ($documents as $document) {
            $daysLeft = now()->startOfDay()->diffInDays($document->review_date->copy()->startOfDay(), false);

            foreach ($recipients as $user) {
                // Sementara log ke database notification bawaan Laravel
                $user->notify(new \App\Notifications\DocumentReviewReminderNotification($document, $daysLeft));
            }

            $this->info("Reminder dikirim untuk dokumen: {$document->title}");
        }

        return self::SUCCESS;
    }
}