<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CitaEntrega;

/**
 * CitaEntregaSearch represents the model behind the search form of `common\models\CitaEntrega`.
 */
class CitaEntregaSearch extends CitaEntrega
{
    // Atributo virtual para el buscador combinado
    public $alumnoInfo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tramite_id'], 'integer'],
            [['fecha', 'hora', 'estado', 'comentarios', 'created_at', 'updated_at', 'alumnoInfo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CitaEntrega::find();

        // Conectar las tablas relacionadas para poder buscar en ellas
        $query->joinWith(['tramite.user', 'tramite.carrera']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                // Orden por defecto: las citas más próximas primero
                'defaultOrder' => [
                    'fecha' => SORT_ASC,
                    'hora' => SORT_ASC,
                ]
            ]
        ]);

        // Habilitar el ordenamiento al hacer clic en el encabezado de "Alumno"
        $dataProvider->sort->attributes['alumnoInfo'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Búsqueda exacta para campos base
        $query->andFilterWhere([
            'cita_entrega.id' => $this->id,
            'cita_entrega.tramite_id' => $this->tramite_id,
            'cita_entrega.fecha' => $this->fecha,
            'cita_entrega.hora' => $this->hora,
        ]);

        $query->andFilterWhere(['like', 'cita_entrega.estado', $this->estado]);

        // Búsqueda inteligente (LIKE) para el nombre, matrícula o carrera
        if ($this->alumnoInfo) {
            $query->andFilterWhere(['or',
                ['like', 'user.username', $this->alumnoInfo],
                ['like', 'tramite_titulacion.matricula', $this->alumnoInfo],
                ['like', 'carrera.nombre', $this->alumnoInfo] 
            ]);
        }

        return $dataProvider;
    }
}