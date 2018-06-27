<?php
/**
 * Created by PhpStorm.
 * User: melotao
 * Date: 2018/6/13
 * Time: 14:27
 */

namespace frontend\controllers;


use backend\models\Admin;
use backend\models\Reports;
use frontend\models\Aglog;
use frontend\models\Change;
use frontend\models\Imsblog;
use yii\web\Controller;
use yii\web\Response;

header('Access-Control-Allow-Origin:*');
class GameController extends Controller
{
    public $enableCsrfValidation = false;
    const CODE = 'OZs9oP2s9lazLTK4kzca4TaDTUkdmpIO';

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * 注册
     * @return mixed
     */
    static function Register($player,$password)
    {
//        $player = \Yii::$app->request->post('admin_name');
//        $player = \Yii::$app->request->post('password');
        $data = array(
            'MerchantCode' =>self::CODE,
            'PlayerId' => $player,
            'Currency' => 'CNY',
            'Password' => $password,
            'Country' => 'CN',
        );
        $url = "http://imone.imaegisapi.com/Player/Register";
        $data = curl($url, $data);
        return $data;
    }

    /**
     * 修改密码
     * @param $player
     * @param $password
     * CreateBy:melo.tao
     * @return array|mixed
     */
    static function EditPwd($player,$password)
    {
//        $player = \Yii::$app->request->post('admin_name');
//        $player = \Yii::$app->request->post('password');
        $data = array(
            'MerchantCode' =>self::CODE,
            'PlayerId' => $player,
            'Password' => $password,
        );
        $url = "http://imone.imaegisapi.com/Player/ResetPassword";
        $data = curl($url, $data);
        return $data;
    }

    /**
     * 资金转账
     * CreateBy:melo.tao
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionPerformTransfer()
    {
        $productWallet = \Yii::$app->request->post('productwallet');
        $money = abs(\Yii::$app->request->post('money'));
        $type = \Yii::$app->request->post('type');
        $player = \Yii::$app->request->post('admin_name');
        $change = new Change();
        $player_info = Admin::findOne(['username'=>$player])->getAttributes(['id','price']);
        //平台账户余额小于转入余额
        if($type==1&&$money>$player_info['price']){
            return messages('账户余额不足', -1);
        }
        $change->type = $type==1?'转出到第三方平台':'转入到第三方平台';//变动类型
        $change->game_type = $productWallet==201?'AG':'IM体育';//变动类型
        $change->last_price = $money;//变动金额
        $change->price = $player_info['price']-$money;//变动后余额
        $change->admin_id = $player_info['id'];
        $change->updated_time = time();//变动时间
        $change->username = $player;//用户账号
        $change->front_price = $player_info['price'];//变动前余额
        $change->intro = $productWallet==201?'IM平台的AG操作':'IM平台体育操作';//备注
        $reports = Reports::findOne(['admin_id'=>$player_info['id']]);
        $reports->game_in += $money;
        //转出
        if($type==2){
            $result = $this->GetBalance($productWallet,$player);
            if($result['Balance']<$money){
                return messages('账户余额不足', -1);
            }
            $change->price = $player_info['price']+$money;
            $reports = Reports::findOne(['admin_id'=>$player_info['id']]);
            $reports->game_out += $money;
            $reports->save();
            $money = -$money;
        }
        $change->save();
        $update = Admin::updateAll(['price'=>$change->price],['id'=>$player_info['id']]);
        $reports->save();
        if($update){
            $data = array(
                'MerchantCode' => self::CODE,
                'PlayerId' => $player,
                'ProductWallet' => $productWallet,
                'TransactionId' => md5(\Yii::$app->security->generateRandomString()),
                'Amount' => $money,
            );
            $url = "http://imone.imaegisapi.com/Transaction/PerformTransfer";
            $data = curl($url, $data);
        }else{
            return messages('充值失败', 1);
        }
        if ($data['Code'] == 0) {
            return messages('操作成功', 1);
        } else {
            return messages('操作失败', -1);
        }
    }


    /**
     * 查询余额
     * CreateBy:melo.tao
     * @return mixed
     */
    public function GetBalance($productWallet,$player)
    {
//        $productWallet = \Yii::$app->request->post('productwallet');
        $data = array(
            'MerchantCode' => self::CODE,
            'PlayerId' => $player,
            'ProductWallet' => $productWallet
        );
        $url = "http://imone.imaegisapi.com/Player/GetBalance";
        $data = curl($url, $data);
        return $data;
    }

    /**
     * 查询账户余额
     * CreateBy:melo.tao
     * @return mixed
     */
    public function actionGetPrice(){
        $productWallet = \Yii::$app->request->post('productwallet');
        $player = \Yii::$app->request->post('admin_name');
        $result = $this->GetBalance($productWallet,$player);
//        return $result;
        if ($result['Code'] == 0) {
             $data['msg'] = '操作成功';
             $data['error'] = 1;
             $data['data']['price'] = $result['Balance'];
            return $data;
        } else {
            return messages('操作失败', -1);
        }

    }

    /**
     * 玩家下注日志
     * CreateBy:melo.tao
     * @return array|mixed
     */
    public function GetBetLog()
    {
//        $productWallet = \Yii::$app->request->post('productwallet');
        $page =1;
        $EndDate = date('Y-m-d H.i.s',time()-15*60);
        $StartDate =date('Y-m-d H.i.s',time()-15*60-10*60);
        $pageSize = 5000;
        $data = array(
            'MerchantCode' =>self::CODE,
            "StartDate" => $StartDate,
            "EndDate" => $EndDate,
            "Page" => $page,
            "PageSize" => $pageSize,
            "ProductWallet" => 201,
            "Currency" => "CNY"
        );
        $data1 = array(
            'MerchantCode' =>self::CODE,
            "StartDate" => $StartDate,
            "EndDate" => $EndDate,
            "Page" => $page,
            "DateFilterType" => 1,
            "BetStatus" => 0,
            "Language" => 'EN',
            "PageSize" => $pageSize,
            "ProductWallet" => 301,
        );
        $url = "http://imone.imaegisapi.com/Report/GetBetLog";
        $data = curl($url, $data);
        $json1 = curl($url, $data1);
        //IMSB
        if($json1['Result']){
        $model = new Imsblog();
            foreach ($json1['Result'] as $item){
              $model->setAttributes($item,false);
              $model->save(false);
            }
        }
        //AG
        if($data['Result']){
            $model = new Aglog();
            foreach ($data['Result'] as $item){
                $model->setAttributes($item,false);
                $model->save(false);
            }
        }
    }

//    public function actionGetSportsLog()
//    {
//        $productWallet = \Yii::$app->request->post('productwallet');
//        $page = \Yii::$app->request->post('page');
//        $StartDate = \Yii::$app->request->post('startdate');
//        $EndDate = \Yii::$app->request->post('enddate');
//        $pageSize = \Yii::$app->request->post('pagesize');
//        $data = array(
//            'MerchantCode' =>self::CODE,
//            "StartDate" => $StartDate,
//            "EndDate" => $EndDate,
//            "Page" => $page,
////            "DateFilterType" => 1,
////            "BetStatus" => 0,
////            "Language" => 'EN',
//            "PageSize" => $pageSize,
//            "ProductWallet" => 301,
//            "Currency" => "CNY"
//        );
//        $url = "http://imone.imaegisapi.com/Report/GetBetLog";
//        $data = curl($url, $data);
//        return $data;
//        if ($data['Code'] == 0) {
//            return messages('操作成功', 1);
//        } else {
//            return messages('操作失败', -1);
//        }
//    }
    /**
     * 产品报表
     * CreateBy:melo.tao
     * @return array|mixed
     */
    public function actionProductReport()
    {
        $StartDate = \Yii::$app->request->post('StartDate');
        $EndDate = \Yii::$app->request->post('EndDate');
        $ReportBy = \Yii::$app->request->post('ReportBy')?1:\Yii::$app->request->post('ReportBy');
        $data = array(
            'MerchantCode' => self::CODE,
            "StartDate" => $StartDate,
            "EndDate" => $EndDate,
            "ProductWallet" => 101,
            "Currency" => "CNY",
            "ReportBy" => $ReportBy
        );
        $url = "http://imone.imaegisapi.com/Report/ProductReport";
        $data = curl($url, $data);
        return $data;
        if ($data['Code'] == 0) {
            return messages('操作成功', 1);
        } else {
            return messages('操作失败', -1);
        }
    }

    /**
     * IMOne 游戏 API
     * CreateBy:melo.tao
     * @return array|mixed
     */
    public function actionLaunchGame()
    {
        $productWallet = \Yii::$app->request->post('productwallet');
        $GameCode = \Yii::$app->request->post('gamecode');
        $admin_name = \Yii::$app->request->post('admin_name');
        $data = array(
            'MerchantCode' => self::CODE,
            "PlayerId" => $admin_name,
            "GameCode" => $GameCode,
            "Language" => "ZH-CN",
            "IpAddress" => \Yii::$app->request->userIP,
            "ProductWallet" => $productWallet,
        );
        $url = "http://imone.imaegisapi.com/Game/LaunchGame";
        $data = curl($url, $data);
        if ($data['Code'] == 0) {
            return messages('操作成功', 1,$data['GameUrl']);
        } else {
            return messages('操作失败', -1);
        }
    }

    /**
     * IMOne 游戏 移动端API
     * CreateBy:melo.tao
     * @return array|mixed
     */
    public function actionLaunchMobileGame()
    {
        $productWallet = \Yii::$app->request->post('productwallet');
        $GameCode = \Yii::$app->request->post('gamecode');
        $admin_name = \Yii::$app->request->post('admin_name');
        $data = array(
            'MerchantCode' => self::CODE,
            "PlayerId" => $admin_name,
            "GameCode" => $GameCode,
            "Language" => "ZH-CN",
            "IpAddress" => \Yii::$app->request->userIP,
            "ProductWallet" => $productWallet,
        );
        $url = "http://imone.imaegisapi.com/Game/LaunchMobileGame";
        $data = curl($url, $data);
        if ($data['Code'] == 0) {
            return messages('操作成功', 1,$data['GameUrl']);
        } else {
            return messages('操作失败', -1);
        }
    }
}