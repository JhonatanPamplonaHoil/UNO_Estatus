<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the
 * search form about `common\models\User`.
 */
class UserSearch extends User
{
    /** @var string */
    public $rolNombre;

    /** @var string */
    public $estadoNombre;

    /** @var int|string */
    public $perfilId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rol_id', 'estado_id'], 'integer'],
            [['username', 'apellido_paterno', 'apellido_materno', 'email', 'created_at', 'updated_at', 'rolNombre',
                'estadoNombre', 'perfilId'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'userIdLink' => [
                    'asc' => ['user.id' => SORT_ASC],
                    'desc' => ['user.id' => SORT_DESC],
                    'label' => 'User',
                ],
                'userLink' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => 'User',
                ],
                'apellido_paterno' => [
                    'asc' => ['user.apellido_paterno' => SORT_ASC],
                    'desc' => ['user.apellido_paterno' => SORT_DESC],
                    'label' => 'Apellido paterno',
                ],
                'apellido_materno' => [
                    'asc' => ['user.apellido_materno' => SORT_ASC],
                    'desc' => ['user.apellido_materno' => SORT_DESC],
                    'label' => 'Apellido materno',
                ],
                'perfilLink' => [
                    'asc' => ['perfil.id' => SORT_ASC],
                    'desc' => ['perfil.id' => SORT_DESC],
                    'label' => 'Perfil',
                ],
                'rolNombre' => [
                    'asc' => ['rol.rol_nombre' => SORT_ASC],
                    'desc' => ['rol.rol_nombre' => SORT_DESC],
                    'label' => 'Rol',
                ],
                'estadoNombre' => [
                    'asc' => ['estado.estado_nombre' => SORT_ASC],
                    'desc' => ['estado.estado_nombre' => SORT_DESC],
                    'label' => 'Estado',
                ],
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'label' => 'Created At',
                ],
                'email' => [
                    'asc' => ['email' => SORT_ASC],
                    'desc' => ['email' => SORT_DESC],
                    'label' => 'Email',
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['rol'])
                ->joinWith(['estado'])
                ->joinWith(['perfil']);
            return $dataProvider;
        }

        $this->addSearchParameter($query, 'id');
        $this->addSearchParameter($query, 'username', true);
        $this->addSearchParameter($query, 'apellido_paterno', true);
        $this->addSearchParameter($query, 'apellido_materno', true);
        $this->addSearchParameter($query, 'email', true);
        $this->addSearchParameter($query, 'rol_id');
        $this->addSearchParameter($query, 'estado_id');
        $this->addSearchParameter($query, 'created_at');
        $this->addSearchParameter($query, 'updated_at');

        $query->joinWith(['rol' => function ($q) {
            $q->andFilterWhere(['=', 'rol.rol_nombre', $this->rolNombre]);
        }])
        ->joinWith(['estado' => function ($q) {
            $q->andFilterWhere(['=', 'estado.estado_nombre', $this->estadoNombre]);
        }])
        ->joinWith(['perfil' => function ($q) {
            $q->andFilterWhere(['=', 'perfil.id', $this->perfilId]);
        }]);

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

        $attribute = "user.$attribute";

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}