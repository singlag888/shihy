<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '账号黑名单';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="wrapper wrapper-content">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                </p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table id="table_id_example" class="table
table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td class="text-center">用户编号</td>
                                            <td class="text-center">用户名</td>
                                            <td class="text-center">上级账号</td>
                                            <td class="text-center">余额</td>
                                            <td class="text-center">注册时间</td>
                                            <td class="text-center">最后登陆时间</td>
                                            <td class="text-center">最近登陆IP</td>
                                            <td class="text-center">状态</td>
                                            <td class="text-center">操作</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($model as $v): ?>
                                            <tr id="<?= $v['id'] ?>" class="text-center">
                                                <td><?= $v['id'] ?></td>
                                                <td><?= $v['username'] ?></td>
                                                <td><?= $v->admins->username ?></td>
                                                <td><?= $v->price ?></td>
                                                <td><?= date('Y-m-d H:i:s',$v->created_time) ?></td>
                                                <td><?= date('Y-m-d H:i:s',$v->login_time) ?></td>
                                                <td><?= $v->login_ip ?></td>
                                                <td id="status"><?php
                                                    if($v['status']==1){
                                                        echo '启用';
                                                    }elseif ($v['status']==-1){
                                                        echo '禁用';
                                                    } ?></td>
                                                <td><a class="btn btn-info btn-xs" id="normal">启用</a></td>
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
$url = \yii\helpers\Url::to(['admin/shy']);
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
         var status = tr.find("#status").text();
         console.debug(status);
      $.get('$url',{id:tr.attr('id'),status:status},function(data) {
               if(data == 1){
                    tr.find("#status").text('启用')
               }else if(data == -1){
                   tr.find("#status").text('禁用')
               }
      })
    });
JS;
$this->registerJs($js);
?>