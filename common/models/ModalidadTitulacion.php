<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "modalidad_titulacion".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int|null $activo
 *
 * @property TramiteTitulacion[] $tramiteTitulacions
 */
class ModalidadTitulacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modalidad_titulacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['activo'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['activo'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[TramiteTitulacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTramiteTitulacions()
    {
        return $this->hasMany(TramiteTitulacion::class, ['modalidad_id' => 'id']);
    }

}
