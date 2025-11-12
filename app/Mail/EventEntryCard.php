<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class EventEntryCard extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct($registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'বংশ অনুষ্ঠান - এন্ট্রি কার্ড',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // এই Blade view টা ইমেইলের বডিতে দেখা যাবে
        return new Content(
            markdown: 'emails.entry_card_message',
            with: [
                'registration' => $this->registration,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // PDF তৈরি করা (mPDF দিয়ে)
        $html = View::make('pdf.entry_card', ['registration' => $this->registration])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);
        $pdfOutput = $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'I'); // PDF as string

        // ইমেইলে অ্যাটাচ করা
        return [
            Attachment::fromData(fn() => $pdfContent, "Entry-Card-{$this->registration->registration_id}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
