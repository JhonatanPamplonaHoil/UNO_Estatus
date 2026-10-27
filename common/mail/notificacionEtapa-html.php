<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\TramiteTitulacion $tramite */
/** @var string $etapaAnterior */
/** @var string $etapaNueva */
/** @var string $motivo */

?>
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
    <div style="background-color: #0d6efd; color: #ffffff; padding: 20px; text-align: center;">
        <h2 style="margin: 0;">Sistema de Gestión de Titulación (UNO)</h2>
        <p style="margin: 5px 0 0 0; font-size: 14px;">Actualización de Estado de Trámite</p>
    </div>
    
    <div style="padding: 25px;">
        <p>Estimado(a) <strong><?= Html::encode($user->username) ?></strong>,</p>
        
        <p>Le informamos que el Departamento de Servicios Escolares ha registrado un cambio en el estatus de su trámite de titulación.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f8f9fa;">
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold; width: 35%;">Folio:</td>
                <td style="padding: 10px; border: 1px solid #dee2e6; color: #0d6efd; font-weight: bold;"><?= Html::encode($tramite->folio) ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Matrícula:</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;"><?= Html::encode($tramite->matricula) ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Etapa Anterior:</td>
                <td style="padding: 10px; border: 1px solid #dee2e6; color: #6c757d;"><?= Html::encode($etapaAnterior) ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Nueva Etapa:</td>
                <td style="padding: 10px; border: 1px solid #dee2e6; color: #198754; font-weight: bold;"><?= Html::encode($etapaNueva) ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Observaciones / Motivo:</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;"><?= Html::encode($motivo) ?></td>
            </tr>
        </table>
        
        <p>Puede consultar el avance y los detalles actualizados ingresando a su portal en el apartado <strong>"Mis Trámites"</strong>.</p>
        
        <p style="margin-top: 30px; font-size: 13px; color: #777;">
            Atentamente,<br>
            <strong>Departamento de Servicios Escolares</strong><br>
            Universidad del Oriente
        </p>
    </div>
</div>