<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use yii\base\Model;
use yii\mail\MailerInterface;

class PasswordResetRequestForm extends Model
{
    public string $email = '';

    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => 'Ingresa tu correo electrónico.'],
            ['email', 'email', 'message' => 'Ingresa un correo electrónico válido.'],
            [
                'email',
                'exist',
                'targetClass' => User::class,
                'message' => 'No existe ninguna cuenta registrada con este correo.',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Correo electrónico',
        ];
    }

    public function sendEmail(MailerInterface $mailer, string $supportEmail, string $appName): bool
    {
        $user = User::findByEmail($this->email);

        if (!$user) {
            return false;
        }

        // Genera clave de 6 dígitos + timestamp (compatible con isPasswordResetTokenValid)
        $codigo = (string) random_int(100000, 999999);
        $user->password_reset_token = $codigo . '_' . time();

        if (!$user->save(false)) {
            return false;
        }

        return $mailer
            ->compose()
            ->setFrom([$supportEmail => $appName])
            ->setTo($this->email)
            ->setSubject('Código de recuperación de contraseña - ' . $appName)
            ->setHtmlBody("
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #7d0d30;'>Recuperación de contraseña - UNO</h2>
                    <p>Hola <b>{$user->username}</b>,</p>
                    <p>Usa la siguiente clave de verificación para restablecer tu contraseña:</p>
                    <div style='font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #7d0d30; background: #fcf8f9; padding: 15px; border-radius: 8px; display: inline-block; border: 1px solid #e2d1d6;'>
                        {$codigo}
                    </div>
                    <p style='margin-top: 15px; font-size: 13px; color: #666;'>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                </div>
            ")
            ->send();
    }
}