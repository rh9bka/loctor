<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Html::encode($model->getFullName() ?: ('Пользователь #' . $model->id));
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status == User::STATUS_ACTIVE): ?>
            <?= Html::a('Заблокировать', ['block', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите заблокировать этого пользователя?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php elseif ($model->status != User::STATUS_ACTIVE): ?>
            <?= Html::a('Активировать', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите активировать этого пользователя?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'phone',
            'email:email',
            'fname',
            'lname',
            'address',
            'lat',
            'lng',
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
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d.m.Y H:i:s', $model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return date('d.m.Y H:i:s', $model->updated_at);
                },
            ],
        ],
    ]) ?>
    <h3 class="mt-4">Объявления пользователя</h3>
    <?php if (!empty($model->ads)): ?>
        <ul>
            <?php foreach ($model->ads as $ad): ?>
                <li><?= Html::a(Html::encode($ad->title), ['/ad/view', 'id' => $ad->id]) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>У пользователя нет объявлений.</p>
    <?php endif; ?>
</div>
