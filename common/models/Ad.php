<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class Ad extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_MODERATION = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    public static function tableName()
    {
        return '{{%ads}}';
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
            [['title', 'description', 'price', 'category_id', 'user_id', 'status', 'slug'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['category_id', 'user_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'price' => 'Цена',
            'category_id' => 'Категория',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'slug' => 'Slug',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->generateSlug();
            }
            return true;
        }
        return false;
    }

    protected function generateSlug()
    {
        $baseSlug = Inflector::transliterate($this->title);
        $baseSlug = mb_strtolower($baseSlug, 'UTF-8');
        $baseSlug = preg_replace('/[^a-z0-9-]/', '-', $baseSlug);
        $baseSlug = preg_replace('/-+/', '-', $baseSlug);
        $baseSlug = trim($baseSlug, '-');
        
        $randomString = $this->generateRandomString(7);
        $this->slug = $baseSlug . '-' . $randomString;
    }

    protected function generateRandomString($length = 7)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
} 