<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '详情';
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
                                    <div> <?= \yii\helpers\Html::a('返回', ['index'], ['class' => 'btn btn-xs btn-success glyphicon glyphicon-cog']) ?></div>
                                    <table id="table_id_example" class="table
table-striped table-bordered " >
                                        <thead>
                                                <tr>
                                                    <td>订单单号:<?=$model->order?></td>&emsp;&emsp;<td>订单编号:<?=$model->id?></td>
                                                </tr>
                                                <tr>
                                                    <td>投注时间:<?=date('Y-m-d H:i:s',$model->created_time)?></td>&emsp;&emsp;<td>用户编号:<?=$model->admin_id?></td>
                                                </tr>
                                                <tr>
                                                    <td>用户名:<?=$model->username?></td>&emsp;&emsp;<td>投注类型:<?php
                                                        if($model->type==1){
                                                            echo "追号购买";
                                                        }elseif($model->type==0){
                                                            echo "普通购买";
                                                        }elseif($model->type==2){
                                                            echo "合买购买";
                                                        }
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td>倍数:<?=$model->multiple?></td>&emsp;&emsp;<td>投注单位:<?=$model->mode?></td>
                                                </tr>
                                                <tr>
                                                    <td>彩种名称:<?=$model->color?></td>&emsp;&emsp;<td>玩法名称:<?=$model->play?></td>
                                                </tr>
                                                <tr>
                                                    <td>奖金模式:奖金模式</td>&emsp;&emsp;<td>彩票期号:<?=$model->period?></td>
                                                </tr>
                                                <tr>
                                                    <td>返点:<?=$model->rebate?></td>&emsp;&emsp;<td>返点金额:<?=$model->rebate?></td>
                                                </tr>
                                                <tr>
                                                    <td>彩票状态:<?php
                                                         if($model->status==1){
                                                             echo "中奖";
                                                         }elseif($model->status==0){
                                                             echo "未开奖";
                                                         }elseif($model->status==2){
                                                             echo "未中奖 ";
                                                         }elseif($model->status==-1){
                                                             echo "撤单 ";
                                                         }
                                                        ?></td>&emsp;&emsp;<td>开奖号码:<?=$model->number?></td>
                                                </tr>
                                                <tr>
                                                    <td>投注金额:<?=$model->price?></td>&emsp;&emsp;<td>投注号码:<?=$model->content?></td>
                                                </tr>
                                                <tr>
                                                    <td>中奖金额:<?=$model->bonus?></td>&emsp;&emsp;<td>盈亏:<?=$model->yk?></td>
                                                </tr>
                                                <tr>
                                                    <td>中奖注数:<?=$model->winning_note?></td>&emsp;&emsp;<td>投注数量:<?=$model->quantity?></td>
                                                </tr>
                                        </thead>

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