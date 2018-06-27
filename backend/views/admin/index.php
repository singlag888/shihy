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
                                            <td class="text-center">用户编号<br/>用户名</td>
                                            <td class="text-center">用户类型</td>
                                            <td class="text-center">余额</td>
                                            <td class="text-center">最大返点<br/>最小返点</td>
                                            <td class="text-center">总入款/总出款<br/>总转入/总转出</td>
                                            <td class="text-center">上级账号<br/>上级编号</td>
                                            <td class="text-center">下级余额<br/>下级人数</td>
                                            <td class="text-center">常用登陆IP<br/>最近登陆IP</td>
                                            <td class="text-center">注册时间<br/>最近登陆时间</td>
                                            <td class="text-center">账号状态<br/>在线状态</td>
                                            <td class="text-center">操作</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($letter as $v): ?>
                                            <tr id="<?= $v->id ?>" class="text-
center">
                                                <td><?= $v->id ?><br><?= $v->username ?></td>
                                                <td><?php
                                                    if($v['type'] == 1){
                                                        echo "代理";
                                                    }elseif($v['type'] == 2){
                                                        echo "试玩账号";
                                                    }elseif($v['type'] == 0){
                                                        echo "会员";
                                                    }
                                                    ?></td>
                                                <td><?= $v->price?></td>
                                                <td><?= $v->max ?><br/><?= $v->min ?></td>
                                                <td class="price"><a >点击查看</a></td>
                                                <td><?=$v->parent_id?><br/><?=$v->parent_id?></td>
                                                <td class="down"><a >点击查看</a></td>
                                                <td><?=$v->login_ip?><br/><?=$v->login_ip?></td>
                                                <td><?= date('Y-m-d H:i:s', $v->created_time) ?><br/><?= date('Y-m-d H:i:s', $v->login_time) ?></td>
                                                <td><?= $v->status == 1 ? '启用' : '
禁止' ?><br/><?= $v->online == 1 ? '启用' : '
禁止' ?></td>
                                                <td>
                                                    <?= \yii\helpers\Html::a('修改',
                                                        ['update', 'id' => $v->id], ['class' => 'btn btn-xs btn-danger glyphicon 
glyphicon-cog']) ?>
                                                    <?= \yii\helpers\Html::a('充值',
                                                        ['recharge', 'id' => $v->id], ['class' => 'btn btn-xs btn-warning glyphicon 
glyphicon-shopping-cart']) ?>
                                                    <?= \yii\helpers\Html::button('
删除', ['class' => 'btn btn-xs btn-default glyphicon glyphicon-trash', 'id' =>
                                                        'del']) ?>

                                                    <?= \yii\helpers\Html::a('设置',
                                                        ['edit', 'id' => $v->id], ['class' => 'btn btn-xs btn-primary glyphicon 
glyphicon-shopping-cart']) ?>
                                                    <?= \yii\helpers\Html::a('下级',
                                                        ['pelo', 'id' => $v->id], ['class' => 'btn btn-xs btn-primary glyphicon 
glyphicon-shopping-cart']) ?>
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
$url = \yii\helpers\Url::to(['admin/delete']);
$url1 = \yii\helpers\Url::to(['admin/price']);
$url2 = \yii\helpers\Url::to(['admin/down']);
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
    
 
    
    
    $("#table_id_example").on('click','.price',function() {
    var tr = $(this).closest('tr');
           var that=$(this);
          $.get('$url1',{id:tr.attr('id')},function(data) {
           var mydata=JSON.parse(data);
           that.eq().prevObject[0].innerHTML='<p style="text-align:center">'+mydata.recharge+'/'+mydata.withdrawals
+'</p>'+'<p style="text-align:center">'+mydata.out+'/'+mydata.in
+'</p>'
      }) 
    });
    
        $("#table_id_example").on('click','.down',function() {
           var tr = $(this).closest('tr');
           var that=$(this);
          $.get('$url2',{id:tr.attr('id')},function(data) {
           var mydata=JSON.parse(data);
           that.eq().prevObject[0].innerHTML='<p style="text-align:center">'+mydata.recharge
+'</p>'+'<p style="text-align:center">'+mydata.count+'</p>'
      }) 
    });
JS;
$this->registerJs($js);
?>