<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

/**
 * This is the model class for table "ad".
 *
 * @property int $id
 * @property string $title Заголовок
 * @property string|null $description Описание
 * @property float $price Цена
 * @property int $user_id ID пользователя
 * @property int $category_id ID категории
 * @property int $status Статус объявления
 * @property int $created_at Дата создания
 * @property int $updated_at Дата обновления
 * @property string $slug
 * @property string $location
 * @property string $phone
 * @property string $email
 *
 * @property Category $category
 * @property User $user
 * @property AdImage[] $images
 * @property AdLog[] $adLogs
 */
class Ad extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_MODERATION = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    /**
     * @var UploadedFile[] файлы изображений для загрузки
     */
    public $imageFiles;

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
            [['title', 'description', 'price', 'category_id', 'user_id', 'status'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['category_id', 'user_id', 'status'], 'integer'],
            [['title', 'slug', 'location', 'phone', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['slug'], 'unique'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10],
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
            'location' => 'Локация',
            'phone' => 'Телефон',
            'email' => 'Email',
            'imageFiles' => 'Изображения',
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

    /**
     * Gets query for [[AdImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(AdImage::class, ['ad_id' => 'id']);
    }

    /**
     * Gets query for [[AdLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdLogs()
    {
        return $this->hasMany(AdLog::class, ['ad_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
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

    /**
     * @return array статусы объявлений
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Активно',
            self::STATUS_INACTIVE => 'Неактивно',
            self::STATUS_MODERATION => 'На модерации',
            self::STATUS_DELETED => 'Удалено',
        ];
    }

    /**
     * @return string текстовое представление статуса
     */
    public function getStatusText()
    {
        $statuses = self::getStatusList();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : 'Неизвестно';
    }

    /**
     * Получить URL изображения (первого или плейсхолдер)
     * @return string
     */
    public function getImageUrl()
    {
        $image = $this->images[0] ?? null;
        if ($image && $image->path) {
            return Yii::getAlias('@web/uploads/' . $image->path);
        }
        return Yii::getAlias('@web/images/no-image.png');
    }
}