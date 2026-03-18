<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Appointment $appointment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $scheduledAt = $this->appointment->scheduled_at->format('d/m/Y H:i');
        $doctorName = $this->appointment->doctor->name;

        return (new MailMessage)
            ->subject('Sua consulta foi cancelada')
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Estamos informando que sua consulta foi cancelada.")
            ->line("**Data e Hora Original:** {$scheduledAt}")
            ->line("**Médico(a):** {$doctorName}")
            ->line('Caso deseje agendar uma nova consulta, por favor acesse nosso sistema.')
            ->action('Acessar Sistema', url('/'))
            ->line('Obrigado por sua compreensão.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'scheduled_at' => $this->appointment->scheduled_at,
            'doctor_name' => $this->appointment->doctor->name,
            'status' => 'canceled',
        ];
    }
}
