<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '更新活动: ' . ' ' . $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->content, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wrapper wrapper-content ">
    <div class="ibox-content">
        <h1><?= Html::encode($this->title) ?></h1>
        <hr>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
