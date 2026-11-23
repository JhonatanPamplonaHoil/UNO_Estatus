<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\PermisosHelpers;

/**
 * Login form
 */
class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';
    public bool $rememberMe = true;
    private User|null $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required', 'message' => 'Este campo no puede estar vacío.'],
            ['email', 'email', 'message' => 'Ingresa un correo electrónico válido.'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'rememberMe' => 'Recordarme en este equipo',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, array|null $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Correo electrónico o contraseña incorrectos.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser(): User|null
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function loginAdmin()
    {
        if (($this->validate()) && PermisosHelpers::requerirMinimoRol('Admin', $this->getUser()->id)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            throw new NotFoundHttpException('No Pasarás.');
        }
    }
}