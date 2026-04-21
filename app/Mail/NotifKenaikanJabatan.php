<?php

namespace App\Mail;

use App\Models\Pejabat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifKenaikanJabatan extends Mailable
{
    use Queueable, SerializesModels;

    public $pejabat;

    public function __construct(Pejabat $pejabat)
    {
        $this->pejabat = $pejabat;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Persiapan Kenaikan Jabatan',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notif_kenaikan',
        );
    }
}
