<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  $form->field($model,'username')->textInput(); ?>

    <?= $form->field($model,'type')->radioList([1=>'代理',0=>'会员',2=>'试玩账号']); ?>

    <?= $form->field($model,'max')->textInput()?>

    <?= $form->field($model,'min')->textInput()?>

      <?= $form->field($model,'password_login')->passwordInput()?>
  
      <?= $form->field($model,'password_pay')->passwordInput()?>
    <?= $form->field($model,'status')->radioList([1=>'启用',-1=>'禁止'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
