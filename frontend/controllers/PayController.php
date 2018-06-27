<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28 0028
 * Time: 15:59
 */

namespace frontend\controllers;


use backend\models\Admin;
use backend\models\Bank;
use backend\models\BankList;
use backend\models\Recharge;
use backend\models\Reports;
use backend\models\Withdrawals;
use frontend\models\Change;
use frontend\models\Mosaic;
use yii\web\Controller;
use yii\web\Response;
header('Access-Control-Allow-Origin:*');
class PayController extends Controller
{



    //解决网页报400错误
    public $enableCsrfValidation  = false;


    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 三方快捷支付接口充值
     * @return string
     */
    public function actionRecharge(){
        $type = \Yii::$app->request->post('type');
        $amount = \Yii::$app->request->post('price');//充值金额
        $token = \Yii::$app->security->generateRandomString();
        $company_order_num = $token; //唯一字符串
        $bank_id = \Yii::$app->request->post('bank_id');
        $estimated_payment_bank = $bank_id;
        $company_user = \Yii::$app->request->post('admin_id');
        $note = \Yii::$app->request->post('note');
        $note_model = \Yii::$app->request->post('note_model');
        $memo = \Yii::$app->request->post('memo');
        $deposit_mode= \Yii::$app->request->post('deposit_mode');
        //1为银行卡转账充值 //2为快捷充值，网银、微信、支付宝 //4为waq钱包
        $ip = \Yii::$app->request->getUserIP();
        $terminal = \Yii::$app->request->post('terminal');
        $key = md5('35830a57a05a4edc4dcc2c04461047da334'.$bank_id.$amount.$company_order_num.$company_user.$estimated_payment_bank.$deposit_mode.'0http://m.shenbang1.com'.$memo.$note.$note_model);
        $post_data = array(
            'company_id' => 334,
            'bank_id' => $bank_id,
            'amount' => $amount,
            'company_order_num' => $company_order_num,
            'company_user' => $company_user,
            'estimated_payment_bank' => $estimated_payment_bank,
            'deposit_mode' => $deposit_mode,
            'group_id' => 0,
            'web_url' => 'http://m.shenbang1.com',
            'memo' => $memo,
            'note' => $note,
            'note_model' => $note_model,
            'key' => $key,
            'terminal' => $terminal,
            'ip_address' => $ip,
        );
        $data= $post_data;//post参数
        $url="http://www.dppay100b.com/mownecum_api/Deposit?format=json";
        $ch = curl_init();//创建连接
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//将数组转换为URL请求字符串，否则有些时候可能服务端接收不到参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); //接收服务端范围的html代码而不是直接浏览器输出
        curl_setopt($ch, CURLOPT_HEADER, false);
        $responds = curl_exec($ch);//接受响应
        curl_close($ch);//关闭连接

        //保存用户充值记录
        $recharge = new Recharge();
        $recharge->admin_id = $company_user;//用户ID
        $admin = Admin::findOne(['id'=>$company_user]);
        $recharge->username = $admin->username; //用户名
        $recharge->price = $amount ;//充值金额
        $recharge->front_price  = $admin->price;  //充值前余额
        $recharge->after_price = $admin->price + $amount; //充值后余额
        $recharge->created_time = time(); //充值时间
        $recharge->status = 0;
        $recharge->bank_id = $bank_id; //银行ID
        $recharge->orders = $token; //商户订单号
        $recharge->deposit_mode = $deposit_mode ;//充值渠道
        $recharge->type = $type;//充值方式
        $json = json_decode($responds,true);
        $recharge->bank_card_num = $json['bank_card_num'];
        $recharge->bank_acc_name = $json['bank_acc_name'];
        $recharge->mownecum_order_num = $json['mownecum_order_num'];//支付平台订单号
        $recharge->save();
        $reports = Reports::findOne(['admin_id'=>$company_user]);
        $reports->in_price += $amount;
        $reports->in_price_num += 1;
        $reports->save();
        if($json['status'] ==0){
            return messages('操作失败',-1);
        }
        return messages('操作成功',1,$json);
    }


    /**
     * 第三方用户提款
     */
    public function actionWithdrawal(){
        $token = \Yii::$app->security->generateRandomString();
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $model = new Withdrawals();
        $reports = Reports::findOne(['admin_id'=>$query['admin_id']]);
        $shy = Mosaic::findOne(['admin_id'=>$query['admin_id']]);
        if($admin->type ==2){
            return messages('操作失败,试玩账号不能提款',-1);
        }else{
            if(token($query['admin_id'],$query['token'])){
                if(!\Yii::$app->security->validatePassword($query['password_pay'],$admin->password_pay)){
                    return messages('操作失败,支付密码错误',-1);
                }else{
                    if($query['price'] > $admin->price){
                        return messages('操作失败,余额不足',-1);
                    }
                    if($shy->number ==0){
                        return messages('操作失败,操作提款次数',-1);
                    }
                    $shy->number = $shy->number -  1;
                    if($shy->number -1 ==0){
                        Mosaic::updateAll(['status'=>-1],['admin_id'=>$query['admin_id']]);
                    }
                    $model->created_time = time();
                    //手续费计算
                    $model->front_price = $admin->price;
                    $model->after_price = $admin->price - $query['price'];
                    $model->price = $query['price'];
                    $admin->price = $admin->price -  $query['price'];
                    $model->bank_id = $query['bank_id'];
                    $bank_list = Bank::findOne(['id'=>$query['bank_id']]);
                    $model->company_id = 334; //充值时间
                    $model->card_num = $bank_list->bank_number;
                    $model->card_name = $bank_list->username;
                    $model->issue_bank_name = $bank_list->bank_name;
                    $model->issue_bank_address =$bank_list->address;
                    $model->orders = $token;
                    $model->memo = $query['memo'];
                    $model->type = $admin->type;
                    $model->username = $admin->username;
                    $model->admin_id = $query['admin_id'];
                    $model->status = 0;
                    $reports->out_price += $query['price'];
                    $reports->out_price_num += 1;
                    $change = new Change();
                    $arr['admin_id'] = $query['admin_id'];
                    $arr['username']=$admin->username;
                    $arr['type'] = '用户提款';
                    $arr['last_price'] = '-'.$query['price'];
                    $arr['price'] = $admin->price-$query['price'];
                    $arr['front_price'] = $admin->price;
                    $arr['game_type'] = '提款';
                    $arr['intro'] = $admin->username.'提款';
                    $change->Change($arr);
                    $reports = Reports::findOne(['admin_id'=>$query['admin_id']]);
                    $reports->out_price += $query['price'];
                    $reports->out_price_num += 1;
                    $reports->save();
                    $reports->save();
                    $model->save();
                    $shy->save();
                    $admin->save();
                    return messages('操作成功',1);
                }

            }else{
                return messages('操作失败,秘钥错误',-2);
            }


        }
    }




    /**
     * 返回信息
     * @return array
     */
    public function actionRechargeReturn(){
        $get = \Yii::$app->request->get('type');
        if($get == 'addTransfer'){ //充值
            $querys = file_get_contents('php://input');
            $datas =explode('&',$querys);
            foreach ($datas as $v){
                $shy =  explode('=',$v);
                $query[$shy[0]] =$shy[1];
            }
            $data = [
                'company_order_num'=>$query['company_order_num'],
                'mownecum_order_num'=>$query['mownecum_order_num'],
                'status'=>1,
                'error_msg'=>null
            ];
            $recharge = Recharge::find()->where(['orders'=>$query['company_order_num']])->andWhere(['mownecum_order_num'=>$query['mownecum_order_num']])->andWhere(['bank_id'=>$query['bank_id']])->andWhere(['status'=>0])->one();
            if($recharge ==null || $recharge==''){
                $data['status'] = 0;
                $data['error_msg'] = '操作失败，此订单已充值，请勿重复提交';
                return $data;
            }else{
                $recharge->updated_time =time();//支付平台系统完成时间：格式，年月日时分秒
                $recharge->status = 1;
                $recharge->fee = $query['fee'];//手续费
                $recharge->alias = $query['pay_card_name'];//付款人
                $recharge->base_info = $query['base_info'];//银行充值的交易流水号
                $recharge->pay_card_num = $query['pay_card_num'];//付款卡号
                $recharge->pay_card_name = $query['pay_card_name'];//付款卡用户名
                $recharge->channel = $query['channel'];//交易渠道
                $recharge->area = $query['area'];//交易地址
                $recharge->transaction_charge = $query['transaction_charge'];//服务费
                $recharge->actual_arrival = $recharge->price - $query['fee']; //实际入账
                $admin = Admin::findOne(['id'=>$recharge->admin_id]);
                $admin->price = $recharge->price + $admin->price - $query['fee']; //修改用户金额
                $admin->save();
                $recharge->save();
                $change = new Change();
                $arr['admin_id'] =  $recharge->admin_id;
                $arr['username']=$admin->username;
                $arr['type'] = '充值';
                $arr['last_price'] = $recharge->price;
                $arr['price'] = $admin->price - $recharge->price;
                $arr['front_price'] = $admin->price;
                $arr['game_type'] = '充值';
                $arr['intro'] = $admin->username.$query['operating_time'].'充值';
                $change->Change($arr);
                return $data;
            }
        }elseif ($get == 'exceptionWithdrawApply'){ //异常账单确认
//            $querys = file_get_contents('php://input');
//            $datas =explode('&',$querys);
//            foreach ($datas as $v){
//                $shy =  explode('=',$v);
//                $query[$shy[0]] =$shy[1];
//            }
//            if($query['exception_order_num'] == '' || $query['exception_order_num']==null){
//                $data = [
//                    'exception_order_num'=>'',
//                    'status'=>0,
//                    'error_msg'=>'没有exception_order_num 异常订单号',
//                ];
//            }else{
//                $data = [
//                    'exception_order_num'=>$query['exception_order_num'],
//                    'status'=>1,
//                    'error_msg'=>null
//                ];
//            }
//
//            return $data;
        }elseif ($get == 'requestWithdrawApproveInformation'){ //体现风控确认
            $querys = file_get_contents('php://input');
            $datas =explode('&',$querys);
            foreach ($datas as $v){
                $shy =  explode('=',$v);
                $query[$shy[0]] =$shy[1];
            }
            $data = [
                'company_order_num'=>$query['company_order_num'],
                'mownecum_order_num'=>$query['mownecum_order_num'],
                'status'=>1,
                'error_msg'=>null
            ];
            $withdalas = Withdrawals::find()->where(['mownecum_order_num'=>$query['mownecum_order_num']])->andWhere(['orders'=>$query['company_order_num']])->one();
            if($withdalas){
                return $data;
            }else{
                $data['status'] = 0;
                $data['error_msg'] = '没有此订单';
                return $data;
            }

        }elseif ($get == 'withdrawalResult'){ //确认体现
            $querys = file_get_contents('php://input');
            $datas =explode('&',$querys);
            foreach ($datas as $v){
                $shy =  explode('=',$v);
                $query[$shy[0]] =$shy[1];
            }
            $data = [
                'mownecum_order_num'=>$query['mownecum_order_num'],
                'company_order_num'=>$query['company_order_num'],
                'status'=>1,
                'error_msg'=>null
            ];
            $withdalas = Withdrawals::find()->where(['mownecum_order_num'=>$query['mownecum_order_num']])->andWhere(['orders'=>$query['company_order_num']])->one();
            if($withdalas){
                $withdalas->exaexact_transaction_charge = $query['exact_transaction_charge'];
                $withdalas->updated_time = time();
                $withdalas->status = 1;
                $withdalas->save();
                return $data;
            }else{
                $data['status'] = 0;
                $data['error_msg'] = '没有此订单';
                return $data;
            }

        }


    }
















    /**
     * 第三方提款返回提示
     */
    public function actionWithdrawalReturn(){
        $querys = file_get_contents('php://input');
        $datas =explode('&',$querys);
        foreach ($datas as $v){
            $shy =  explode('=',$v);
            $query[$shy[0]] =$shy[1];
        }
        $data  = [
            'company_order_num'=>$query['company_order_num'],
            'mownecum_order_num'=>$query['mownecum_order_num'],
            'status'=>1,
        ];
        $withdrawal = Recharge::findOne(['orders'=>$query['company_order_num']]);
        $withdrawal->updated_time = $query['operating_time'];//支付平台系统完成时间：格式，年月日时分秒
        $withdrawal->mownecum_order_num = $query['mownecum_order_num'];//支付平台订单号
        $withdrawal->details = $query['details'];//交易明细
        $withdrawal->status = 1;
        $withdrawal->transaction_charge = $query['transaction_charge'];//服务费
        $withdrawal->exact_transaction_charge = $query['exact_transaction_charge'];//实际服务费
        $withdrawal->mownecum_order_num = $query['mownecum_order_num'];//支付平台订单号
        $admin = Admin::findOne(['id'=>$withdrawal->admin_id]);
        $admin->price =$admin->price - $withdrawal->price -  $query['fee']; //修改用户金额
        $admin->save();
        $withdrawal->save();
        $change = new Change();
        $arr['admin_id'] =  $withdrawal->admin_id;
        $arr['username']=$admin->username;
        $arr['type'] = '提款';
        $arr['last_price'] = $withdrawal->price;
        $arr['price'] = $admin->price - $withdrawal->price;
        $arr['front_price'] = $admin->price;
        $arr['game_type'] = '提款';
        $arr['intro'] = $admin->username.$query['operating_time'].'提款';
        $change->Change($arr);
        return $data;
    }


}