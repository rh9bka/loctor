<?php

namespace backend\models;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch представляет модель поиска для `common\models\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $phone;
    public $email;
    public $fname;
    public $lname;
    public $status;
    public $created_at;
    public $updated_at;
    public $address;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'phone', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'fname', 'lname', 'address'], 'safe'],
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
