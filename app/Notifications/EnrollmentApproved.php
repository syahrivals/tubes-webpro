<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Enrollment;

class EnrollmentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
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
            ->subject("Enrollment Disetujui - {$this->enrollment->matkul->nama}")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Selamat! Permohonan enrollment Anda untuk mata kuliah **{$this->enrollment->matkul->nama}** telah **DISETUJUI**.")
            ->line("**Dosen Pengampu:** {$this->enrollment->matkul->dosen->name}")
            ->line("**Hari:** {$this->enrollment->matkul->hari}")
            ->line("**Jam:** {$this->enrollment->matkul->jam}")
            ->line("**Deskripsi:** {$this->enrollment->matkul->deskripsi}")
            ->action('Lihat Mata Kuliah', route('mahasiswa.enrollments.index'))
            ->line('Anda sekarang dapat melakukan presensi untuk mata kuliah ini.')
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
            'type' => 'enrollment_approved',
            'enrollment_id' => $this->enrollment->id,
            'matkul_name' => $this->enrollment->matkul->nama,
            'dosen_name' => $this->enrollment->matkul->dosen->name,
            'hari' => $this->enrollment->matkul->hari,
            'jam' => $this->enrollment->matkul->jam,
            'message' => "Enrollment Anda untuk {$this->enrollment->matkul->nama} telah disetujui"
        ];
    }
}
