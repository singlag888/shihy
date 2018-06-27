<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model,'name')->textInput();?>
    <?= $form->field($model,'username')->textInput();?>
    <?= $form->field($model,'bank')->textInput();?>
    <?= $form->field($model,'price')->textInput();?>
    <?= $form->field($model,'status')->radioList([1=>'开奖',2=>'未开奖']);?>
    <?= $form->field($model,'before_price')->textInput();?>
    <?= $form->field($model,'last_price')->textInput();?>
    <?= $form->field($model,'time')->textInput();?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
