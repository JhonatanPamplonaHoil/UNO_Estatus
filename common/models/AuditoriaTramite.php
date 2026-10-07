<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modelo para la bitácora inmutable auditoria_tramite
 *
 * @property int $id
 * @property int $tramite_id
 * @property int|null $etapa_anterior_id
 * @property int $etapa_nueva_id
 * @property int $usuario_responsable_id
 * @property string|null $motivo_cambio
 * @property string|null $ip_origen
 * @property string $created_at
 */
class AuditoriaTramite extends ActiveRecord
{
    public static function tableName()
    {
        return 'auditoria_tramite';
    }

    public function rules()
    {
        return [
            [['tramite_id', 'etapa_nueva_id', 'usuario_responsable_id'], 'required'],
            [['tramite_id', 'etapa_anterior_id', 'etapa_nueva_id', 'usuario_responsable_id'], 'integer'],
            [['motivo_cambio'], 'string'],
            [['created_at'], 'safe'],
            [['ip_origen'], 'string', 'max' => 45],
        ];
    }

    public function getTramite()
    {
        return $this->hasOne(TramiteTitulacion::class, ['id' => 'tramite_id']);
    }

    public function getEtapaAnterior()
    {
        return $this->hasOne(EtapaTramite::class, ['id' => 'etapa_anterior_id']);
    }

    public function getEtapaNueva()
    {
        return $this->hasOne(EtapaTramite::class, ['id' => 'etapa_nueva_id']);
    }

    public function getUsuarioResponsable()
    {
        return $this->hasOne(User::class, ['id' => 'usuario_responsable_id']);
    }
}