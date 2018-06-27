<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '菜单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <p>
                <?= Html::a('创建菜单', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="table_id_example">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>名称</th>
                                        <th>父级</th>
                                        <th>路由</th>
                                        <th>状态</th>
                                        <th>排序</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($menu as $vo):?>
                                        <tr id="<?=$vo['id']?>">
                                            <td><?=$vo['id']?></td>
                                            <td><?=$vo['name']?></td>
                                            <td><?=$vo['parent']?></td>
                                            <td><?=$vo['route']?></td>
                                            <td><?=$vo['status'] >0 ? '<span class="label label-info">显示</span>' : '<span class="label label-error">隐藏</span>'?></td>
                                            <td><?=($vo['sort']==''?'':$vo['sort'])?></td>
                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['menu/update','id'=>$vo['id']])?>"><i class="fa fa-edit"></i>编辑</a>  <a class="btn btn-default btn-xs" id="del"><i class="fa fa-close"></i>删除</a></td>
                                        </tr>
                                        <!--二级菜单-->
                                        <?php if(!empty($vo['_child'])):?>
                                            <?php foreach($vo['_child'] as $v):?>
                                                <tr id="<?=$v['id']?>">
                                                <td><?=$v['id']?></td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;┝<?=$v['name']?></td>
                                                <td><?=$v['parent']?></td>
                                                <td><?=$v['route']?></td>
                                                <td><?=$v['status'] > 0 ? '<span class="label label-info">显示</span>' : '<span class="label label-error">隐藏</span>'?></td>
                                                <td><?=($v['sort']==''?'':$v['sort'])?></td>
                                                <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['menu/update','id'=>$v['id']])?>"><i class="fa fa-edit"></i>编辑</a>  <a class="btn btn-default btn-xs" id="del"><i class="fa fa-close"></i>删除</a></td>
                                                </tr>

                                                <!--三级菜单-->
                                                <?php if(!empty($v['_child'])):?>
                                                    <?php foreach($v['_child'] as $v3):?>
                                                        <tr id="<?=$v3['id']?>">
                                                            <td><?=$v3['id']?></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┝<?=$v3['name']?></td>
                                                            <td><?=$v3['parent']?></td>
                                                            <td><?=$v3['route']?></td>
                                                            <td><?=$v3['status'] > 0 ? '<span class="label label-info">显示</span>' : '<span class="label label-error">隐藏</span>'?></td>
                                                            <td><?=($v3['sort']==''?'':$v3['sort'])?></td>
                                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['menu/update','id'=>$v3['id']])?>"><i class="fa fa-edit"></i>编辑</a>  <a class="btn btn-default btn-xs" id="del"><i class="fa fa-close"></i>删除</a></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                <!--三级菜单 end-->

                                            <?php endforeach;?>
                                        <?php endif;?>
                                        <!--二级菜单 end-->

                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <!--分页-->
                                <!--<div class="f-r">
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
$url = \yii\helpers\Url::to(['menu/delete']);
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
          $.get('$url',{id:tr.attr('id')},function(date) {
              if (date==1){
                  tr.remove();
              }
           
          })
      } 
    })
JS;
$this->registerJs($js);
?>