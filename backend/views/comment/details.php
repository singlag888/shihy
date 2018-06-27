<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>评论详情</title>

    <!-- Bootstrap -->
<!--    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
<!--    <link href="../css/style.css" rel="stylesheet">-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main{margin-bottom: 60px;}
        .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
    </style>
</head>
<body>
    <!--导航结束-->
    <a class="btn btn-info glyphicon glyphicon-home" href="<?=\yii\helpers\Url::to(['comment/index'])?>">返回</a>
    <div class="container-fluid text-center">
        <div class="blank"></div>
        <h3 class="noticeDetailTitle"><strong><?=$comment->process_star?></strong>场地</h3>
        <div class="noticeDetailInfo">评论者:<?=isset($member->name)?$member->name:'无'?></div>
        <div class="noticeDetailInfo">对老师:<?=isset($teacher->name)?$teacher->name:'无'?> :<?=$comment->teacher_star?></div>
        <div class="noticeDetailInfo">评分:<?=$comment->the_sum?>分</div>
        <div class="noticeDetailInfo">评论时间：<?=date("Y-m-d H:i",$comment->created_at)?></div>
        <div class="noticeDetailInfo">修改时间：<?=isset($comment->updated_at)?date("Y-m-d H:i",$comment->updated_at):'暂无修改时间'?></div>
        <h4 class="text-danger">场地评论:<?=$comment->field_star?></h4>
        <div class="noticeDetailContent">
            <?=isset($comment->content)?$comment->content:'暂无详情'?>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="../jquery-1.11.2.min.js"></script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="../bootstrap/js/bootstrap.min.js"></script>-->
</body>
</html>