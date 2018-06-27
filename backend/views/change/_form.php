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
    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'num')->textInput() ?>
    <?= $form->field($model, 'number')->textInput() ?>
    <?= $form->field($model, 'price')->textInput() ?>
    <?= $form->field($model, 'game_type')->textInput()?>
    <?= $form->field($model, 'change_amount')->textInput()?>
    <?= $form->field($model, 'amount')->textInput()?>
    <?= $form->field($model, 'type')->textInput()?>
    <?= $form->field($model, 'remark')->textInput()?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
