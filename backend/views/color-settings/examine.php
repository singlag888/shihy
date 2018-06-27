<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '查看内容: ' . ' ' . $model->theme;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->theme, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wrapper wrapper-content ">
    <div class="ibox-content">
        <h1><?= Html::encode($this->title) ?></h1>
        <hr>
        <a class="btn btn-info glyphicon glyphicon-repeat" href="<?=\yii\helpers\Url::to(['letter/index'])?>">返回</a>
        <div>&emsp;</div>
        <div class="panel panel-info">
            <div class="panel-heading">彩种名称</div>
            <div class="panel-body">
                <?=$model->stauts?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">彩种状态</h3>
            </div>
            <div class="panel-body">
                <?=$model->status?>
            </div>
        </div>


    </div>
</div>


