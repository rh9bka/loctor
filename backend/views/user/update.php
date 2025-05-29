<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование пользователя: ' . ($model->getFullName() ?: ('Пользователь #' . $model->id));
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->getFullName() ?: ('Пользователь #' . $model->id)), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Изменение пользователя: ' . ($model->getFullName() ?: ('Пользователь #' . $model->id));
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName() ?: ('Пользователь #' . $model->id), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
