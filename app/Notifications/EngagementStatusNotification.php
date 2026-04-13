<?php

namespace App\Notifications;

use App\Models\Engagement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EngagementStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $engagement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Engagement $engagement)
    {
        $this->engagement = $engagement;
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
        $statusLabel = $this->engagement->statut === 'approuve' ? 'approuvé' : 'rejeté';
        
        $mail = (new MailMessage)
            ->subject('E-Intendance : Expression de besoins ' . $statusLabel)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre Expression de besoins (N° **' . $this->engagement->id . '**) d\'un montant de **' . number_format($this->engagement->total_ttc, 2, ',', ' ') . ' DH** a été **' . $statusLabel . '** par l\'administration.');

        if ($this->engagement->statut === 'rejete' && $this->engagement->motif_refus) {
            $mail->line('**Motif de refus :** ' . $this->engagement->motif_refus);
            $mail->error();
        } else {
            $mail->success();
        }

        $mail->action('Consulter mes expressions de besoins', route('emetteur.engagements.index'))
            ->line('Merci d\'utiliser le système E-Intendance (LISI).');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'engagement_id' => $this->engagement->id,
            'statut' => $this->engagement->statut,
        ];
    }
}
