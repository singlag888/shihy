<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '站内信';
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
                                <table id="table_id_example" class="table table-striped table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">创建用户编号</th>
                                        <th class="text-center">创建用户账号</th>
                                        <th class="text-center">契约用户编号</th>
                                        <th class="text-center">契约用户账号</th>
                                        <th class="text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<!--                                    --><?php //foreach($model as $vo):?>
                                        <tr id="">
<!--                                            <td>--><?//=$vo['id']?><!--</td>-->
<!--                                            <td>--><?//=$vo['username']?><!--</td>-->
<!--                                            <td>--><?//=$vo['total_amount'].'/'.$vo['note'].'/'.$vo['average']?><!--</td>-->
<!--                                            <td>--><?//=$vo['sales_rebate']?><!--</td>-->
<!--                                            <td>--><?//=$vo['winning_amount']?><!--</td>-->
<!--                                            <td>--><?//=$vo['amount']?><!--</td>-->
<!--                                            <td>--><?//=$vo['make_water']?><!--</td>-->
<!--                                            <td>--><?//=$vo['game_transfer']?><!--</td>-->
<!--                                            <td>--><?//=$vo['game_out']?><!--</td>-->
<!--                                            <td>--><?//=$vo['lose_money']?><!--</td>-->
<!--                                            <td>--><?//=$vo['activity']?><!--</td>-->
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['teacher/update',])?>"><i class="fa fa-edit"></i>编辑</a>  <a id="del" class="btn btn-default btn-xs">删除</a></td>
                                        </tr>
<!--                                    --><?php //endforeach;?>
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
//$url = \yii\helpers\Url::to(['letter/delete']);
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
//    $("#table_id_example").on('click','#del',function() {
//      var tr = $(this).closest('tr');
//      if(confirm('是否需要删除此数据,此操作将不可恢复')){
//          $.get('',{id:tr.attr('id')},function() {
//            tr.remove();
//          })
//      } 
//    })
JS;
$this->registerJs($js);
?>
