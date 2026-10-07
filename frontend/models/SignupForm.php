<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use common\models\ValorHelpers;
use frontend\models\Perfil;
use Yii;
use yii\base\Model;
use yii\mail\MailerInterface;

/**
 * Signup form adaptado a la arquitectura de roles y perfiles
 */
class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $password = '';

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Este nombre de usuario ya está registrado.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Este correo electrónico ya está registrado.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'] ?? 6],
        ];
    }

    /**
     * Registra el usuario asignando roles institucionales y creando su perfil asociado.
     *
     * @param MailerInterface $mailer
     * @param string $supportEmail
     * @param string $appName
     * @return bool|null
     */
    public function signup(MailerInterface $mailer, string $supportEmail, string $appName): bool|null
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        // Asignación de rol, estado institucional y tipo de usuario
        $user->rol_id = 1; // Usuario / Egresado
        $user->estado_id = ValorHelpers::getEstadoId('Activo');
        $user->tipo_usuario_id = 1;

        if ($user->save()) {
            // Creación automática del perfil asociado al usuario
            $perfil = new Perfil();
            $perfil->user_id = $user->id;
            $perfil->nombre = $this->username;
            $perfil->genero_id = 1; // Asigna un valor inicial válido del catálogo genero
            $perfil->fecha_nacimiento = date('Y-m-d'); // Fecha inicial para evitar error de campo no nulo

            $perfil->save(false);

            // Iniciar sesión automáticamente con la nueva cuenta
            Yii::$app->user->login($user);

            return true;
        }

        return false;
    }
}