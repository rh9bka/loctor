<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput() ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>
            <?php if ($model->isNewRecord): ?>
                <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Пароль') ?>
            <?php else: ?>
                <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Пароль (оставьте пустым, если не хотите менять)') ?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList([
                User::STATUS_INACTIVE => 'Неактивный',
                User::STATUS_ACTIVE => 'Активный',
                User::STATUS_BLOCKED => 'Заблокирован',
            ]) ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'lat')->textInput(['type' => 'number', 'step' => '0.000001']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'lng')->textInput(['type' => 'number', 'step' => '0.000001']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
