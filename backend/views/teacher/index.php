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
                <?= Html::a('添加教师', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="table_id_example" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center">名称</th>
                                        <th class="text-center">年龄(岁)</th>
                                        <th class="text-center">性别</th>
                                        <th class="text-center">手机号</th>
                                        <th class="text-center">邮箱</th>
                                        <th class="text-center">工龄(年)</th>
                                        <th class="text-center">单位</th>
                                        <th class="text-center">居住地</th>
                                        <th class="text-center">学历</th>
                                        <th class="text-center">资格证</th>
                                        <th class="text-center">星级</th>
                                        <th class="text-center">余额(钱包)</th>
                                        <th class="text-center">审核状态</th>
                                        <th class="text-center">注册时间</th>
                                        <th class="text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($teachers as $teacher):?>
                                        <tr id="<?=$teacher->id?>" class="text-center">
                                            <td><?=$teacher->name?></td>
                                            <td><?=$teacher->age?></td>
                                            <td><?=$teacher->sex==1?'男':'女'?></td>
                                            <td><?=$teacher->phone?></td>
                                            <td><?=$teacher->email?></td>
                                            <td><?=$teacher->life?></td>
                                            <td><?=$teacher->work_unit?></td>
                                            <td><?=$teacher->domicile?></td>
                                            <td><?php if ($teacher->education==1){echo "初中";}elseif ($teacher->education==2){echo "高中";}elseif ($teacher->education==3){echo "大专";}elseif ($teacher->education==4){echo "本科";}elseif ($teacher->education==5){echo "研究生";}elseif ($teacher->education==6){echo "硕士";}elseif ($teacher->education==7){echo "博士";}elseif ($teacher->education==8){echo "其他";}?></td>
                                            <td><img src="<?=$teacher->certificate?>" width="110px"></td>
                                            <td><?=$teacher->star?></td>
                                            <td>￥<?=$teacher->balance?></td>
                                            <td><?php if($teacher->status==1){echo "<a data-toggle='modal' data-target='#myModal' title='点击更改'>待审核</a>";}elseif($teacher->status==2){echo "通过审核";}else{echo "<a data-toggle='modal' data-target='#myModal' title='点击更改'>不合格</a>";}?></td>
                                            <td><?=date('Y-m-d H:i:s',$teacher->add_time)?></td>
                                            <td><a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['teacher/update','id'=>$teacher['id']])?>"><i class="fa fa-edit"></i>编辑</a>  <a id="del" class="btn btn-default btn-xs">删除</a></td>
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
$url = \yii\helpers\Url::to(['teacher/delete']);
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
