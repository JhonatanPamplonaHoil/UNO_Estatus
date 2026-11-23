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
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $email = '';
    public string $telefono = '';
    public string $password = '';
    public string $password_repeat = '';

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

            [['apellido_paterno', 'apellido_materno'], 'trim'],
            ['apellido_paterno', 'required', 'message' => 'El apellido paterno es obligatorio.'],
            ['apellido_materno', 'required', 'message' => 'El apellido materno es obligatorio.'],
            [['apellido_paterno', 'apellido_materno'], 'string', 'max' => 100],
            [
                ['apellido_paterno', 'apellido_materno'],
                'match',
                'pattern' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]+$/',
                'message' => 'Solo se permite una palabra (sin espacios ni números).',
            ],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Este correo electrónico ya está registrado.'],

            ['telefono', 'trim'],
            ['telefono', 'required', 'message' => 'El número telefónico es obligatorio.'],
            ['telefono', 'match', 'pattern' => '/^[0-9]{10}$/', 'message' => 'Ingresa un número válido de 10 dígitos.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'] ?? 6],

            ['password_repeat', 'required', 'message' => 'Debes confirmar tu contraseña.'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Nombre de usuario',
            'apellido_paterno' => 'Apellido paterno',
            'apellido_materno' => 'Apellido materno',
            'email' => 'Correo electrónico',
            'telefono' => 'Número telefónico',
            'password' => 'Contraseña',
            'password_repeat' => 'Confirmar contraseña',
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
        $user->apellido_paterno = $this->apellido_paterno;
        $user->apellido_materno = $this->apellido_materno;
        $user->email = $this->email;
        $user->telefono = $this->telefono;
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
            // Si tu tabla perfil tiene columna 'apellido', descomenta la siguiente línea:
            // $perfil->apellido = trim($this->apellido_paterno . ' ' . $this->apellido_materno);
            $perfil->genero_id = 1;
            $perfil->fecha_nacimiento = date('Y-m-d');

            $perfil->save(false);

            // Iniciar sesión automáticamente con la nueva cuenta
            Yii::$app->user->login($user);

            return true;
        }

        return false;
    }
}