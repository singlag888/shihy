<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '修改第三方入款账号';
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
