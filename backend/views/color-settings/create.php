<?php

use yii\helpers\Html;
$this->title = '添加彩种';
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox-content">
        <h1><?= Html::encode($this->title) ?></h1>
        <hr>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
