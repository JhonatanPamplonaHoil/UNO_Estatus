<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Perfil;

/**
 * PerfilSearch represents the model behind the search form about `frontend\models\Perfil`.
 */
class PerfilSearch extends Perfil
{
    /** @var string */
    public $generoNombre;

    /** @var int|string */
    public $genero_id;

    /** @var int|string */
    public $userId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'genero_id'], 'integer'],
            [['nombre', 'apellido', 'fecha_nacimiento', 'generoNombre', 'userId'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'genero_id' => 'Género',
            'apellido' => 'Apellidos',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Perfil::find()->joinWith(['user', 'genero']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'nombre',
                'apellido' => [
                    'asc' => ['user.apellido_paterno' => SORT_ASC, 'user.apellido_materno' => SORT_ASC],
                    'desc' => ['user.apellido_paterno' => SORT_DESC, 'user.apellido_materno' => SORT_DESC],
                    'label' => 'Apellidos',
                ],
                'fecha_nacimiento',
                'generoNombre' => [
                    'asc' => ['genero.genero_nombre' => SORT_ASC],
                    'desc' => ['genero.genero_nombre' => SORT_DESC],
                    'label' => 'Género',
                ],
                'perfilIdLink' => [
                    'asc' => ['perfil.id' => SORT_ASC],
                    'desc' => ['perfil.id' => SORT_DESC],
                    'label' => 'ID',
                ],
                'userLink' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => 'User',
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addSearchParameter($query, 'id');
        $this->addSearchParameter($query, 'nombre', true);
        $this->addSearchParameter($query, 'fecha_nacimiento', true);
        $this->addSearchParameter($query, 'genero_id');
        $this->addSearchParameter($query, 'created_at');
        $this->addSearchParameter($query, 'updated_at');
        $this->addSearchParameter($query, 'user_id');

        // Filtro de apellidos buscando en la tabla user y como respaldo en perfil.apellido
        if (trim((string) $this->apellido) !== '') {
            $apellido = trim((string) $this->apellido);
            $query->andFilterWhere([
                'or',
                ['like', 'user.apellido_paterno', $apellido],
                ['like', 'user.apellido_materno', $apellido],
                ['like', "CONCAT(COALESCE(user.apellido_paterno, ''), ' ', COALESCE(user.apellido_materno, ''))", $apellido],
                ['like', 'perfil.apellido', $apellido],
            ]);
        }

        // Filtro por nombre de género y por ID de usuario
        $query->andFilterWhere(['like', 'genero.genero_nombre', $this->generoNombre]);
        $query->andFilterWhere(['=', 'user.id', $this->userId]);

        return $dataProvider;
    }

    protected function addSearchParameter($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;

        if (trim((string) $value) === '') {
            return;
        }

        $attribute = "perfil.$attribute";

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}