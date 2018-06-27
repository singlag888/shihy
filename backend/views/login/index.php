<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '用户登录记录';
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
                                        <td class="text-center">ID</td>
                                        <td class="text-center">用户编号</td>
                                        <td class="text-center">用户名</td>
                                        <td class="text-center">登录时间</td>
                                        <td class="text-center">登录网址</td>
                                        <td class="text-center">登录IP</td>
                                        <td class="text-center">浏览器</td>
                                        <td class="text-center">结果</td>
                                        <td class="text-center">备注</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $v): ?>
                                        <tr id="<?=$v->id?>">
                                            <td><?=$v->id ?></td>
                                            <td><?=$v->admin_id ?></td>
                                            <td><?=$v->username ?></td>
                                            <td><?=date('Y-m-d H:i:s', $v->login_time) ?></td>
                                            <td><?=$v->url ?></td>
                                            <td><?=$v->login_ip?></td>
                                            <td><?=$v->explorer?></td>
                                            <td>成功</td>
                                            <td>成功<?=$v->number?>次</td>
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

JS;
$this->registerJs($js);
?>
