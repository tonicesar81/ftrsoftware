<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification {

    use Queueable;
    
    public $token;

    public static $toMailCallback;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token) {
        //
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        //You are receiving this email because we received a password reset request for your account.
        //If you did not request a password reset, no further action is required
        return (new MailMessage)
                        ->subject('redefinição de senha')
                        ->line('Você está recebendo esse email porque nós recebemos um pedido de redefinição de senha para sua conta.')
                        ->action('Redefinir senha', url(config('app.url') . route('password.reset', $this->token, false)))
                        ->line('Se você não solicitou uma definição de senha, apenas desconsidere esse email.');
    }

    public static function toMailUsing($callback) {
        static::$toMailCallback = $callback;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
                //
        ];
    }

}
