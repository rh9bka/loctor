<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ad;

/**
 * AdSearch represents the model behind the search form of `common\models\Ad`.
 */
class AdSearch extends Ad
{
    public $date_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'status'], 'integer'],
            [['title', 'slug', 'description', 'location', 'phone', 'email', 'date_range'], 'safe'],
            [['price'], 'number'],
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
        $query = Ad::find();

        // add conditions that should always apply here
        $query->joinWith(['category', 'user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ad.id' => $this->id,
            'ad.user_id' => $this->user_id,
            'ad.category_id' => $this->category_id,
            'ad.price' => $this->price,
            'ad.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ad.title', $this->title])
            ->andFilterWhere(['like', 'ad.slug', $this->slug])
            ->andFilterWhere(['like', 'ad.description', $this->description])
            ->andFilterWhere(['like', 'ad.location', $this->location])
            ->andFilterWhere(['like', 'ad.phone', $this->phone])
            ->andFilterWhere(['like', 'ad.email', $this->email]);

        // Обрабатываем диапазон дат
        if (!empty($this->date_range)) {
            $dates = explode(' - ', $this->date_range);
            if (count($dates) == 2) {
                $query->andFilterWhere(['>=', 'ad.created_at', $dates[0] . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'ad.created_at', $dates[1] . ' 23:59:59']);
            }
        }

        return $dataProvider;
    }
}
