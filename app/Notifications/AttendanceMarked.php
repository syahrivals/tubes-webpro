<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Presence;
use App\Models\Matkul;

class AttendanceMarked extends Notification implements ShouldQueue
{
    use Queueable;

    protected $presence;
    protected $matkul;

    /**
     * Create a new notification instance.
     */
    public function __construct(Presence $presence, Matkul $matkul)
    {
        $this->presence = $presence;
        $this->matkul = $matkul;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = match($this->presence->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => 'Unknown'
        };

        return (new MailMessage)
            ->subject("Presensi {$this->matkul->nama} - {$statusText}")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Presensi Anda untuk mata kuliah **{$this->matkul->nama}** telah dicatat.")
            ->line("**Tanggal:** " . \Carbon\Carbon::parse($this->presence->tanggal)->format('d/m/Y'))
            ->line("**Status:** {$statusText}")
            ->when($this->presence->catatan, function ($mail) {
                return $mail->line("**Catatan:** {$this->presence->catatan}");
            })
            ->action('Lihat Detail', route('mahasiswa.reports.student', ['matkul_id' => $this->matkul->id]))
            ->line('Terima kasih telah menggunakan Sistem Presensi Universitas!')
            ->salutation('Salam, Tim Akademik');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_marked',
            'presence_id' => $this->presence->id,
            'matkul_id' => $this->matkul->id,
            'matkul_name' => $this->matkul->nama,
            'status' => $this->presence->status,
            'tanggal' => $this->presence->tanggal,
            'catatan' => $this->presence->catatan,
            'message' => "Presensi {$this->matkul->nama} telah dicatat sebagai " . ucfirst($this->presence->status)
        ];
    }
}
