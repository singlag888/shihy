<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '家长';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <p>
                <?= Html::a('添加家长', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="table_id_example" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>家长姓名</th>
                                        <th>家长手机号</th>
                                        <th>宝宝名字</th>
                                        <th>宝宝年龄</th>
                                        <th>积分</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($members as $u):?>
                                        <tr id="<?=$u->id?>">
                                            <td><?=$u->name?></td>
                                            <td><?=$u->phone?></td>
                                            <td><?=$u->baby_name?></td>
                                            <td><?=$u->baby_age?></td>
                                            <td><?=$u->integral==0?'此家长没有积分!':$u->integral?></td>
                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['member/update','id'=>$u->id])?>"><i class="fa fa-edit"></i>编辑</a>  <a id="del" class="btn btn-default btn-xs">删除</a> <a href="<?=Url::to(['member/coupon','id'=>$u->id])?>" class="btn btn-danger btn-xs glyphicon glyphicon-gift">发送优惠卷</a></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <!--分页-->
<!--                                <div class="f-r">
                                    <?/*= LinkPager::widget([
                                        'pagination'=>$pages,
                                        'firstPageLabel' => '首页',
                                        'nextPageLabel' => '下一页',
                                        'prevPageLabel' => '上一页',
                                        'lastPageLabel' => '末页',
                                    ]) */?>
                                </div>-->

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
$url = \yii\helpers\Url::to(['member/delete']);
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
