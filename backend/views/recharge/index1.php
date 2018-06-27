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
                                        <td class="text-center">用户编码</td>
                                        <td class="text-center">用户名</td>
                                        <td class="text-center">出款金额</td>
                                        <td class="text-center">银行账号</td>
                                        <td class="text-center">开户名</td>
                                        <td class="text-center">出款方式</td>
                                        <td class="text-center">手续费</td>
                                        <td class="text-center">已达打码量</td>
                                        <td class="text-center">需达打码量</td>
                                        <td class="text-center">近一个月充值/取款/彩票盈亏</td>
                                        <td class="text-center">申请时间</td>
                                        <td class="text-center">审核时间</td>
                                        <td class="text-center">审核人</td>
                                        <td class="text-center">状态</td>
                                        <td class="text-center">操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  foreach ($model as $v): ?>
                                        <tr id="<?=$v->admin->id?>">
                                            <td class="shy"><?=$v->id ?></td>
                                            <td><?php
                                                $admin = \backend\models\Admin::findOne(['id'=>$v->admin->id]);
                                                echo $admin->username;
                                                ?></td>
                                            <td><?=$v->type==1?'代理':'会员' ?></td>
                                            <td><?=$v->admin->id ?></td>
                                            <td><?=$v->username ?></td>
                                            <td><?=$v->price?></td>
                                            <td><?=$v->banks->bank_number?></td>
                                            <td><?=$v->admin->username?></td>
                                            <td><?=$v->banks->bank_name?></td>
                                            <td><?=$v->transaction_charge?></td>
                                            <td>0.000</td>
                                            <td>0.000</td>
                                            <td class="price"><a>点击查看</a></td>
                                            <td><?=date("Y-m-d H:i:s",$v->created_time)?></td>
                                            <td id="time"><?=date("Y-m-d H:i:s",$v->updated_time)?></td>
                                            <td ></td>
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
$url = \yii\helpers\Url::to(['recharge/statu']);
$url1 = \yii\helpers\Url::to(['recharge/price']);
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
          var id = $(this).closest('tr').index();
          var tr = $(this).closest('tr');
          id= $(".shy").eq(id).text();
          $.get('$url',{id:id,status:1},function(data) {
                   if(data==-1){
                    tr.find("#st").text('审核失败');
                    tr.find("#normal").attr({'id':'111','disabled':'disabled'});
                    tr.find("#normal1").attr({'id':'111','disabled':'disabled'});
                     tr.find("#time").text(date('Y-m-d H:i:s'));
              }else{
                   tr.find("#st").text('审核通过');
                    tr.find("#normal").attr({'id':'111','disabled':'disabled'});
                    tr.find("#normal1").attr({'id':'111','disabled':'disabled'});
                     tr.find("#time").text(data);
                   }
    
          })
        });
          $("#table_id_example").on('click','#normal1',function() {
          var id = $(this).closest('tr').index();
          id= $(".shy").eq(id).text();
          var tr = $(this).closest('tr');
           console.log(id);
          $.get('$url',{id:id,status:-1},function(data) {
                   tr.find("#st").text('审核失败');
                    tr.find("#normal").attr({'id':'111','disabled':'disabled'});
                    tr.find("#normal1").attr({'id':'111','disabled':'disabled'});
                     tr.find("#time").text(data);
          })
        });
          
          
        $("#table_id_example").on('click','.price',function() {
                 var tr = $(this).closest('tr');
               var that=$(this);
              $.get('$url1',{id:tr.attr('id')},function(data) {
               var mydata=JSON.parse(data);
               that.eq().prevObject[0].innerHTML='<p style="text-align:center">'+mydata.recharge+'/'+mydata.withdrawals
    +'/'+mydata.lottery+'</p>'
          }) 
        });
JS;
$this->registerJs($js);
?>
