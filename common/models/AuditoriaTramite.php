<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auditoria_tramite".
 *
 * @property int $id
 * @property int $tramite_id
 * @property int $usuario_responsable_id
 * @property int|null $etapa_anterior_id
 * @property int $etapa_nueva_id
 * @property string|null $motivo_cambio
 * @property string|null $ip_origen
 * @property string $created_at
 *
 * @property TramiteTitulacion $tramite
 */
class AuditoriaTramite extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auditoria_tramite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['etapa_anterior_id', 'motivo_cambio', 'ip_origen'], 'default', 'value' => null],
            [['tramite_id', 'usuario_responsable_id', 'etapa_nueva_id', 'created_at'], 'required'],
            [['tramite_id', 'usuario_responsable_id', 'etapa_anterior_id', 'etapa_nueva_id'], 'integer'],
            [['motivo_cambio'], 'string'],
            [['created_at'], 'safe'],
            [['ip_origen'], 'string', 'max' => 45],
            [['tramite_id'], 'exist', 'skipOnError' => true, 'targetClass' => TramiteTitulacion::class, 'targetAttribute' => ['tramite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tramite_id' => 'Tramite ID',
            'usuario_responsable_id' => 'Usuario Responsable ID',
            'etapa_anterior_id' => 'Etapa Anterior ID',
            'etapa_nueva_id' => 'Etapa Nueva ID',
            'motivo_cambio' => 'Motivo Cambio',
            'ip_origen' => 'Ip Origen',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Tramite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTramite()
    {
        return $this->hasOne(TramiteTitulacion::class, ['id' => 'tramite_id']);
    }

}
