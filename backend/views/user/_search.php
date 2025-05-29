<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

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
            <?= $form->field($model, 'phone') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList([
                '' => 'Все',
                User::STATUS_INACTIVE => 'Неактивен',
                User::STATUS_ACTIVE => 'Активен',
                User::STATUS_BLOCKED => 'Заблокирован',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'fname') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'lname') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'address') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
