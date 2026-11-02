<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Modelo para gestionar los días, horarios y cupos habilitados por Servicios Escolares.
 */
class DisponibilidadCita extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'disponibilidad_cita';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['fecha', 'hora_inicio', 'hora_fin', 'cupo_maximo'], 'required'],
            [['fecha', 'hora_inicio', 'hora_fin', 'created_at', 'updated_at'], 'safe'],
            [['cupo_maximo'], 'integer', 'min' => 1],
            [['fecha'], 'unique', 'message' => 'Ya existe una configuración de cupo para este día.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha Habilitada',
            'hora_inicio' => 'Hora de Inicio',
            'hora_fin' => 'Hora de Cierre',
            'cupo_maximo' => 'Cupo Máximo de Citas',
            'created_at' => 'Fecha de Registro',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Calcula cuántos lugares quedan disponibles para este día específico.
     * Resta las citas ya agendadas al cupo máximo. Si es 0, el calendario se pondrá en rojo.
     */
    public function getLugaresDisponibles()
    {
        $citasOcupadas = CitaEntrega::find()
            ->where(['fecha' => $this->fecha])
            ->andWhere(['!=', 'estado', 'Cancelada'])
            ->count();
            
        return $this->cupo_maximo - $citasOcupadas;
    }
}