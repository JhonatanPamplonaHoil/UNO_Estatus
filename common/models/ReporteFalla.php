<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $nombre
 * @property string $email
 * @property string $categoria
 * @property string $descripcion
 * @property string $estado
 * @property string|null $notas_admin
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User|null $user
 */
class ReporteFalla extends ActiveRecord
{
    public const ESTADO_PENDIENTE = 'Pendiente';
    public const ESTADO_EN_REVISION = 'En revisión';
    public const ESTADO_RESUELTO = 'Resuelto';

    public static function tableName(): string
    {
        return 'reporte_falla';
    }

    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE => self::ESTADO_PENDIENTE,
            self::ESTADO_EN_REVISION => self::ESTADO_EN_REVISION,
            self::ESTADO_RESUELTO => self::ESTADO_RESUELTO,
        ];
    }

    public function rules(): array
    {
        return [
            [['nombre', 'email', 'categoria', 'descripcion'], 'required'],
            [['user_id'], 'integer'],
            [['descripcion', 'notas_admin'], 'string'],
            [['nombre', 'categoria'], 'string', 'max' => 150],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['estado'], 'in', 'range' => array_keys(self::getEstados())],
            [['estado'], 'default', 'value' => self::ESTADO_PENDIENTE],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'Folio',
            'user_id' => 'Usuario',
            'nombre' => 'Reportado por',
            'email' => 'Correo electrónico',
            'categoria' => 'Tipo de falla',
            'descripcion' => 'Descripción del problema',
            'estado' => 'Estado',
            'notas_admin' => 'Notas de seguimiento (Admin)',
            'created_at' => 'Fecha de reporte',
            'updated_at' => 'Última actualización',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}