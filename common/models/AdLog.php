<?php
<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ad_logs".
 *
 * @property int $id
 * @property int $ad_id ID объявления
 * @property int|null $user_id ID пользователя
 * @property string $action Действие
 * @property int $created_at Время создания
 *
 * @property Ad $ad
 * @property User $user
 */
class AdLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ad_logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'action'], 'required'],
            [['ad_id', 'user_id', 'created_at'], 'integer'],
            [['action'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ad::class, 'targetAttribute' => ['ad_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Объявление',
            'user_id' => 'Пользователь',
            'action' => 'Действие',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[Ad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ad::class, ['id' => 'ad_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ad_log".
 *
 * @property int $id
 * @property int $ad_id
 * @property int|null $user_id
 * @property string $action
 * @property string $created_at
 *
 * @property Ad $ad
 * @property User $user
 */
class AdLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ad_log';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'action'], 'required'],
            [['ad_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['action'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ad::class, 'targetAttribute' => ['ad_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Объявление',
            'user_id' => 'Пользователь',
            'action' => 'Действие',
            'created_at' => 'Дата',
        ];
    }

    /**
     * Gets query for [[Ad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ad::class, ['id' => 'ad_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
