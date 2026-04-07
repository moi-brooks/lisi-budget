<?php

namespace App\Notifications;

use App\Models\LigneBudgetProposee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropositionStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $proposition;

    /**
     * Create a new notification instance.
     */
    public function __construct(LigneBudgetProposee $proposition)
    {
        $this->proposition = $proposition;
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
        $statusLabel = $this->proposition->statut === 'approuve' ? 'approuvée' : 'rejetée';
        
        $mail = (new MailMessage)
            ->subject('E-Intendance : Proposition de budget ' . $statusLabel)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre proposition de budget pour la ligne **' . $this->proposition->ligne->nom . '** d\'un montant de **' . $this->proposition->montant . ' DH** a été **' . $statusLabel . '** par l\'administration.');

        if ($this->proposition->statut === 'rejete' && $this->proposition->motif_refus) {
            $mail->line('**Motif de refus :** ' . $this->proposition->motif_refus);
            $mail->error(); // Display as Red button
        } else {
            $mail->success(); // Display as Green button
        }

        $mail->action('Consulter mes propositions', route('emetteur.propositions.index'))
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
            'proposition_id' => $this->proposition->id,
            'statut' => $this->proposition->statut,
        ];
    }
}
