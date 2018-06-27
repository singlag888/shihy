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
                                        <th class="text-center"><h1>登陆IP黑名单</h1></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="">
                                        <td>搜索条件 : <input type="text" id="username" name="username"> <a  class="btn btn-danger btn-xs" id="select">查询用户</a>  <a  class="btn btn-danger btn-xs">解禁</a><a  class="btn btn-danger btn-xs">禁用</a></td>
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
$url = \yii\helpers\Url::to(['login/select']);
$js =
    <<<JS


    $("#select").click(function() {
        var ip = $("#username").val();
          $.get('$url',{ip:ip},function(data) {
              if(data == -1){
                  alert("该IP已被禁用");
              }else{
                  alert("该IP未被禁用");
              }
          })
    })
JS;
$this->registerJs($js);
?>
