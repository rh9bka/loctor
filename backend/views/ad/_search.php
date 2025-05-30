<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Ad;
use yii\helpers\ArrayHelper;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\AdSearch */
/* @var $form yii\widgets\ActiveForm */

$statusOptions = [
    Ad::STATUS_ACTIVE => 'Активно',
    Ad::STATUS_INACTIVE => 'Неактивно',
    Ad::STATUS_MODERATION => 'На модерации',
    Ad::STATUS_DELETED => 'Удалено',
];
?>

<div class="ad-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                ['prompt' => 'Все категории']
            ) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList(
                $statusOptions,
                ['prompt' => 'Все статусы']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
