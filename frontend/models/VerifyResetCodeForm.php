<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use yii\base\Model;

class VerifyResetCodeForm extends Model
{
    public string $email = '';
    public string $code = '';
    private ?string $_token = null;

    public function rules(): array
    {
        return [
            [['email', 'code'], 'trim'],
            ['code', 'required', 'message' => 'Ingresa la clave enviada a tu correo.'],
            ['code', 'match', 'pattern' => '/^[0-9]{6}$/', 'message' => 'La clave debe ser de 6 dígitos numéricos.'],
            ['code', 'validateCode'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'code' => 'Clave de verificación',
        ];
    }

    public function validateCode(string $attribute): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = User::findByEmail($this->email);

        if (!$user || empty($user->password_reset_token)) {
            $this->addError($attribute, 'No hay una solicitud de recuperación activa.');
            return;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $this->addError($attribute, 'La clave ha expirado. Solicita una nueva.');
            return;
        }

        [$savedCode] = explode('_', $user->password_reset_token);

        if ($savedCode !== $this->code) {
            $this->addError($attribute, 'La clave ingresada es incorrecta.');
            return;
        }

        $this->_token = $user->password_reset_token;
    }

    public function getToken(): ?string
    {
        return $this->_token;
    }
}