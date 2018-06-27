<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '彩票注单';
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
                                        <th class="text-center" rowspan="2">层级</th>
                                        <th class="text-center" colspan="2">普通入款</th>
<!--                                        <th class="text-center">次数</th>-->
                                        <th class="text-center" colspan="2">快速入款</th>
                                        <th class="text-center" colspan="2">第三放入款</th>
                                        <th class="text-center" colspan="2">人工入款</th>
                                        <th class="text-center" colspan="2">总计</th>
                                    </tr>
                                    <tr id="">
                                        <td>次数</td>
                                        <td>金额</td>
                                        <td>次数</td>
                                        <td>金额</td>
                                        <td>次数</td>
                                        <td>金额</td>
                                        <td>次数</td>
                                        <td>金额</td>
                                        <td>次数</td>
                                        <td>金额</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($hierarchy as $vo):?>
                                        <tr id="">
                                            <td><?=$vo['name']?></td>
                                            <td><?=$vo['pu_nu']?></td>
                                            <td><?=$vo['pu_jine']?></td>
                                            <td><?=$vo['kuai_nu']?></td>
                                            <td><?=$vo['kuai_jine']?></td>
                                            <td><?=$vo['san_nu']?></td>
                                            <td><?=$vo['san_jine']?></td>
                                            <td><?=$vo['ren_nu']?></td>
                                            <td><?=$vo['ren_jine']?></td>
                                            <td><?=$vo['pu_nu']+$vo['kuai_nu']+$vo['san_nu']+$vo['ren_jine']?></td>
                                            <td><?=$vo['ren_jine']+$vo['pu_jine']+$vo['kuai_jine']+$vo['san_jine']?></td>
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
$url = \yii\helpers\Url::to(['letter/delete']);
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
