<?php

namespace App\Notifications\Pengaduan;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusUpdateNotification extends Notification
{
    use Queueable;

    public $pengaduan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengaduan_id' => $this->pengaduan->id,
            'kode_tracking' => $this->pengaduan->kode_tracking,
            'judul' => $this->pengaduan->judul,
            'status_baru' => $this->pengaduan->status,
            'pesan' => "Status laporan '" . $this->pengaduan->judul . "' diperbarui menjadi " . strtoupper($this->pengaduan->status) . ".",
        ];
    }
}
