<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '投注几率';
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
                                <table id="table_id_example" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <td>投注期号</td>
                                        <td>用户名</td>
                                        <td>订单单号</td>
                                        <td>彩种名称<br/>玩法名称</td>
                                        <td >投注号码</td>
                                        <td>投注额</td>
                                        <td>投注时间</td>
                                        <td>盈亏状态</td>
                                        <td>购买类型</td>
                                        <td>操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $v):  ?>
                                        <tr id="<?=$v->id?>">
                                            <td><?= $v->period ?></td>
                                            <td><?= $v->username ?></td>
                                            <td><?= $v->order ?></td>
                                            <td><?= $v->color ?><br/><?=$v->play?></td>
                                            <td><?= substr($v->content,0,5).'....'?></td>
                                            <td><?= $v->price ?></td>
                                            <td><?= date('Y-m-d H:i:s',$v->created_time )?></td>
                                            <td><?= $v->yk ?></td>
                                            <td><?php
                                                if($v['type'] == 0){
                                                    echo "普通投注";
                                                }elseif($v['type'] == 1){
                                                    echo "追号投注";
                                                }elseif($v['type'] == 2){
                                                    echo "合买投注";
                                                }?></td>
                                            <td>
                                                <?= \yii\helpers\Html::a('详细', ['intro', 'id' => $v->id], ['class' => 'btn btn-xs btn-success glyphicon glyphicon-cog']) ?>
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
