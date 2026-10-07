<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cita_entrega".
 *
 * @property int $id
 * @property int $tramite_id
 * @property string $fecha
 * @property string $hora
 * @property string|null $estado
 * @property string|null $comentarios
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TramiteTitulacion $tramite
 */
class CitaEntrega extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cita_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comentarios'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Agendada'],
            [['tramite_id', 'fecha', 'hora', 'created_at', 'updated_at'], 'required'],
            [['tramite_id'], 'integer'],
            [['fecha', 'hora', 'created_at', 'updated_at'], 'safe'],
            [['estado'], 'string', 'max' => 20],
            [['comentarios'], 'string', 'max' => 255],
            [['tramite_id'], 'unique'],
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
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'estado' => 'Estado',
            'comentarios' => 'Comentarios',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
