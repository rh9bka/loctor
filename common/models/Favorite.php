<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Favorite extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%favorites}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'ad_id'], 'required'],
            [['user_id', 'ad_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ad::class, 'targetAttribute' => ['ad_id' => 'id']],
            [['user_id', 'ad_id'], 'unique', 'targetAttribute' => ['user_id', 'ad_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'ad_id' => 'Объявление',
            'created_at' => 'Дата добавления',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAd()
    {
        return $this->hasOne(Ad::class, ['id' => 'ad_id']);
    }
} 