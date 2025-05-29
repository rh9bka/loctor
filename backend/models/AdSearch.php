<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ad;

/**
 * AdSearch represents the model behind the search form of `common\models\Ad`.
 */
class AdSearch extends Model
{
    public $id;
    public $user_id;
    public $category_id;
    public $status;
    public $title;
    public $slug;
    public $description;
    public $date_range;
    public $price;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'status'], 'integer'],
            [['title', 'slug', 'description', 'date_range'], 'safe'],
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
        $query->joinWith(['category', 'user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ads.id' => $this->id,
            'ads.user_id' => $this->user_id,
            'ads.category_id' => $this->category_id,
            'ads.price' => $this->price,
            'ads.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ads.title', $this->title])
            ->andFilterWhere(['like', 'ads.slug', $this->slug])
            ->andFilterWhere(['like', 'ads.description', $this->description]);

        // Обрабатываем диапазон дат
        if (!empty($this->date_range)) {
            $dates = explode(' - ', $this->date_range);
            if (count($dates) == 2) {
                $query->andFilterWhere(['>=', 'ads.created_at', $dates[0] . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'ads.created_at', $dates[1] . ' 23:59:59']);
            }
        }

        return $dataProvider;
    }
}
