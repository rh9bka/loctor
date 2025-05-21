<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Ad;
use common\models\Favorite;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class AdsController extends BaseController
{
    public $modelClass = 'common\models\Ad';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Настраиваем аутентификацию
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpHeaderAuth::class,
            'header' => 'Authorization',
            'pattern' => '/^Bearer\s+(.*?)$/',
            'optional' => ['index', 'view'], // Эти действия доступны без аутентификации
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $query = Ad::find()->where(['status' => Ad::STATUS_ACTIVE]);
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function actionMyAds()
    {
        $status = Yii::$app->request->get('status', 'active');
        $query = Ad::find()
            ->where(['user_id' => Yii::$app->user->id]);

        // Маппинг текстовых статусов на числовые значения
        $statusMap = [
            'active' => Ad::STATUS_ACTIVE,
            'inactive' => Ad::STATUS_INACTIVE,
            'moderation' => Ad::STATUS_MODERATION,
            'deleted' => Ad::STATUS_DELETED,
        ];

        // Проверяем, что передан корректный статус
        if (!isset($statusMap[$status])) {
            throw new \yii\web\BadRequestHttpException('Некорректный статус. Допустимые значения: ' . implode(', ', array_keys($statusMap)));
        }

        // Применяем фильтр по статусу
        $query->andWhere(['status' => $statusMap[$status]]);

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);
    }

    public function actionView($id)
    {
        $model = Ad::findOne(['id' => $id, 'status' => Ad::STATUS_ACTIVE]);
        if ($model === null) {
            throw new NotFoundHttpException('Объявление не найдено');
        }
        return $model;
    }

    public function actionViewBySlug($slug)
    {
        $model = Ad::findOne(['slug' => $slug, 'status' => Ad::STATUS_ACTIVE]);
        if ($model === null) {
            throw new NotFoundHttpException('Объявление не найдено');
        }
        return $model;
    }

    public function actionCreate()
    {
        $model = new Ad();
        $model->load(Yii::$app->request->getBodyParams(), '');
        $model->user_id = Yii::$app->user->id;

        if ($model->save()) {
            return $model;
        }

        return $model->errors;
    }

    public function actionUpdate($id)
    {
        $model = Ad::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('Объявление не найдено');
        }

        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('У вас нет прав на редактирование этого объявления');
        }

        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save()) {
            return $model;
        }

        return $model->errors;
    }

    public function actionDelete($id)
    {
        $model = Ad::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('Объявление не найдено');
        }

        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('У вас нет прав на удаление этого объявления');
        }

        $model->status = Ad::STATUS_DELETED;
        return $model->save();
    }

    public function actionAddToFavorites($id)
    {
        $ad = Ad::findOne(['id' => $id, 'status' => Ad::STATUS_ACTIVE]);
        if (!$ad) {
            throw new NotFoundHttpException('Объявление не найдено');
        }

        // Проверяем, не добавлено ли уже в избранное
        $exists = Favorite::find()
            ->where(['user_id' => Yii::$app->user->id, 'ad_id' => $id])
            ->exists();

        if ($exists) {
            throw new BadRequestHttpException('Объявление уже в избранном');
        }

        $favorite = new Favorite();
        $favorite->user_id = Yii::$app->user->id;
        $favorite->ad_id = $id;

        if (!$favorite->save()) {
            throw new ServerErrorHttpException('Не удалось добавить в избранное');
        }

        return [
            'success' => true,
            'message' => 'Объявление добавлено в избранное'
        ];
    }

    public function actionRemoveFromFavorites($id)
    {
        $favorite = Favorite::find()
            ->where(['user_id' => Yii::$app->user->id, 'ad_id' => $id])
            ->one();

        if (!$favorite) {
            throw new NotFoundHttpException('Объявление не найдено в избранном');
        }

        if (!$favorite->delete()) {
            throw new ServerErrorHttpException('Не удалось удалить из избранного');
        }

        return [
            'success' => true,
            'message' => 'Объявление удалено из избранного'
        ];
    }

    public function actionFavorites()
    {
        $query = Ad::find()
            ->alias('a')
            ->innerJoin(['f' => Favorite::tableName()], 'f.ad_id = a.id')
            ->where(['f.user_id' => Yii::$app->user->id])
            ->andWhere(['a.status' => Ad::STATUS_ACTIVE]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'f.created_at' => SORT_DESC,
                ]
            ]
        ]);
    }
} 