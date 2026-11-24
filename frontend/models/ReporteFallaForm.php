<?php

declare(strict_types=1);

namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\mail\MailerInterface;
use common\models\ReporteFalla;
/**
 * Formulario de Reporte de Fallas del Sistema UNO.
 */
class ReporteFallaForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $body = '';
    public string $verifyCode = '';

    /**
     * Categorías predefinidas de fallas
     */
    public static function getCategoriasFalla(): array
    {
        return [
            'Error al cargar una página' => 'Error al cargar una página',
            'Falla en trámite de titulación' => 'Falla en trámite de titulación',
            'Problema al agendar cita' => 'Problema al agendar cita',
            'Error de cuenta o inicio de sesión' => 'Error de cuenta o inicio de sesión',
            'Error visual o de diseño' => 'Error visual o de diseño',
            'Otro tipo de bug / falla' => 'Otro tipo de bug / falla',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'subject', 'body'], 'trim'],
            [['name', 'email', 'subject', 'body'], 'required', 'message' => 'Este campo es obligatorio.'],
            ['email', 'email', 'message' => 'Ingresa un correo electrónico válido.'],
            ['body', 'string', 'min' => 10, 'tooShort' => 'Describe la falla con al menos 10 caracteres.'],
            ['verifyCode', 'captcha', 'message' => 'El código de verificación es incorrecto.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Tu nombre',
            'email' => 'Correo electrónico',
            'subject' => 'Tipo de falla',
            'body' => 'Descripción del problema',
            'verifyCode' => 'Código de verificación',
        ];
    }

    /**
     * Envía el reporte de falla al correo de soporte/administración.
     */
    public function sendEmail(
        MailerInterface $mailer,
        string $adminEmail,
        string $senderEmail,
        string $senderName,
    ): bool {
        $fecha = date('d/m/Y H:i:s');
        $descripcionHtml = nl2br(htmlspecialchars($this->body, ENT_QUOTES, 'UTF-8'));

        return $mailer->compose()
            ->setTo($adminEmail)
            ->setFrom([$senderEmail => $senderName])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject("[Reporte de Falla UNO] {$this->subject}")
            ->setHtmlBody("
                <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px;'>
                    <h2 style='color: #7d0d30; border-bottom: 2px solid #7d0d30; padding-bottom: 8px;'>
                        Nuevo Reporte de Falla - Sistema UNO
                    </h2>
                    <p><b>Reportado por:</b> {$this->name} ({$this->email})</p>
                    <p><b>Tipo de falla:</b> {$this->subject}</p>
                    <p><b>Fecha y hora:</b> {$fecha}</p>
                    <div style='background: #fcf8f9; border-left: 4px solid #7d0d30; padding: 12px 16px; margin-top: 12px;'>
                        <b>Descripción del problema:</b><br><br>
                        {$descripcionHtml}
                    </div>
                </div>
            ")
            ->send();
    }

    /**
     * Guarda el reporte en la base de datos.
     */
    public function guardarReporte(): bool
    {
        $reporte = new ReporteFalla();
        $reporte->user_id = Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id;
        $reporte->nombre = $this->name;
        $reporte->email = $this->email;
        $reporte->categoria = $this->subject;
        $reporte->descripcion = $this->body;
        $reporte->estado = ReporteFalla::ESTADO_PENDIENTE;

        return $reporte->save();
    }
}