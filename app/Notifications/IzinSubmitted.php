<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Izin;

class IzinSubmitted extends Notification
{
    use Queueable;

    protected $izin;

    public function __construct(Izin $izin)
    {
        $this->izin = $izin;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Mahasiswa ' . $this->izin->mahasiswa->user->name . ' mengajukan izin untuk ' . $this->izin->matkul->nama . ' pada ' . $this->izin->tanggal->format('Y-m-d'),
            'izin_id' => $this->izin->id,
        ];
    }
}
