<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '活动';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="wrapper wrapper-content">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <p>
                    <?= Html::a('添加用户', ['create'], ['class' => 'btn btn-sm 
btn-primary']) ?>
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
                                            <td class="text-center">最大返点<br/>最小返点</td>
                                            <td class="text-center">注册类型</td>
                                            <td class="text-center">注册数</td>
                                            <td class="text-center">状态</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($model as $v): ?>
                                            <tr id="<?= $v['id'] ?>" class="text-center">
                                                <td><?= $v['id'] ?></td>
                                                <td><?= $v['username'] ?></td>
                                                <td><?= $v['max'] ?><br/><?= $v['min'] ?></td>
                                                <td><?= $v['type']==1?"代理":'会员' ?></td>
                                                <td><?php
                                                    if($v['type']==1){
                                                        echo '0/999999999';
                                                    }else{
                                                        echo '0/999';
                                                    }


                                                    ?></td>
                                                <td><span class="btn btn-danger">启用</span></td>

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
$url = \yii\helpers\Url::to(['admin/delete']);
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