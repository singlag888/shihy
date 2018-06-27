<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '查看内容: ' . ' ' . $letter->theme;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $letter->theme, 'url' => ['view', 'id' => $letter->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wrapper wrapper-content ">
    <div class="ibox-content">
        <h1><?= Html::encode($this->title) ?></h1>
        <hr>
        <a class="btn btn-info glyphicon glyphicon-repeat" href="<?=\yii\helpers\Url::to(['letter/index'])?>">返回</a>
        <div>&emsp;</div>
        <div class="panel panel-info">
            <div class="panel-heading">主题</div>
            <div class="panel-body">
                <?=$letter->theme?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">发送人</h3>
            </div>
            <div class="panel-body">
                <?=$letter->users->username?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">接受人</h3>
            </div>
            <div class="panel-body">
                <?=$letter->user->username?>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">内容</h3>
            </div>
            <div class="panel-body">
                <?=$letter->content?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">发送时间</h3>
            </div>
            <div class="panel-body">
                <?=date('Y-m-d H:i:s',$letter->sending_time)?>
            </div>
        </div>
    </div>
</div>


