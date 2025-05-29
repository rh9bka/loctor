<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\User;
use common\models\Ad;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Ad */
/* @var $form yii\widgets\ActiveForm */

$statusOptions = [
    Ad::STATUS_ACTIVE => 'Активно',
    Ad::STATUS_INACTIVE => 'Неактивно',
    Ad::STATUS_MODERATION => 'На модерации',
    Ad::STATUS_DELETED => 'Удалено',
];
?>

<div class="ad-form">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

                    <?= $form->field($model, 'category_id')->dropDownList(
                        ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                        ['prompt' => 'Выберите категорию']
                    ) ?>

                    <?= $form->field($model, 'user_id')->dropDownList(
                        ArrayHelper::map(User::find()->limit(100)->all(), 'id', 'lname'),
                        ['prompt' => 'Выберите пользователя']
                    ) ?>

                    <?= $form->field($model, 'status')->dropDownList($statusOptions) ?>
                </div>
            </div>

            <?= $form->field($model, 'imageFiles[]')->widget(FileInput::class, [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showRemove' => true,
                    'maxFileCount' => 10,
                ],
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
