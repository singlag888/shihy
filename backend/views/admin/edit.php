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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table id="table_id_example" class="table
table-striped table-bordered " >
                                        <thead>
                                        <tr>
                                            <td class="text-center">累计入款次数&emsp;&emsp; <span  style="text-align: center"> </span> <?=$data['in_money_number']?> </td>
                                            <td class="text-center">累计入款总额&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['in_money_price']?> </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">累计出款次数&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['out_money_number']?> </td>
                                            <td class="text-center">累计出款总额&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['out_money_price']?> </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">出款需打码量&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['dama']?></td>
                                            <td class="text-center">已达码量累计&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['dama1']?> </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">投注单数&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['lottery']?> </td>
                                            <td class="text-center">投注总额&emsp;&emsp; <span  style="text-align: center"> </span> <?=$data['lottery_price']?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">销售返水&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['out']?> </td>
                                            <td class="text-center">团队赚水&emsp;&emsp; <span  style="text-align: center"> </span> <?=$data['team_water']?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">中奖总额&emsp;&emsp; <span  style="text-align: center"> </span> <?=$data['win']?></td>
                                            <td class="text-center">派彩总额&emsp;&emsp; <span  style="text-align: center"> </span> <?=$data['win']?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">活动总额&emsp;&emsp; <span  style="text-align: center"> </span><?=$data['activity_price']?> </td>
                                            <td class="text-center">总盈亏&emsp;&emsp; <span  style="text-align: center"><?=$data['all']?> </span> </td>
                                        </tr>


                                        </thead>

                                    </table>

                                    <table id="table_id_example" class="table
table-striped table-bordered">
                                            <?php
                                           if($bank=='' || $bank==null){
                                                echo '<tr><th style="text-align: center"><h1>该用户没有绑定银行卡</h1></th></tr>';
                                            }
                                            foreach ($bank as $k):?>
                                                <tr>
                                                    <td>用户名</td>
                                                    <td>银行名称</td>
                                                    <td>开户名</td>
                                                    <td>银行卡号</td>
                                                    <td>银行网点</td>
                                                    <td>是否锁定</td>
                                                </tr>
                                                <tr>
                                                    <td><?=$k->username?></td>
                                                    <td><?=$k->bank_name?></td>
                                                    <td><?=$k->username?></td>
                                                    <td><?=$k->bank_number?></td>
                                                    <td><?=$k->address?></td>
                                                    <td><?=$k->status==1?'锁定':'不锁定'?></td>
                                                </tr>
                                            <?php endforeach;?>
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