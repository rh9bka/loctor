<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Ad;

/* @var $this yii\web\View */
/* @var $model common\models\Ad */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Объявления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$statusLabels = [
    Ad::STATUS_ACTIVE => '<span class="badge badge-success">Активно</span>',
    Ad::STATUS_INACTIVE => '<span class="badge badge-secondary">Неактивно</span>',
    Ad::STATUS_MODERATION => '<span class="badge badge-warning">На модерации</span>',
    Ad::STATUS_DELETED => '<span class="badge badge-danger">Удалено</span>',
];
?>
<div class="ad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status !== Ad::STATUS_DELETED): ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить это объявление?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>

        <?php if ($model->status === Ad::STATUS_MODERATION): ?>
            <?= Html::a('Одобрить', ['approve', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите одобрить это объявление?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Отклонить', ['reject', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите отклонить это объявление?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'slug',
            'description:ntext',
            'price:currency',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) use ($statusLabels) {
                    return $statusLabels[$model->status] ?? $model->status;
                },
            ],
            [
                'attribute' => 'category_id',
                'value' => function($model) {
                    return $model->category ? $model->category->name : 'Не указана';
                },
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return $model->user ? $model->user->lname : 'Неизвестный пользователь';
                },
            ],
            'location',
            'phone',
            'email:email',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php if (!empty($model->images)): ?>
    <div class="ad-images mt-4">
        <h3>Изображения</h3>
        <div class="row">
            <?php foreach ($model->images as $image): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="<?= $image->getImageUrl() ?>" class="card-img-top" alt="<?= Html::encode($model->title) ?>">
                    <div class="card-body text-center">
                        <?= Html::a('Удалить', ['delete-image', 'id' => $image->id], [
                            'class' => 'btn btn-sm btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить это изображение?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="ad-history mt-4">
        <h3>История изменений</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Пользователь</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($model->adLogs)): ?>
                    <?php foreach ($model->adLogs as $log): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDatetime($log->created_at) ?></td>
                        <td><?= $log->user ? $log->user->lname : 'Система' ?></td>
                        <td><?= Html::encode($log->action) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">История изменений пуста</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
