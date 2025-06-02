<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model common\models\Ad */

$viewUrl = Url::to(['ad/view', 'slug' => $model->slug], true);
?>

<div class="ad-status-notification">
    <h2>Уведомление о статусе объявления</h2>

    <p>Здравствуйте, <?= Html::encode($model->user->username) ?>!</p>

    <p>Статус вашего объявления <strong>"<?= Html::encode($model->title) ?>"</strong> был изменен администратором.</p>

    <p>Текущий статус: <strong><?php
        switch ($model->status) {
            case \common\models\Ad::STATUS_ACTIVE:
                echo 'Активно';
                break;
            case \common\models\Ad::STATUS_INACTIVE:
                echo 'Отклонено';
                break;
            case \common\models\Ad::STATUS_MODERATION:
                echo 'На модерации';
                break;
            case \common\models\Ad::STATUS_DELETED:
                echo 'Удалено';
                break;
            default:
                echo 'Неизвестно';
        }
    ?></strong></p>

    <?php if ($model->status == \common\models\Ad::STATUS_ACTIVE): ?>
    <p>Ваше объявление теперь доступно для всех пользователей сайта. Вы можете просмотреть его по ссылке:</p>
    <p><?= Html::a($viewUrl, $viewUrl) ?></p>
    <?php elseif ($model->status == \common\models\Ad::STATUS_INACTIVE): ?>
    <p>К сожалению, ваше объявление было отклонено модератором. Возможные причины:</p>
    <ul>
        <li>Нарушение правил сайта</li>
        <li>Недостаточное описание товара/услуги</li>
        <li>Некорректная категория</li>
        <li>Запрещенный товар/услуга</li>
    </ul>
    <p>Вы можете отредактировать объявление и отправить его на повторную модерацию.</p>
    <?php endif; ?>

    <p>Если у вас возникли вопросы, пожалуйста, свяжитесь с нашей службой поддержки.</p>

    <p>С уважением,<br>Команда <?= Yii::$app->name ?></p>
</div>
