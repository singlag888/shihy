<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'num')->textInput() ?>
    <?= $form->field($model, 'note')->textInput() ?>
    <?= $form->field($model, 'bs')->textInput()?>
    <?= $form->field($model, 'buy_price')->textInput()?>
    <?= $form->field($model, 'admini_id')->textInput()?>
    <?= $form->field($model, 'created_time')->textInput()?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
