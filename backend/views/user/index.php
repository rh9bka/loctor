<?php
<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'phone',
            'email:email',
            'fname',
            'lname',
            [
                'attribute' => 'status',
                'filter' => [
                    User::STATUS_INACTIVE => 'Неактивный',
                    User::STATUS_ACTIVE => 'Активный',
                    User::STATUS_BLOCKED => 'Заблокирован'
                ],
                'value' => function ($model) {
                    switch ($model->status) {
                        case User::STATUS_INACTIVE:
                            return 'Неактивный';
                        case User::STATUS_ACTIVE:
                            return 'Активный';
                        case User::STATUS_BLOCKED:
                            return 'Заблокирован';
                        default:
                            return 'Неизвестно';
                    }
                }
            ],
            'created_at:datetime',
            //'updated_at',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'verification_token',
            //'address',
            //'lat',
            //'lng',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'phone',
            'email:email',
            'fname',
            'lname',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    switch ($model->status) {
                        case User::STATUS_INACTIVE:
                            return 'Неактивен';
                        case User::STATUS_ACTIVE:
                            return 'Активен';
                        case User::STATUS_BLOCKED:
                            return 'Заблокирован';
                        default:
                            return 'Неизвестно';
                    }
                },
                'filter' => [
                    User::STATUS_INACTIVE => 'Неактивен',
                    User::STATUS_ACTIVE => 'Активен',
                    User::STATUS_BLOCKED => 'Заблокирован',
                ],
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d.m.Y H:i:s', $model->created_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {block} {activate}',
                'buttons' => [
                    'block' => function ($url, $model) {
                        if ($model->status == User::STATUS_ACTIVE) {
                            return Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', ['block', 'id' => $model->id], [
                                'title' => 'Заблокировать',
                                'data-confirm' => 'Вы уверены, что хотите заблокировать этого пользователя?',
                                'data-method' => 'post',
                            ]);
                        }
                        return '';
                    },
                    'activate' => function ($url, $model) {
                        if ($model->status != User::STATUS_ACTIVE) {
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['activate', 'id' => $model->id], [
                                'title' => 'Активировать',
                                'data-confirm' => 'Вы уверены, что хотите активировать этого пользователя?',
                                'data-method' => 'post',
                            ]);
                        }
                        return '';
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
