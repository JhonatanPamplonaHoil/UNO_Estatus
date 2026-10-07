<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\User;

/**
 * This is the model class for table "tramite_titulacion".
 *
 * @property int $id
 * @property string $folio
 * @property int $user_id
 * @property int $carrera_id
 * @property int $modalidad_id
 * @property int $etapa_actual_id
 * @property string $matricula
 * @property float|null $promedio_general
 * @property string|null $observaciones
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuditoriaTramite[] $auditoriaTramites
 * @property Carrera $carrera
 * @property CitaEntrega $citaEntrega
 * @property EtapaTramite $etapaActual
 * @property ModalidadTitulacion $modalidad
 * @property User $user
 */
class TramiteTitulacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tramite_titulacion';
    }

    /**
     * Comportamientos para registro automático de fechas
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promedio_general', 'observaciones'], 'default', 'value' => null],
            // Obligatorios que debe ingresar el usuario en el formulario
            [['carrera_id', 'modalidad_id', 'matricula'], 'required', 'message' => '{attribute} no puede estar vacío.'],
            [['user_id', 'carrera_id', 'modalidad_id', 'etapa_actual_id'], 'integer'],
            [['promedio_general'], 'number'],
            [['observaciones'], 'string'],
            // Campos gestionados por el sistema / controlador
            [['created_at', 'updated_at', 'folio', 'etapa_actual_id', 'user_id'], 'safe'],
            [['folio'], 'string', 'max' => 50],
            [['matricula'], 'string', 'max' => 20],
            [['folio'], 'unique'],
            [['carrera_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::class, 'targetAttribute' => ['carrera_id' => 'id']],
            [['modalidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModalidadTitulacion::class, 'targetAttribute' => ['modalidad_id' => 'id']],
            [['etapa_actual_id'], 'exist', 'skipOnError' => true, 'targetClass' => EtapaTramite::class, 'targetAttribute' => ['etapa_actual_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'folio' => 'Folio',
            'user_id' => 'Usuario',
            'carrera_id' => 'Carrera',
            'modalidad_id' => 'Modalidad de Titulación',
            'etapa_actual_id' => 'Etapa Actual',
            'matricula' => 'Matrícula',
            'promedio_general' => 'Promedio General',
            'observaciones' => 'Observaciones / Tema',
            'created_at' => 'Fecha de Registro',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[AuditoriaTramites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuditoriaTramites()
    {
        return $this->hasMany(AuditoriaTramite::class, ['tramite_id' => 'id']);
    }

    /**
     * Gets query for [[Carrera]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrera()
    {
        return $this->hasOne(Carrera::class, ['id' => 'carrera_id']);
    }

    /**
     * Gets query for [[CitaEntrega]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCitaEntrega()
    {
        return $this->hasOne(CitaEntrega::class, ['tramite_id' => 'id']);
    }

    /**
     * Gets query for [[EtapaActual]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEtapaActual()
    {
        return $this->hasOne(EtapaTramite::class, ['id' => 'etapa_actual_id']);
    }

    /**
     * Gets query for [[Modalidad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModalidad()
    {
        return $this->hasOne(ModalidadTitulacion::class, ['id' => 'modalidad_id']);
    }
}