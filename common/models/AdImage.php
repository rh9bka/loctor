<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "ad_images".
 *
 * @property int $id
 * @property int $ad_id ID объявления
 * @property string $filename Имя файла
 * @property int $created_at Время создания
 * @property int $updated_at Время обновления
 *
 * @property Ad $ad
 */
class AdImage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ad_images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'filename'], 'required'],
            [['ad_id', 'created_at', 'updated_at'], 'integer'],
            [['filename'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ad::class, 'targetAttribute' => ['ad_id' => 'id']],
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
            'filename' => 'Имя файла',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
     * Сохраняет загруженное изображение
     * 
     * @param UploadedFile $file
     * @return bool успешно ли сохранено изображение
     */
    public function saveImage($file)
    {
        $this->filename = Yii::$app->security->generateRandomString() . '.' . $file->extension;

        // Создаем директорию, если её нет
        $uploadPath = Yii::getAlias('@frontend/web/uploads/ads/' . $this->ad_id);
        FileHelper::createDirectory($uploadPath);

        if ($file->saveAs($uploadPath . '/' . $this->filename)) {
            return $this->save(false);
        }

        return false;
    }

    /**
     * Возвращает URL изображения
     * 
     * @return string URL изображения
     */
    public function getImageUrl()
    {
        return Yii::getAlias('@web/uploads/ads/' . $this->ad_id . '/' . $this->filename);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Удаляем файл с сервера
        $filePath = Yii::getAlias('@frontend/web/uploads/ads/' . $this->ad_id . '/' . $this->filename);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
