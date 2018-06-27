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
            <div class="panel-heading">玩法名称</div>
            <div class="panel-body">
                <?=$model->name?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">购买时间</h3>
            </div>
            <div class="panel-body">
                <?=$model->created_time?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">购买号码</h3>
            </div>
            <div class="panel-body">
                <?=$model->num?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">注数</h3>
            </div>
            <div class="panel-body">
                <?=$model->note?>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">倍数</h3>
            </div>
            <div class="panel-body">
                <?=$model->bs?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">金额</h3>
            </div>
            <div class="panel-body">
                <?=$model->buy_price?>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">用户ID</h3>
            </div>
            <div class="panel-body">
                <?=$model->admini_id?>
            </div>
        </div>
    </div>
</div>


