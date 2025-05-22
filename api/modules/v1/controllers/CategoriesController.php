<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

class CategoriesController extends BaseController
{
    public $modelClass = 'common\models\Category';

    public function actions()
    {
        $actions = parent::actions();
        // Отключаем все стандартные действия
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionTree()
    {
        $categories = Category::find()->orderBy('parent_id, name')->all();

        $tree = [];
        $items = [];

        foreach ($categories as $category) {
            $items[$category->id] = $category->toArray();
            $items[$category->id]['children'] = [];
        }

        foreach ($items as $id => $item) {
            if (isset($item['parent_id']) && $item['parent_id'] !== null && isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$items[$id];
            } else {
                $tree[] = &$items[$id];
            }
        }

        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $tree;
    }

    // Оставляем методы, но они не будут доступны из-за actions()
    public function actionIndex()
    {
        $parent_id = Yii::$app->request->get('parent_id');

        $query = Category::find();

        if ($parent_id === null) {
            // Получаем категории верхнего уровня (у которых parent_id = null)
            $query->where(['parent_id' => null]);
        } else {
            // Получаем дочерние категории для указанного parent_id
            $query->where(['parent_id' => $parent_id]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50, // Или другое подходящее значение
            ],
        ]);
    }

    public function actionView($id)
    {
        $model = Category::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Категория не найдена');
        }
        return $model;
    }

    public function actionCreate()
    {
        $model = new Category();
        $model->load(Yii::$app->request->post(), '');
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException('Не удалось создать категорию');
        }
    }

    public function actionUpdate($id)
    {
        $model = Category::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Категория не найдена');
        }
        $model->load(Yii::$app->request->post(), '');
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException('Не удалось обновить категорию');
        }
    }

    public function actionDelete($id)
    {
        $model = Category::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Категория не найдена');
        }
        if ($model->delete()) {
            return ['success' => true];
        } else {
            throw new ServerErrorHttpException('Не удалось удалить категорию');
        }
    }
} 