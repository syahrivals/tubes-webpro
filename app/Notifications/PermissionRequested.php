<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Izin;

class PermissionRequested extends Notification implements ShouldQueue
{
    use Queueable;

    protected $izin;

    /**
     * Create a new notification instance.
     */
    public function __construct(Izin $izin)
    {
        $this->izin = $izin;
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
        return (new MailMessage)
            ->subject("Permohonan Izin Baru - {$this->izin->mahasiswa->user->name}")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Mahasiswa **{$this->izin->mahasiswa->user->name}** ({$this->izin->mahasiswa->nim}) mengajukan permohonan izin.")
            ->line("**Mata Kuliah:** {$this->izin->matkul->nama}")
            ->line("**Tanggal:** " . \Carbon\Carbon::parse($this->izin->tanggal)->format('d/m/Y'))
            ->line("**Alasan:** {$this->izin->alasan}")
            ->when($this->izin->bukti, function ($mail) {
                return $mail->line("**Bukti:** Dilampirkan");
            })
            ->action('Tinjau Permohonan', route('dosen.izin.index'))
            ->line('Silakan tinjau dan berikan keputusan atas permohonan ini.')
            ->salutation('Salam, Sistem Presensi');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'permission_requested',
            'izin_id' => $this->izin->id,
            'mahasiswa_name' => $this->izin->mahasiswa->user->name,
            'mahasiswa_nim' => $this->izin->mahasiswa->nim,
            'matkul_name' => $this->izin->matkul->nama,
            'tanggal' => $this->izin->tanggal,
            'alasan' => $this->izin->alasan,
            'message' => "Permohonan izin dari {$this->izin->mahasiswa->user->name} untuk {$this->izin->matkul->nama}"
        ];
    }
}
