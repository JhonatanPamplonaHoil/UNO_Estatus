<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TramiteTitulacion[] $tramiteTitulacions
 */
class Carrera extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'codigo', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre'], 'string', 'max' => 150],
            [['codigo'], 'string', 'max' => 20],
            [['codigo'], 'unique'],
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
            'codigo' => 'Codigo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[TramiteTitulacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTramiteTitulacions()
    {
        return $this->hasMany(TramiteTitulacion::class, ['carrera_id' => 'id']);
    }

}
