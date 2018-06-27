<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'theme')->textInput() ?>

    <?= $form->field($model, 'type' )->dropDownList([2=>'全部',1=>'用户类型',0=>'层级']) ?>

    <?= $form->field($model, 'objects')->textInput() ?>

    <?= $form->field($model, 'review')->radioList([1=>'是',0=>'否'])?>

    <?= $form->field($model, 'status')->radioList([1=>'启用',0=>'禁用'])?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
