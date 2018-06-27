<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = '神榜娱乐';
?>
<style>
    .navbar-header{
        width: 94%;
    }
    .menuBox{
        width: 80%;
        height: 60px;
        background-color: #f3f3f4;
        margin-left: 50px;
        padding: 0px 30px;
        overflow: hidden;
    }
    .menuBox>ul{
        height: 60px;
        margin-bottom: 0px;
        padding: 0px;
    }
    .menuBox>ul li{
        height: 60px;
        list-style: none;
        float: left;
        padding: 0px 25px;
        background-color: #f3f3f4;
        color: #2b3d4c;
        line-height: 60px;
    }
    .menuBox>ul li>img{
        float: left;
        margin-top: 15px;
        margin-right: 10px;
    }
    .menuBox>ul li>p{
        float: left;
    }
    .myMessage{
        float: left;
        width: 20px;
        height: 20px;
        background-color: red;
        border-radius: 10px;
        text-align: center;
        line-height: 20px;
        margin-top: 13px;
        margin-left: 3px;
        color: white;
    }
</style>
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="img-circle" src="<?=$touxiang?>" style="width: 100px;" /></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold"><?= Yii::$app->user->identity->username ?></strong></span>
                                <span class="text-muted text-xs block"><?= $user_info?><b class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem" href="<?=Url::to(['user/avatar'])?>">修改头像</a>
                            <li><a class="J_menuItem" href="<?=Url::to(['user/update','id'=>Yii::$app->user->id])?>">修改密码</a>
                            </li>
                            <li><a class="J_menuItem" href="<?= Url::toRoute(['user/update','id'=>Yii::$app->user->identity->getId()])?>">个人资料</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?=Url::toRoute('site/logout')?>">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">菜单
                    </div>
                </li>
                <?php  foreach($menu as $v1):?>
                    <?php $data = json_decode($v1['data'], true);?>
                    <li><!--一级菜单-->
                        <a href="#">
                            <i class="<?=$data['icon']?>"></i>
                            <span class="nav-label"><?= $v1['name']?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <?php if(array_key_exists('_child',$v1)):?>
                            <ul class="nav nav-second-level">
                                <?php  foreach($v1['_child'] as $v2):?>
                                    <?php $data2 = json_decode($v2['data'], true);?>
                                    <?php if(array_key_exists('_child',$v2)):?>
                                        <li><!--二级菜单-->
                                            <a href="#">
                                                <?php if($data2['icon']):?><i class="<?=$data2['icon']?>"></i><?php endif;?><?= $v2['name']?>
                                                <span class="fa arrow"></span>
                                            </a>
                                            <?php if(!empty($v2['_child'])):?>
                                                <ul class="nav nav-third-level collapse">
                                                    <?php  foreach($v2['_child'] as $v3):?>
                                                        <li><!--三级菜单-->
                                                            <a class="J_menuItem" href="<?= Url::toRoute($v3['route']);?>" data-index="0"><?= $v3['name']?></a>
                                                        </li>
                                                    <?php endforeach;?>
                                                </ul>
                                            <?php  endif;?>
                                        </li>
                                    <?php else:?>
                                        <li><!--二级菜单-->
                                            <a class="J_menuItem" href="<?= Url::toRoute($v2['route']);?>" data-index="0"><?= $v2['name']?></a>
                                        </li>
                                    <?php  endif;?>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>

                    </li>
                <?php endforeach;?>

            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <div class="menuBox">
                        <ul>
                            <li>
                                <img src="/img/index/signal.png" alt="">
                                <p>您的IP：<span><?=Yii::$app->request->userIP?></span></p>
                            </li>
                            <li>
                                <img src="/img/index/bankCard.png" alt="">
                                <p>
                                    <a class="J_menuItem" href="/index.php?r=bank-list%2Findex" data-index="0">入款银行</a></p>
                            </li>
                            <li>
                                <img src="/img/index/pay.png" alt="">
                                <p>
                                    <a class="J_menuItem" href="/index.php?r=recharge%2Findex" data-index="0">第三方支付</a></p>
                                <div class="myMessage"><?=$model1?></div>
                            </li>
                            <li>
                                <img src="/img/index/money.png" alt="">
                                    <p><a class="J_menuItem" href="/index.php?r=recharge%2Findex1" data-index="0">出款</a></p>
                                <div class="myMessage"><?=$total1?></div>
                            </li>
                            <li>
                                <img src="/img/index/integral.png" alt="">
                                <p>积分</p>
                            </li>
                            <li>
                                <img src="/img/index/volume.png" alt="">
                                <p>入款提示音</p>
                            </li>
                            <li>
                                <img src="/img/index/volume.png" alt="">
                                <p>出款提示音</p>
                            </li>
                            <li>
                                <img src="/img/index/username.png" alt="">
                                <div class="myMessage"><?=$register?></div>
                            </li>
                        </ul>
                    </div>

                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> 主题
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="<?=Url::toRoute('site/logout')?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::toRoute('index/welcome')?>" frameborder="0" data-id="index_v1.html" seamless></iframe>
        </div>
    
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">

            <ul class="nav nav-tabs navs-3">

                <li class="active">
                    <a data-toggle="tab" href="#tab-1">
                        <i class="fa fa-gear"></i> 主题
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3> <i class="fa fa-comments-o"></i> 主题设置</h3>
                        <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，布局设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title">主题设置</div>
                        <div class="setings-item">
                            <span>收起左侧菜单</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>固定顶部</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                    <label class="onoffswitch-label" for="fixednavbar">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                                <span>
                        固定宽度
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="title">皮肤选择</div>
                        <div class="setings-item black-bg skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             默认皮肤
                         </a>
                    </span>
                        </div>
                        <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            蓝色主题
                        </a>
                    </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            黄色/紫色主题
                        </a>
                    </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!--右侧边栏结束-->
</div>
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="js/plugins/layer/layer.min.js"></script>
<script src="js/hplus.min.js?v=4.1.0"></script>
<script type="text/javascript" src="js/contabs.min.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>