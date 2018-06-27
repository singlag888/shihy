<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '活动设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <p>
                <?= Html::a('添加活动', ['settings-create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="table_id_example" class="table table-striped table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">编号</th>
                                        <th class="text-center">活动主题</th>
                                        <th class="text-center">活动类型</th>
                                        <th class="text-center">活动对象</th>
                                        <th class="text-center">需审核</th>
                                        <th class="text-center">状态</th>
                                        <th class="text-center">开始时间</th>
                                        <th class="text-center">结束时间</th>
                                        <th class="text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($activitys as $vo):?>
                                        <tr id="<?=$vo['id']?>">
                                            <td><?=$vo['id']?></td>
                                            <td><?=$vo['theme']?></td>
                                            <td><?=$vo['type']?></td>
                                            <td><?php if($vo['objects']==1){echo "用户类型";}elseif($vo['objects']==2){echo '全部';}else{echo '层';}?></td>
                                            <td><?=$vo['review']==1?'是':'否'?></td>
                                            <td id="st"><?=$vo['status']==1?'<span class="btn btn-info btn-xs">启用</span>':'<span class="btn btn-danger btn-xs">禁止</span>'?></td>
                                            <td></td>
                                            <td></td>
                                            <td><a id="status" class="btn btn-danger btn-xs">启用</a>&emsp;<a id="status1" class="btn btn-danger btn-xs">禁用</a></td>
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
$url = \yii\helpers\Url::to(['activity/status']);
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
      $("#table_id_example").on('click','#status',function() {
      var tr = $(this).closest('tr');
      $.get('$url',{id:tr.attr('id'),status:1},function(data) {
          tr.find("#st").html('<span class="btn btn-info btn-xs">启用</span>');
      })
    });
      
        $("#table_id_example").on('click','#status1',function() {
      var tr = $(this).closest('tr');
      $.get('$url',{id:tr.attr('id'),status:-1},function(data) {
          tr.find("#st").html('<span class="btn btn-danger btn-xs">禁止</span>');
      })
    });
JS;
$this->registerJs($js);
?>
