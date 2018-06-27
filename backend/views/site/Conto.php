<?php
/**
 * @var \yii\web\View
 */
$this->registerJsFile('@web/js/js/html5.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/js/TweenLite.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/js/EasePack.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/js/rAF.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/js/demo-1.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/css/css/normalize.css');
$this->registerCssFile('@web/css/css/demo.css');
$this->registerCssFile('@web/css/css/component.css');
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
</head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header">
            <canvas id="demo-canvas"></canvas>
            <div class="logo_box">
                <h3>欢迎你</h3>
                <form action="#" name="f" method="post">
                    <?php $form = \yii\bootstrap\ActiveForm::begin(['id' => 'login-form']); ?>
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'style'=>'text-align: center'])->label('用户名') ?>
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <?= $form->field($model, 'password')->passwordInput(['style'=>'text-align: center'])->label('密码') ?>
                    </div>
                    <div class="mb2"> <?= \yii\bootstrap\Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?></div>
                    <?php \yii\bootstrap\ActiveForm::end(); ?>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>