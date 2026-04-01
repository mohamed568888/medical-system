<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;
    public $patient;

    public function __construct($patient)
    {
        $this->patient = $patient;
    }

    public function via($notifiable)
    {
        return ['database']; // سنخزن الإشعار في قاعدة البيانات
    }

    public function toArray($notifiable)
    {
        return [
            'patient_name' => $this->patient->name,
            'clinic_name' => $this->patient->clinic->name ?? 'N/A',
            'message' => 'New appointment booked by ' . $this->patient->name
        ];
    }
}
