<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/25 0025
 * Time: 10:43
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\Lottery;
use backend\models\Recharge;
use backend\models\Withdrawals;
use frontend\models\Drawing;

class RechargeController extends CommonController
{

    public $enableCsrfValidation = false;



    //充值列表（入款）
    public function actionIndex(){
        $model =Recharge::find()->orderBy('id DESC')->all();
        return $this->render('index',['model'=>$model]);
    }



    //体现列表
    public function actionIndex1(){
        $total =Withdrawals::find()->orderBy('id DESC')->all();
        return $this->render('index1',['model'=>$total]);
    }



    public function actionPrice($id){
        $start=mktime(0,0,0,date('m'),1,date('Y'));
        $end=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $recharge = Recharge::find()->where(['admin_id'=>$id])->andWhere(['>=','created_time',$start])->andWhere(['<=','created_time',$end])->sum('price');
        $withdrawals = Withdrawals::find()->where(['admin_id'=>$id])->andWhere(['>=','created_time',$start])->andWhere(['<=','created_time',$end])->sum('price');
        $lottery = Lottery::find()->where(['admin_id'=>$id])->andWhere(['>=','created_time',$start])->andWhere(['<=','created_time',$end])->sum('yk');
        $data = [
            'recharge'=>$recharge==null?'0.000':$recharge,
            'withdrawals'=>$withdrawals==null?'0.000':$withdrawals,
            'lottery'=>$lottery==null?'0.000':$lottery,
            'in'=>'0.000',
            'out'=>'0.000',
        ];
        $data = json_encode($data);
        return $data;
    }



    /**
     * 修改状态 添加金额
     */
    public function actionStatus($id,$status){
        $time = date('Y-m-d H:i:s',time());
        $sta = Recharge::findOne(['id'=>$id]);
        if ($status==1){
            if($sta->status != 1 &&$sta->status !=-1){
                Recharge::updateAll(['status'=>1,'updated_time'=>time()],['id'=>$id]);
                $recharge = Recharge::findOne(['id'=>$id]);
                $admin = Admin::findOne(['id'=>$recharge->admin_id]);
                $admin->price = $admin->price + $recharge->price;
                $admin->save();
            }
        }elseif($status==-1){
            if($sta->status != -1){
                Recharge::updateAll(['status'=>-1,'updated_time'=>time()],['id'=>$id]);

            }
        }
        return $time;
    }



    /**
     * 修改状态 扣除金额
     */
    public function actionStatu($id,$status){
        $time = date('Y-m-d H:i:s',time());
        $sta = Withdrawals::findOne(['id'=>$id]);
        if ($status==1){
            if($sta->status != 1 && $sta->status !=-1){
                $company_order_num = $sta->orders; //唯一字符串
                $amount = $sta->price;//体现金额
                $bank_id = $sta->bank_id;//银行ID
                $card_num = $sta->card_num;//银行卡卡号：用户的提现银行卡卡号
                $card_name = $sta->card_name;//银行卡姓名：用户提现银行卡姓名
                $company_user = $sta->admin_id; //商户：用户在商户前台的使用ID
                $issue_bank_name = $sta->issue_bank_name; //开户行名称
                $issue_bank_address = $sta->issue_bank_address; //开户行地址
                $memo = $sta->memo; //备用字段
                $key = md5('35830a57a05a4edc4dcc2c04461047da334'.$bank_id.$company_order_num.$amount.$card_num.$card_name.$company_user.$issue_bank_name.$issue_bank_address.$memo);
                $post_data = array(
                    'company_id' => 334,
                    'bank_id' => $bank_id,
                    'company_order_num' => $company_order_num,
                    'amount' => $amount,
                    'card_num' => $card_num,
                    'card_name' => $card_name,
                    'company_user' => $company_user,
                    'issue_bank_name' => $issue_bank_name,
                    'issue_bank_address' => $issue_bank_address,
                    'memo' => $memo,
                    'key' => $key,
                );
                //保存用户体现记录
                $data= $post_data;//post参数
                $url="http://www.dppay100b.com/mownecum_api/Withdrawal?format=json";
                $ch = curl_init();//创建连接
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//将数组转换为URL请求字符串，否则有些时候可能服务端接收不到参数
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); //接收服务端范围的html代码而不是直接浏览器输出
                curl_setopt($ch, CURLOPT_HEADER, false);
                $responds = curl_exec($ch);//接受响应
                curl_close($ch);//关闭连接
                $json = json_decode($responds,true);
                $sta->mownecum_order_num = $json['mownecum_order_num'];//支付平台订单号
                $sta->transaction_charge = $json['transaction_charge'];//服务费
                $sta->status = 1;//状态
                $sta->updated_time = time();//状态
                $sta->save();

            }
        }elseif($status==-1){
            if($sta->status != -1){
                Withdrawals::updateAll(['status'=>-1,'updated_time'=>time()],['id'=>$id]);

            }
        }
        return $time;
    }


}