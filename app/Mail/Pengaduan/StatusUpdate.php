<?php

namespace App\Mail\Pengaduan;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusUpdate extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pengaduan;
    public $subjectLine;
    public $statusLabel;
    public $statusMessage;
    public $actionLabel;
    public $actionUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
        
        $statusConfig = [
            'menunggu' => [
                'subject' => 'Laporan Diterima - ' . $pengaduan->kode_tracking,
                'label' => 'DITERIMA',
                'message' => 'Laporan Anda telah berhasil masuk ke sistem kami dan sedang menunggu verifikasi awal oleh petugas.',
            ],
            'proses' => [
                'subject' => 'Laporan Sedang Diproses - ' . $pengaduan->kode_tracking,
                'label' => 'SEDANG DIPROSES',
                'message' => 'Kabar baik! Laporan Anda saat ini sedang dalam tahap tindak lanjut oleh petugas/bidang terkait.',
            ],
            'selesai' => [
                'subject' => 'Laporan Selesai Ditindaklanjuti - ' . $pengaduan->kode_tracking,
                'label' => 'SELESAI',
                'message' => 'Laporan Anda telah dinyatakan selesai ditindaklanjuti. Terima kasih atas kontribusi Anda dalam membangun lingkungan yang lebih baik.',
            ],
            'ditolak' => [
                'subject' => 'Informasi Mengenai Laporan Anda - ' . $pengaduan->kode_tracking,
                'label' => 'DITOLAK / DIARSIPKAN',
                'message' => 'Mohon maaf, laporan Anda belum dapat kami tindak lanjuti saat ini. Silakan cek detail alasan penolakan pada website.',
            ],
        ];

        $config = $statusConfig[$pengaduan->status] ?? $statusConfig['menunggu'];

        $this->subjectLine = $config['subject'];
        $this->statusLabel = $config['label'];
        $this->statusMessage = $config['message'];
        $this->actionLabel = 'Pantau Progres Laporan';
        $this->actionUrl = route('pengaduan.feed-detail', ['kode_tracking' => $pengaduan->kode_tracking]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pengaduan.status-update',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
