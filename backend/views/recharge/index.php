<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '提款列表';
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
                                        <td class="text-center">编号</td>
                                        <td class="text-center">直属代理</td>
                                        <td class="text-center">用户层级</td>
                                        <td class="text-center">订单号</td>
                                        <td class="text-center">用户编码</td>
                                        <td class="text-center">用户名</td>
                                        <td class="text-center">付款人</td>
                                        <td class="text-center">充值金额</td>
                                        <td class="text-center">优惠</td>
                                        <td class="text-center">手续费</td>
                                        <td class="text-center">付款方式</td>
                                        <td class="text-center">充值前金额</td>
                                        <td class="text-center">充值后金额</td>
                                        <td class="text-center">收款银行</td>
                                        <td class="text-center">收款账号</td>
                                        <td class="text-center">收款人</td>
                                        <td class="text-center">申请时间</td>
                                        <td class="text-center">审核时间</td>
                                        <td class="text-center">状态</td>
                                        <td class="text-center">操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $v): ?>
                                        <tr id="<?=$v->id?>">
                                            <td><?=$v->id ?></td>
                                            <td><?=$v->admin->username?></td>
                                            <td><?=$v->admin->type==1?'代理':'普通用户'?></td>
                                            <td><?=$v->orders ?></td>
                                            <td><?=$v->admin->id?></td>
                                            <td><?=$v->username ?></td>
                                            <td><?=$v->alias ?></td>
                                            <td><?=$v->price?></td>
                                            <td>0.000</td>
                                            <td>0.000</td>

                                            <td><?=$v->type?></td>
                                            <td><?=$v->front_price ?></td>
                                            <td><?=$v->after_price ?></td>
                                            <td><?=$v->bank_id ?></td>
                                            <td> </td>
                                            <td> </td>

                                            <td><?=date("Y-m-d H:i:s",$v->created_time)?></td>
                                            <td id="time"><?=date("Y-m-d H:i:s",$v->updated_time)?></td>
                                            <td id="st"><?php
                                                if($v['status'] == 1){
                                                    echo "审核通过";
                                                }elseif($v['status'] == -1){
                                                    echo "审核失败";
                                                }elseif($v['status'] == 0){
                                                    echo "审核中";
                                                }
                                                ?></td>
                                            <td><a class="btn btn-info btn-xs" <?=$v['status']==0?"id='normal'":"id='22'"?><?=$v['status']==0?'':'disabled'?> >审核通过</a>&emsp;
                                                <a class="btn btn-warning btn-xs" <?=$v['status']==0?'':'disabled'?>  <?=$v['status']==0?"id='normal1'":"id='11'"?>>审核失败</a></td>

                                        </tr>
                                    <?php endforeach; ?>
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
$url = \yii\helpers\Url::to(['recharge/status']);
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
      $.get('$url',{id:tr.attr('id'),status:1},function(data) {
                tr.find("#st").text('审核通过');
                tr.find("#normal").attr({'id':'111','disabled':'disabled'});
                tr.find("#normal1").attr({'id':'111','disabled':'disabled'});
                 tr.find("#time").text(data);
      })
    });
      $("#table_id_example").on('click','#normal1',function() {
      var tr = $(this).closest('tr');
      $.get('$url',{id:tr.attr('id'),status:-1},function(data) {
                tr.find("#st").text('审核失败');
                  tr.find("#normal").attr({'id':'111','disabled':'disabled'});
                tr.find("#normal1").attr({'id':'111','disabled':'disabled'});
                tr.find("#time").text(data);
      })
    });
   
JS;
$this->registerJs($js);
?>
