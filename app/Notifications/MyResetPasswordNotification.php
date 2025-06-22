<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class MyResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(Lang::get('Сброс пароля для сайта dualshop'))
                    ->greeting(Lang::get('Здравствуйте!'))
                    ->line(Lang::get('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи на сайте Дуалшоп.'))
                    ->action(
                        Lang::get('Сбросить пароль'),
                        route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()])
                    )
                    ->line(Lang::get('Срок действия этой ссылки для сброса пароля истечет через :count минут.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                    ->line(Lang::get('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'))
                    ->salutation(Lang::get('С уважением,') . '\n' . config('app.name'));
    }
} 