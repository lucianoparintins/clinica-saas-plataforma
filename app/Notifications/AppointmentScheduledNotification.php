<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentScheduledNotification extends Notification implements ShouldQueue
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
            ->subject('Sua consulta foi agendada!')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Estamos confirmando que sua consulta foi agendada com sucesso.')
            ->line("**Data e Hora:** {$scheduledAt}")
            ->line("**Médico(a):** {$doctorName}")
            ->line('Caso precise reagendar ou cancelar, entre em contato conosco.')
            ->action('Ver Detalhes do Agendamento', url('/'))
            ->line('Obrigado por escolher nossa clínica!');
    }

    /**
     * Placeholder para futura implementação de SMS.
     *
     * @return array<string, mixed>
     */
    public function toSms(object $notifiable): array
    {
        return [
            'message' => "Sua consulta foi agendada para {$this->appointment->scheduled_at->format('d/m/Y H:i')} com {$this->appointment->doctor->name}.",
        ];
    }

    /**
     * Placeholder para futura implementação de WhatsApp.
     *
     * @return array<string, mixed>
     */
    public function toWhatsapp(object $notifiable): array
    {
        return [
            'template' => 'appointment_confirmation',
            'data' => [
                'date' => $this->appointment->scheduled_at->format('d/m/Y H:i'),
                'doctor' => $this->appointment->doctor->name,
            ],
        ];
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
        ];
    }
}
