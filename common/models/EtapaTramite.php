<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "etapa_tramite".
 *
 * @property int $id
 * @property int $secuencia
 * @property string $nombre
 * @property string|null $descripcion
 * @property int|null $es_etapa_final
 * @property int|null $permite_cita
 *
 * @property TramiteTitulacion[] $tramiteTitulacions
 */
class EtapaTramite extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'etapa_tramite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['permite_cita'], 'default', 'value' => 0],
            [['secuencia', 'nombre'], 'required'],
            [['secuencia', 'es_etapa_final', 'permite_cita'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['secuencia'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'secuencia' => 'Secuencia',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'es_etapa_final' => 'Es Etapa Final',
            'permite_cita' => 'Permite Cita',
        ];
    }

    /**
     * Gets query for [[TramiteTitulacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTramiteTitulacions()
    {
        return $this->hasMany(TramiteTitulacion::class, ['etapa_actual_id' => 'id']);
    }

}
