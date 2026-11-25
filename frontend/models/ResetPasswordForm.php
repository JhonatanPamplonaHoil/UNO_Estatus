<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class ResetPasswordForm extends Model
{
    public string $password = '';
    public string $password_repeat = '';
    private User $_user;

    public function __construct(string $token, array $config = [])
    {
        if (empty($token)) {
            throw new InvalidArgumentException('El código de recuperación no puede estar vacío.');
        }

        $user = User::findByPasswordResetToken($token);
        if (!$user) {
            throw new InvalidArgumentException('El código de recuperación es inválido o ha expirado.');
        }

        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['password', 'required', 'message' => 'Ingresa tu nueva contraseña.'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'] ?? 6],
            ['password_repeat', 'required', 'message' => 'Debes confirmar tu nueva contraseña.'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'password' => 'Nueva contraseña',
            'password_repeat' => 'Confirmar contraseña',
        ];
    }

    public function resetPassword(): bool
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}