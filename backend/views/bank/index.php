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
                                        <th class="text-center">ID</th>
                                        <th class="text-center">用户名称</th>
                                        <th class="text-center">银行卡名称</th>
                                        <th class="text-center">开户名</th>
                                        <th class="text-center">银行卡卡号</th>
                                        <th class="text-center">开户地址</th>
                                        <th class="text-center">是否锁定</th>
                                        <th class="text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($model as $vo):?>
                                        <tr id="<?=$vo['id']?>">
                                            <td><?=$vo['id']?></td>
                                            <td><?=$vo->admin->username?></td>
                                            <td><?=$vo->bank->name?></td>
                                            <td><?=$vo->username?></td>
                                            <td><?=$vo['bank_number']?></td>
                                            <td><?=$vo['address']?></td>
                                            <td id="status"><?php
                                                if($vo['status']==1){
                                                    echo '已锁定';
                                                }elseif ($vo['status']==-1){
                                                    echo '未锁定';
                                                } ?></td>
                                            <td><a class="btn btn-info btn-xs" href="<?=Url::toRoute(['bank/update','id'=>$vo['id']])?>"><i class="fa fa-edit"></i>修改</a>  <a id="del" class="btn btn-danger btn-xs">删除</a><a class="btn btn-info btn-xs" id="normal">解除/锁定</a>&emsp;</td>
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
$url = \yii\helpers\Url::to(['bank/delete']);
$url1 = \yii\helpers\Url::to(['bank/status']);
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
    $("#table_id_example").on('click','#del',function() {
      var tr = $(this).closest('tr');
      if(confirm('是否需要删除此数据,此操作将不可恢复')){
          $.get('$url',{id:tr.attr('id')},function() {
            tr.remove();
          })
      } 
    });
      $("#table_id_example").on('click','#normal',function() {
          var tr = $(this).closest('tr');
         var status = tr.find("#status").text();
         console.debug(status);
      $.get('$url1',{id:tr.attr('id'),status:status},function(data) {
               if(data == 1){
                    tr.find("#status").text('已锁定')
               }else if(data == -1){
                   tr.find("#status").text('未锁定')
               }
      })
    });
JS;
$this->registerJs($js);
?>
