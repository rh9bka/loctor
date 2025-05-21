<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Sms extends ActiveRecord
{
    const TYPE_REGISTRATION = 1;
    const TYPE_PASSWORD_RESET = 2;
    const TYPE_ADVERTISEMENT = 3;

    const STATUS_PENDING = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_EXPIRED = 2;

    public static function tableName()
    {
        return '{{%sms}}';
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
            [['user_id', 'type', 'msg', 'phone'], 'required'],
            [['user_id', 'phone', 'status', 'type'], 'integer'],
            [['msg'], 'string', 'min' => 1, 'max' => 100],
            [['type'], 'in', 'range' => [self::TYPE_REGISTRATION, self::TYPE_PASSWORD_RESET, self::TYPE_ADVERTISEMENT]],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_VERIFIED, self::STATUS_EXPIRED]],
            [['expired_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'type' => 'Тип',
            'msg' => 'Сообщение',
            'phone' => 'Телефон',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'expired_at' => 'Истекает',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function isExpired()
    {
        return time() > $this->expired_at;
    }

    public function verify()
    {
        $this->status = self::STATUS_VERIFIED;
        return $this->save();
    }

    public function expire()
    {
        $this->status = self::STATUS_EXPIRED;
        return $this->save();
    }

    public function getTypeLabel()
    {
        $types = [
            self::TYPE_REGISTRATION => 'Регистрация',
            self::TYPE_PASSWORD_RESET => 'Сброс пароля',
            self::TYPE_ADVERTISEMENT => 'Реклама',
        ];
        return $types[$this->type] ?? 'Неизвестный тип';
    }
} 