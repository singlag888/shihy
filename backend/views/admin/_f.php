<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  $form->field($model,'last')->textInput()->label('充值金额 (负数为扣款)'); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '充值' : '充值', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
