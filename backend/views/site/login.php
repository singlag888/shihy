<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login</title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php $this->beginBody() ?>
<div class="myBox">
    <div class="loginContent">
        <form action="<?=\yii\helpers\Url::to(['site/login'])?>" method="post">

        <div class="contentItem">
            <div class="itemImg">
                <img src="img/username.png" alt="">
            </div>
            <div class="itemInput">
                <?=\yii\helpers\Html::activeInput('text',$model,'username',['placeholder'=>'Username'])?>
            </div>
        </div>
        <div class="contentItem">
            <div class="itemImg">
                <img src="img/password.png" alt="">
            </div>
            <div class="itemInput">
                <?=\yii\helpers\Html::activeInput('password',$model,'password',['placeholder'=>'Password'])?>
            </div>
        </div>
        <div class="contentItem">
            <div class="checkCode">
                <input id="inputCode" type="text" placeholder="输入验证码">
            </div>
            <div class="keyCode"></div>
            <p class="checkText">验证码不正确！</p>
        </div>
        <div class="rememberMe">
            <?=\yii\helpers\Html::activeInput('checkbox',$model,'rememberMe')?>&nbsp;记住我
<!--            <input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]">&nbsp;记住我-->
        </div>
        <div class="loginBtn">
            <button type="submit">登录</button>
        </div>
        </form>

    </div>
</div>
<script src="js/login.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>