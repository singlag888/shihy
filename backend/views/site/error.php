<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <h1><?= Html::encode($this->title) ?></h1>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Stylish Error Page template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login sign up Responsive web template, SmartPhone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design"
    />
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- Custom Theme files -->
    <link href="/error/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!-- //Custom Theme files -->
    <!--pop-up-box-->
    <link href="/error/css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
    <!--//pop-up-box-->
    <link rel="stylesheet" href="/error/css/font-awesome.css">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- web font -->
    <!--<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>-->
    <!--<link href="//fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"-->
    <!--rel="stylesheet">-->
    <!-- //web font -->
</head>

<body>
<div class="main-agileits">
    <!-- content -->
    <div class="mainw3-agileinfo form">
        <div class="agileits-subscribe">
            <div class="img-po-agile">
                <img src="/error/images/mmmmk.png" alt="">
            </div>
            <div class="subscribe-w3lsbottom">
                <div class="w3l-page">
                    <i class="fa fa-spinner" aria-hidden="true"></i>加载中
                </div>
                <h6>对不起,<?= nl2br(Html::encode($message)) ?>,请输入正确的地址</h6>
                <a class="play-icon popup-with-zoom-anim" href="">点击跳转</a>
            </div>
            <div class="img-po-agile posi-sgile-2">
                <img src="/error/images/mmmmk2.png" alt="">
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- //content -->
</div>

<!-- Popup 模态框的形式   <a class="play-icon popup-with-zoom-anim" href="#small-dialog1">点击跳转</a>-->
<!--<div class="main vlcone mfp-hide" id="small-dialog1">
    <h3>Why Are You Waiting..</h3>
    <p>Subscribe to our email newsletter</p>
    <div class="w3ls-form">
        <form action="#" method="post">
            <input type="email" placeholder="Subscribe" name="Subscribe" required="">
            <button class="btn1">
                <i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
        </form>
    </div>
    <div class="agileits-info">
        <h5>(OR)</h5>
    </div>
    <div class="share-icons">
        <a class="facebook" href="#">
            <i class="fa fa-facebook" aria-hidden="true"></i>
        </a>
        <a class="pinterest" href="#">
            <i class="fa fa-pinterest-p" aria-hidden="true"></i>
        </a>
        <a class="twitter" href="#">
            <i class="fa fa-twitter" aria-hidden="true"></i>
        </a>
        <a class="google" href="#">
            <i class="fa fa-google-plus" aria-hidden="true"></i>
        </a>
        <a class="vk" href="#">
            <i class="fa fa-vk" aria-hidden="true"></i>
        </a>
    </div>
</div>-->
<!-- copyright -->
<div class="w3copyright-agile">
    <p>宝贝驿站官网 &copy; <a href="http://www.quxdui.com/" target="_blank" title="点击跳转">www.baidu.com</a>
    </p>
</div>
<!-- jquery -->
<script src="/error/js/jquery-2.1.4.min.js"></script>
<!-- //jquery -->
<!-- popup modal (for signin & signup)-->
<script src="/error/js/jquery.magnific-popup.js"></script>
<script>
    $(document).ready(function () {
        $('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });

    });
</script>

</body>

</html>