<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '系统消息列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <p>
                <?= Html::a('添加系统消息', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="table_id_example" class="table table-striped table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <td class="text-center">#</td>
                                        <td class="text-center">商户名称/商户别名</td>
                                        <td class="text-center">商户号</td>
                                        <td class="text-center">可充值</td>
                                        <td class="text-center">可下发</td>
                                        <td class="text-center">所属分类</td>
                                        <td class="text-center">入款次数/入款金额</td>
                                        <td class="text-center">排序</td>
                                        <td class="text-center">操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $v): ?>
                                        <tr id="<?=$v->id?>">
                                            <td><?=$v->id ?></td>
                                            <td><?=$v->name ?> / <?=$v->alias ?></td>
                                            <td><?=$v->number ?></td>
                                            <td><?=$v->in_status ==1?'<span class="label label-info">是</span>':'<span class="label label-danger">否</span>' ?></td>
                                            <td><?=$v->out_status ==1?'<span class="label label-info">是</span>':'<span class="label label-danger">否</span>' ?></td>
                                            <td><?=$v->type ?></td>
                                            <td></td>
                                            <td><?=$v->num ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['three-payment/update','id'=>$v['id']])?>"><i class="fa fa-edit"></i>编辑</a> <a id="del" class="btn btn-default btn-xs">删除</a>
                                            </td>
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
$url = \yii\helpers\Url::to(['three-payment/delete']);
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
    })
JS;
$this->registerJs($js);
?>
