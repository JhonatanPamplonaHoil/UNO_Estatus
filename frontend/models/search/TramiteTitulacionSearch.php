<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TramiteTitulacion;

/**
 * TramiteTitulacionSearch represents the model behind the search form of `common\models\TramiteTitulacion`.
 */
class TramiteTitulacionSearch extends TramiteTitulacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'carrera_id', 'modalidad_id', 'etapa_actual_id'], 'integer'],
            [['folio', 'matricula', 'observaciones', 'created_at', 'updated_at'], 'safe'],
            [['promedio_general'], 'number'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = TramiteTitulacion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'carrera_id' => $this->carrera_id,
            'modalidad_id' => $this->modalidad_id,
            'etapa_actual_id' => $this->etapa_actual_id,
            'promedio_general' => $this->promedio_general,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'matricula', $this->matricula])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
