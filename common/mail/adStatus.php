<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ad */

$statusText = '';
switch ($model->status) {
    case \common\models\Ad::STATUS_ACTIVE:
        $statusText = 'активно';
        break;
    case \common\models\Ad::STATUS_INACTIVE:
        $statusText = 'неактивно';
        break;
    case \common\models\Ad::STATUS_MODERATION:
        $statusText = 'на модерации';
        break;
    case \common\models\Ad::STATUS_DELETED:
        $statusText = 'удалено';
        break;
}
?>

<div class="ad-status">
    <h2>Уважаемый <?= Html::encode($model->user ? $model->user->username : 'пользователь') ?>!</h2>

    <p>Статус вашего объявления <b><?= Html::encode($model->title) ?></b> был изменен.</p>

    <p>Текущий статус: <b><?= $statusText ?></b></p>

    <?php if ($model->status == \common\models\Ad::STATUS_ACTIVE): ?>
        <p>Ваше объявление прошло модерацию и теперь доступно для просмотра всем пользователям.</p>
    <?php elseif ($model->status == \common\models\Ad::STATUS_INACTIVE): ?>
        <p>Ваше объявление было отклонено модератором. Пожалуйста, проверьте его содержимое на соответствие правилам сайта и попробуйте разместить снова.</p>
    <?php endif; ?>

    <p>Вы можете просмотреть свое объявление, перейдя по ссылке:</p>

    <p><?= Html::a('Просмотреть объявление', Yii::$app->params['frontendUrl'] . '/ad/' . $model->slug) ?></p>

    <p>С уважением,<br>Команда <?= Yii::$app->name ?></p>
</div>
