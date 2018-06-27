<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '更新返点信息' ;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wrapper wrapper-content ">
    <div class="ibox-content">
        <hr>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
