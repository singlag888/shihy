<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '彩票注单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <form class="navbar-form navbar-left" role="search" method="post">
                                    <div class="form-group">
                                        <select class="form-control" name="search">
                                            <?php foreach ($aa as $item):?>
                                            <option value="<?=$item['id']?>" <?=$search==$item['id']?'selected':''?>><?=$item['name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default glyphicon glyphicon-search">搜索</button>
                                </form>
                                <table id="table_id_example" class="table table-striped table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">玩法名称</th>
                                        <th class="text-center">返点赔率</th>
                                        <th class="text-center">彩票类型</th>
                                        <th class="text-center">状态</th>
                                        <th class="text-center">限制注数<br/>(空表示不限)</th>
                                        <th class="text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($model as $vo):?>
                                        <tr id="<?=$vo['id']?>" search="<?=$vo->big_color_id?>">
                                            <td><?=$vo->bonus.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vo->name?></td>
                                            <td><?=$vo->bonuss?></td>
                                            <td><?=$vo->nam->name?></td>
                                            <td id="st"><?php if ($vo->status==0){echo '维护中';}elseif ($vo->status==1){echo '正常';}else{echo '停售';} ?></td>
                                            <td><?=$vo->note?></td>
                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['cqssc/update','id'=>$vo['id']])?>"><i class="fa fa-edit"></i>修改</a> <a class="btn btn-info btn-xs" id="normal">正常</a> <a class="btn btn-warning btn-xs" id="normal1">停售</a> <a class="btn btn-danger btn-xs" id="normal2">维护中</a></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/media/js/jquery.dataTables.js', [
    'depends' => \yii\web\JqueryAsset::className()
]);
$url = \yii\helpers\Url::to(['cqssc/status']);
$js =
    <<<JS
     $(document).ready( function () {
    $('#table_id_example').DataTable();
} );
    $('#table_id_example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
     }
 });
        $("#table_id_example").on('click','#normal',function() {
      var tr = $(this).closest('tr');
      $.get('$url',{id:tr.attr('id'),status:1},function() {
                tr.find("#st").text('正常')
      })
    });
      $("#table_id_example").on('click','#normal1',function() {
      var tr = $(this).closest('tr');
      $.get('$url',{id:tr.attr('id'),status:-1},function() {
               tr.find("#st").text('停售')
      })
    });
    $("#table_id_example").on('click','#normal2',function() {
         var tr = $(this).closest('tr');
          $.get('$url',{id:tr.attr('id'),status:0},function() {
               tr.find("#st").text('维护中')
         })
    });
JS;
$this->registerJs($js);
?>
