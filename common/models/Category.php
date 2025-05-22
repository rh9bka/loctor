<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%categories}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['parent_id'], 'integer'],
            [['svg'], 'string'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'parent_id' => 'Родительская категория',
            'svg' => 'SVG',
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }

    public function getAds()
    {
        return $this->hasMany(Ad::class, ['category_id' => 'id']);
    }
} 