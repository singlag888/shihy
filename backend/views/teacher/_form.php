<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'name')->textInput() ?>

    <?= $form->field($model,'age')->textInput(['type'=>'number'])?>

    <?= $form->field($model,'sex')->radioList([1=>'男',2=>'女']) ?>

    <?= $form->field($model,'phone')->textInput(['type'=>'number']) ?>

    <?= $form->field($model,'email')->textInput(['type'=>'email']) ?>

    <?= $form->field($model,'life')->textInput(['type'=>'number'])?>

    <?= $form->field($model,'work_unit')->textInput()?>

    <?= $form->field($model,'education')->radioList([1=>'初中',2=>'高中',3=>'大专',4=>'本科',5=>'研究生',6=>'硕士',7=>'博士',8=>'其他'])?>

    <?= $form->field($model,'domicile')->textInput()?>

    <?= $form->field($model,'certificate')->hiddenInput()?>

    <?= $form->field($model,'balance')->textInput(['type'=>'number'])?>
    <?= $form->field($model,'status')->radioList([1=>'待审核',2=>'通过审核',3=>'不合格'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
