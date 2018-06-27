<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '添加活动';
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
?>
<div class="wrapper wrapper-content">
<div class="ibox-content">
    <hr>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
