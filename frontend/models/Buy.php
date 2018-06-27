<?php
/**
 * Buy控制器中的方法
 */

namespace frontend\models;


use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\Gflfc;
use backend\models\Gfwfc;
use backend\models\Hg15fc;
use backend\models\HowToPlay;
use backend\models\Jndsfc;
use backend\models\Ltxffc;
use backend\models\PlayMethod;
use backend\models\Reports;
use backend\models\Xxl45mc;
use yii\base\Model;

class Buy extends Model
{
    /*前端追号
     * @param $datas
     * @return array
     */
    public static function Zhuihao($datas)
    {
        $result = [];
        $data = $datas['Lottery'];
        foreach ($data as $key=>$datum){
            $time = time();
            $admin  =Admin::findOne(['id'=>$datas['admin_id']]);
            $a = 0;
            $b = 0;
            foreach ($datum as $k=>$item){
                if ($k==0){//保存在总追号表中
                    $redis = new \Redis();
                    $redis->connect('127.0.0.1', 6379);
                    $howtoplay = HowToPlay::findOne(['id'=>$item['color_id']]);
                    $playmethod = PlayMethod::find()->where(['lottery_type'=>$item['color_id']])->andWhere(['number'=>$item['play_id']])->one();
                    if (!$item['price'] || !$item['color_id'] || !$item['play'] || !$item['price'] || !$item['bonus']) {
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'period'=>$item['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' => '数据不完整'];
                        break;
                    }
                    //彩种总开关
                    if ($howtoplay->status==-1){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'该彩种已停止'];
                        break;
                    }elseif ($howtoplay->status==-2){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'该彩种已停售'];
                        break;
                    }
                    //玩法控制
                    if ($playmethod->status==0){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'该玩法维护中'];
                        break;
                    }elseif ($playmethod->status==-1){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'该玩法已停售'];
                        break;
                    }elseif ($playmethod->note){
                        if ($item['quantity']>$playmethod->note){
                            $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'price'=>$item['price'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'注单数不能超过平台限制'.$playmethod->note.'注,当前投注'.$item['quantity'].'注'];
                            break;
                        }
                    }
                    if ($item['zongjine']> $admin->price){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'period'=>$item['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'余额不足'];
                        break;
                    }
                    if ($item['color_id'] == 60) {//腾讯分分彩
                        //前端显示倒计时
                        $redis = resis(60);
                        $fp = 10;
                    } elseif ($item['color_id'] == 1) {//重庆时时彩
                        $redis = resis(1);
                        $fp = 90;
                        //前端显示倒计时
                    } elseif ($item['color_id'] == 3) {//天津时时彩
                        //前端显示倒计时
                        $redis = resis(3);
                        $fp = 120;
                    } elseif ($item['color_id'] == 7) {//新疆时时彩
                        //前端显示倒计时
                        $redis = resis(7);
                        $fp = 120;
                    } elseif ($item['color_id'] == 112) {//官方分分彩
                        //前端显示倒计时
                        $redis = resis(112);
                        $fp = 10;
                    } elseif ($item['color_id'] == 113) {//官方两分彩
                        //前端显示倒计时
                        $redis = resis(113);
                        $fp = 20;
                    } elseif ($item['color_id'] == 114) {//官方五分彩
                        //前端显示倒计时
                        $redis = resis(114);
                        $fp = 60;
                    } elseif ($item['color_id'] == 9) {//广东11选5
                        //前端显示倒计时
                        $redis = resis(9);
                        $fp = 120;
                    } elseif ($item['color_id'] == 6) {//江西11选5
                        //前端显示倒计时
                        $redis = resis(6);
                        //封盘时间
                        $fp = 120;
                    } elseif ($item['color_id'] == 115) {//江苏11选5
                        //前端显示倒计时
                        $redis = resis(115);
                        $fp = 120;
                    } elseif ($item['color_id'] == 22) {//上海11选5
                        //前端显示倒计时
                        $redis = resis(22);
                        $fp = 120;
                    } elseif ($item['color_id'] == 116) {//官方11选5
                        //前端显示倒计时
                        $redis =resis(116);
                        $fp = 10;
                    } elseif ($item['color_id'] == 23) {//江苏快三
                        //前端显示倒计时
                        $redis = resis(23);
                        $fp = 120;
                    } elseif ($item['color_id'] == 24) {//安徽快三
                        //前端显示倒计时
                        $redis = resis(24);
                        $fp = 120;
                    } elseif ($item['color_id'] == 37) {//北京快三
                        //前端显示倒计时
                        $redis = resis(37);
                        //封盘时间
                        $fp = 120;
                    } elseif ($item['color_id'] == 38) {//广西快三
                        //前端显示倒计时
                        $redis = resis(38);
                        $fp = 120;
                    } elseif ($item['color_id'] == 117) {//官方快三
                        //前端显示倒计时
                        $redis = resis(117);
                        $fp = 10;
                    } elseif ($item['color_id'] == 27) {//北京PK10
                        //前端显示倒计时
                        $redis = resis(27);
                        $fp = 20;
                    } elseif ($item['color_id'] == 118) {//官方分分赛车
                        //前端显示倒计时
                        $redis = resis(118);
                        //封盘时间
                        $fp = 10;
                    } elseif ($item['color_id'] == 119) {//官方三分赛车
                        //前端显示倒计时
                        $redis = resis(119);
                        //封盘时间
                        $fp = 60;
                    }elseif ($item['color_id']== 1299) {
                        $redis = resis(1299);
                        $fp = 10;
                    }elseif ($item['color_id'] == 42) {
                        $redis = resis(42);
                        $fp = 15;
                    }elseif ($item['color_id']== 1297) {
                        $redis = resis(1297);
                        $fp = 30;
                    }elseif ($item['color_id'] == 1298) {
                        $redis = resis(1298);
                        $fp = 20;
                    }elseif ($item['color_id'] == 66) {
                        $redis = resis(66);
                        $fp = 10;
                    }
                    if ($redis < $fp) {
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'period'=>$item['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'开奖中'];
                        break;
                    }
                    if ($item['color_id'] == 60) {//腾讯分分彩
                        $dbs = Ssctxffc::find();
                    } elseif ($item['color_id'] == 1) {//重庆时时彩
                        $dbs = Cqssc::find();
                    } elseif ($item['color_id'] == 3) {//天津时时彩
                        $dbs = Tjssc::find();
                    } elseif ($item['color_id'] == 7) {//新疆时时彩
                        $dbs = Xjssc::find();
                    } elseif ($item['color_id'] == 112) {//官方分分彩
                        $dbs = Gfffc::find();
                    } elseif ($item['color_id'] == 113) {//官方两分彩
                        $dbs = Gflfc::find();
                    } elseif ($item['color_id'] == 114) {//官方五分彩
                        $dbs = Gfwfc::find();
                    } elseif ($item['color_id'] == 9) {//广东11选5
                        $dbs = Gd11x5::find();
                    } elseif ($item['color_id'] == 6) {//江西11选5
                        $dbs = Jx11x5::find();
                    } elseif ($item['color_id'] == 115) {//江苏11选5
                        $dbs = Js11x5::find();
                    } elseif ($item['color_id'] == 22) {//上海11选5
                        $dbs = Sh11x5::find();
                    } elseif ($item['color_id'] == 116) {//官方11选5
                        $dbs = Gf11x5::find();
                    } elseif ($item['color_id'] == 23) {//江苏快三
                        $dbs = Jsks::find();
                    } elseif ($item['color_id'] == 24) {//安徽快三
                        $dbs = Ahks::find();
                    } elseif ($item['color_id'] == 37) {//北京快三
                        $dbs = Bjks::find();
                    } elseif ($item['color_id'] == 38) {//广西快三
                        $dbs = Gxks::find();
                    } elseif ($item['color_id'] == 117) {//官方快三
                        $dbs = Gfks::find();
                    } elseif ($item['color_id'] == 27) {//北京PK10
                        $dbs = Bjpk10::find();
                    } elseif ($item['color_id'] == 118) {//官方分分赛车
                        $dbs = Gfffsc::find();
                    }elseif ($item['color_id'] == 119) {//官方三分赛车
                        $dbs = Gfsfsc::find();
                    }elseif ($item['color_id']  == 1299) {
                        $dbs = Ltxffc::find();
                    }elseif ($item['color_id']  == 42) {
                        $dbs = Hg15fc::find();
                    }elseif ($item['color_id']  == 1297) {
                        $dbs = Jndsfc::find();
                    }elseif ($item['color_id']  == 1298) {
                        $dbs = Xjplfc::find();
                    }elseif ($item['color_id'] == 66) {
                        $dbs = Xxl45mc::find();
                    }
                    $ssctxffc = $dbs->orderBy('id desc')->where(['status' => 1])->one();
                    if ($ssctxffc->behind_period!==$item['period']){
                        $result[$key] =['error' => -1,'color'=>$howtoplay['name'],'period'=>$item['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$item['content'],'zongjine'=>$item['zongjine'],'time'=>time(), 'msg' =>'该期已开奖'];
                        break;
                    }
                    $recallnumberrecords =  RecallNumberRecords::find()->orderBy('id desc')->one();
                    if ($recallnumberrecords){
                        $one =substr($recallnumberrecords->order,0,strpos($recallnumberrecords->order,date('d')));
                        if ($one==date('Ym')){
                            $order = $recallnumberrecords->order+1;
                        }else{
                            $order = date('Ymd').'1';
                        }
                    }else{
                        $order = date('Ymd').'1';
                    }
                    $model = new RecallNumberRecords();
                    $model->admin_id = $datas['admin_id'];
                    $model->order = $order;//追单号
                    $model->price = $item['zongjine'];//投注金额
                    $model->color = $item['color_id'];//彩票名称
                    $model->play = $item['play_id'];//玩法
                    $model->starting_period_number = $item['period'];//起始期号
                    $model->enddate = $item['enddate'];//结束期号
                    $model->order_time = $time;//下单时间
                    $model->betting_period = count($datum);//投注期数
                    $model->betting_number = $item['content'];//投注号码
                    $model->status = $item['status'];//中奖停追
                    $model->type = $item['type'];//类型
                    $model->get_on = -1;//是未开奖
                    $model->number = $item['number'];//总投注数量
                    $model->brokerage = 0.00;//佣金
                    $model->mode = $item['mode'];//圆角模式
                    $model->save();
                    $change = new Change();
                    $arr['admin_id'] = $item['admin_id'];
                    $arr['username']=$admin->username;
                    $arr['type'] = '彩票下注';
                    $arr['front_price'] = $admin->price;//变动前的金额
                    $arr['last_price'] = '-'.$item['zongjine'];
                    $arr['price'] = $admin->price-$item['zongjine'];
                    $arr['game_type'] = '彩票';
                    $arr['intro'] = $howtoplay->name.'追单';
                    $change->Change($arr);
                    $reports = Reports::findOne(['admin_id'=>$item['admin_id']]);
                    $reports->all_price += $item['zongjine'];
                    $reports->all_price_num += 1;
                    $reports->save();
                    $aa = $model->id;
                    $content = $item['content'];
                    $zongjine = $item['zongjine'];
                    $period = $item['period'];
                }
                if ($item['price'] <= $admin->price){   //判断用户的金额够不
                    $item['bian'] = $admin->price - $item['price'];//
                    $item['username']=$admin->username;//用户名
                    $item['admin_id']=$datas['admin_id'];//用户id
                    $item['type']=1;//是不是追号
                    $item['recall_number_records_id']=$aa;//追号的id
                    $item['time'] = $time;//时间
                    $lottery = new Lottery();
                    $resul = $lottery->Lottery($item);//调方法来添加  将开奖的
                    if ($resul){
                        $admin->price -= $item['price'];
                        $admin->save();
                        ++$a;//成功
                    }else{
                        ++$b;//失败
                    }
                }else{
                    ++$b;//失败
                }
            }
            if ($a||$b){
                $result[$key] =['error' => 1,'color'=>$howtoplay['name'],'period'=>$period,'play'=>$playmethod->name,'order'=>$order, 'content'=>$content,'zongjine'=>$zongjine,'time'=>$time, 'msg' =>'成功'.$a.'期,失败'.$b.'期'];
            }
        }
        $nu = 0;
        $n = 0;
        foreach ($result as $it){
            if ($it['error']==-1){
                ++$nu;
            }
            ++$n;
        }

        return ['result'=>$result,'success'=>'共计成功'.$n.'注;失败'.$nu.'注'];
    }
    /**App追号
     * @param $data
     */
    public function ZhuihaoApp($data)
    {
        $result = [];
        $time = time();
        $dataq = $data['Lottery'];
        $a = 0;
        $b = 0;
        foreach ($dataq as $k=>$item){
            //彩种总开关
            $howtoplay = HowToPlay::findOne(['id'=>$item['color_id']]);
            if ($howtoplay->status==-1){
                $result['msg'] = '该彩种已停止,追号失败';
                break;
            }elseif ($howtoplay->status==-2){
                $result['msg'] = '该彩种已停售,追号失败';
                break;
            }
            //玩法控制
            $playmethod = PlayMethod::find()->where(['lottery_type'=>$item['color_id']])->andWhere(['number'=>$item['play_id']])->one();
            if ($playmethod->status==0){
                $result['msg'] = '该玩法维护中,追号失败';
                break;
            }elseif ($playmethod->status==-1){
                $result['msg'] = '该玩法已停售,追号失败';
                break;
            }elseif ($playmethod->note){
                if ($item['quantity']>$playmethod->note){
                    $result['msg'] = '注单数不能超过平台限制'.$playmethod->note.'注,当前投注'.$item['quantity'].'注,追号失败';
                    break;
                }
            }
            if ($k==0){//保存在总追号表中
                $redis = new \Redis();
                $redis->connect('127.0.0.1', 6379);
                if (!$item['price'] || !$item['color_id'] || !$item['play_id'] || !$item['price'] || !$item['bonus']) {
                    $result['msg'] = '数据不完整';
                    break;
                }
                if (!token($item['admin_id'],$item['token'])){
                    $result['msg'] = '秘钥错误';
                    break;
                }
                $admin  =Admin::findOne(['id'=>$item['admin_id']]);
                if ($item['zongjine']> $admin->price){
                    $result['msg'] = '余额不足';
                    break;
                }
                if ($item['color_id'] == 60) {//腾讯分分彩
                    //前端显示倒计时
                    $redis = resis(60);
                    $fp = 10;
                } elseif ($item['color_id'] == 1) {//重庆时时彩
                    $redis = resis(1);
                    $fp = 90;
                    //前端显示倒计时
                } elseif ($item['color_id'] == 3) {//天津时时彩
                    //前端显示倒计时
                    $redis = resis(3);
                    $fp = 120;
                } elseif ($item['color_id'] == 7) {//新疆时时彩
                    //前端显示倒计时
                    $redis = resis(7);
                    $fp = 120;
                } elseif ($item['color_id'] == 112) {//官方分分彩
                    //前端显示倒计时
                    $redis = resis(112);
                    $fp = 10;
                } elseif ($item['color_id'] == 113) {//官方两分彩
                    //前端显示倒计时
                    $redis = resis(113);
                    $fp = 20;
                } elseif ($item['color_id'] == 114) {//官方五分彩
                    //前端显示倒计时
                    $redis = resis(114);
                    $fp = 60;
                } elseif ($item['color_id'] == 9) {//广东11选5
                    //前端显示倒计时
                    $redis = resis(9);
                    $fp = 120;
                } elseif ($item['color_id'] == 6) {//江西11选5
                    //前端显示倒计时
                    $redis = resis(6);
                    //封盘时间
                    $fp = 120;
                } elseif ($item['color_id'] == 115) {//江苏11选5
                    //前端显示倒计时
                    $redis = resis(115);
                    $fp = 120;
                } elseif ($item['color_id'] == 22) {//上海11选5
                    //前端显示倒计时
                    $redis = resis(22);
                    $fp = 120;
                } elseif ($item['color_id'] == 116) {//官方11选5
                    //前端显示倒计时
                    $redis =resis(116);
                    $fp = 10;
                } elseif ($item['color_id'] == 23) {//江苏快三
                    //前端显示倒计时
                    $redis = resis(23);
                    $fp = 120;
                } elseif ($item['color_id'] == 24) {//安徽快三
                    //前端显示倒计时
                    $redis = resis(24);
                    $fp = 120;
                } elseif ($item['color_id'] == 37) {//北京快三
                    //前端显示倒计时
                    $redis = resis(37);
                    //封盘时间
                    $fp = 120;
                } elseif ($item['color_id'] == 38) {//广西快三
                    //前端显示倒计时
                    $redis = resis(38);
                    $fp = 120;
                } elseif ($item['color_id'] == 117) {//官方快三
                    //前端显示倒计时
                    $redis = resis(117);
                    $fp = 10;
                } elseif ($item['color_id'] == 27) {//北京PK10
                    //前端显示倒计时
                    $redis = resis(27);
                    $fp = 20;
                } elseif ($item['color_id'] == 118) {//官方分分赛车
                    //前端显示倒计时
                    $redis = resis(118);
                    //封盘时间
                    $fp = 10;
                } elseif ($item['color_id'] == 119) {//官方三分赛车
                    //前端显示倒计时
                    $redis = resis(119);
                    //封盘时间
                    $fp = 60;
                }elseif ($item['color_id']== 1299) {
                    $redis = resis(1299);
                    $fp = 10;
                }elseif ($item['color_id'] == 42) {
                    $redis = resis(42);
                    $fp = 15;
                }elseif ($item['color_id']== 1297) {
                    $redis = resis(1297);
                    $fp = 30;
                }elseif ($item['color_id'] == 1298) {
                    $redis = resis(1298);
                    $fp = 20;
                }elseif ($item['color_id'] == 66) {
                    $redis = resis(66);
                    $fp = 10;
                }
                if ($redis < $fp) {
                    $result['msg'] = '追号失败,正在开奖';
                    break;
                }
                if ($item['color_id'] == 60) {//腾讯分分彩
                    $dbs = Ssctxffc::find();
                } elseif ($item['color_id'] == 1) {//重庆时时彩
                    $dbs = Cqssc::find();
                } elseif ($item['color_id'] == 3) {//天津时时彩
                    $dbs = Tjssc::find();
                } elseif ($item['color_id'] == 7) {//新疆时时彩
                    $dbs = Xjssc::find();
                } elseif ($item['color_id'] == 112) {//官方分分彩
                    $dbs = Gfffc::find();
                } elseif ($item['color_id'] == 113) {//官方两分彩
                    $dbs = Gflfc::find();
                } elseif ($item['color_id'] == 114) {//官方五分彩
                    $dbs = Gfwfc::find();
                } elseif ($item['color_id'] == 9) {//广东11选5
                    $dbs = Gd11x5::find();
                } elseif ($item['color_id'] == 6) {//江西11选5
                    $dbs = Jx11x5::find();
                } elseif ($item['color_id'] == 115) {//江苏11选5
                    $dbs = Js11x5::find();
                } elseif ($item['color_id'] == 22) {//上海11选5
                    $dbs = Sh11x5::find();
                } elseif ($item['color_id'] == 116) {//官方11选5
                    $dbs = Gf11x5::find();
                } elseif ($item['color_id'] == 23) {//江苏快三
                    $dbs = Jsks::find();
                } elseif ($item['color_id'] == 24) {//安徽快三
                    $dbs = Ahks::find();
                } elseif ($item['color_id'] == 37) {//北京快三
                    $dbs = Bjks::find();
                } elseif ($item['color_id'] == 38) {//广西快三
                    $dbs = Gxks::find();
                } elseif ($item['color_id'] == 117) {//官方快三
                    $dbs = Gfks::find();
                } elseif ($item['color_id'] == 27) {//北京PK10
                    $dbs = Bjpk10::find();
                } elseif ($item['color_id'] == 118) {//官方分分赛车
                    $dbs = Gfffsc::find();
                }elseif ($item['color_id'] == 119) {//官方三分赛车
                    $dbs = Gfsfsc::find();
                }elseif ($item['color_id']  == 1299) {
                    $dbs = Ltxffc::find();
                }elseif ($item['color_id']  == 42) {
                    $dbs = Hg15fc::find();
                }elseif ($item['color_id']  == 1297) {
                    $dbs = Jndsfc::find();
                }elseif ($item['color_id']  == 1298) {
                    $dbs = Xjplfc::find();
                }elseif ($item['color_id']  == 66) {
                    $dbs = Xxl45mc::find();
                }
                $ssctxffc = $dbs->orderBy('id desc')->where(['status' => 1])->one();
                if ($ssctxffc->behind_period!==$item['period']){
                    $result['msg'] =  '该期已经开奖,追号失败';
                    break;
                }
                $recallnumberrecords =  RecallNumberRecords::find()->orderBy('id desc')->one();
                if ($recallnumberrecords){
                    $one =substr($recallnumberrecords->order,0,strpos($recallnumberrecords->order,date('d')));
                    if ($one==date('Ym')){
                        $order = $recallnumberrecords->order+1;
                    }else{
                        $order = date('Ymd').'1';
                    }
                }else{
                    $order = date('Ymd').'1';
                }
                $model = new RecallNumberRecords();
                $model->admin_id = $item['admin_id'];
                $model->order = $order;//追单号
                $model->price = $item['zongjine'];//投注金额
                $model->color = $item['color_id'];//彩票名称
                $model->play = $item['play_id'];//玩法
                $model->starting_period_number = $item['period'];//起始期号
                $model->enddate = end($dataq)['period'];//结束期号
                $model->order_time = $time;//下单时间
                $model->betting_period = count($dataq);//投注期数
                $model->betting_number = $item['content'];//投注号码
                $model->status = $item['status'];//中奖停追
                $model->type = $item['type'];//中奖停追
                $model->get_on = -1;//是未开奖
                $model->number = $item['number'];//投注数量
                $model->brokerage = 0.00;//佣金
                $model->mode = $item['mode'];//圆角模式
                $model->save();
                $change = new Change();
                $arr['admin_id'] = $item['admin_id'];
                $arr['username']=$admin->username;
                $arr['type'] = '彩票下注';
                $arr['front_price'] = $admin->price;//变动前的金额
                $arr['last_price'] = '-'.$item['zongjine'];
                $arr['price'] = $admin->price-$item['zongjine'];
                $arr['game_type'] = '彩票';
                $arr['intro'] = $howtoplay->name.'追单';
                $change->Change($arr);
                $reports = Reports::findOne(['admin_id'=>$item['admin_id']]);
                $reports->all_price += $item['zongjine'];
                $reports->all_price_num += 1;
                $reports->save();
                $aa = $model->id;
            }
            $item['bian'] = $admin->price - $item['price'];
            $item['username']=$admin->username;
            $item['type']=1;
            $item['recall_number_records_id']=$aa;
            $item['time'] = $time;
            $lottery = new Lottery();
            $resul = $lottery->Lottery($item);//调方法来添加  将开奖的
            if ($resul){
                $admin->price -= $item['price'];
                $admin->save();
                ++$a;//成功
            }else{
                ++$b;//失败
            }
        }
        if($a||$b){
            $result['msg'] = '成功'.$a.'期,失败'.$b.'期';
        }
        return $result;
    }

    /**三端共有的普通投注
     * @param $v 数据
     * @param $admin_id 用户的id
     * @return array
     */
    public static function Aa($v, $admin_id)
    {
        $admin = Admin::findOne(['id' => $admin_id]);
        if ($v['price'] <= $admin->price) {   //判断用户的金额够不
            $v['bian'] = $admin->price - $v['price'];
            $v['username'] = $admin->username;
            $v['time'] = time();
            $v['recall_number_records_id'] = 0;
            $v['num'] = 0;
            $v['type'] = 0;
            $lottery = new Lottery();
            $resul = $lottery->Lottery($v);//调方法来添加  将开奖的
            if ($resul) {
                $change = new Change();
                $arr['admin_id'] = $v['admin_id'];
                $arr['username'] = $admin->username;
                $arr['type'] = '彩票下注';
                $arr['last_price'] = '-' . $v['price'];
                $arr['price'] = $admin->price - $v['price'];
                $arr['game_type'] = '彩票';
                $arr['intro'] = $v['color'] . $v['period'] . '期下注';
                $arr['time'] = time();
                $change->Change($arr);
                $admin->price -= $v['price'];
                $admin->save();
                $result = ['error' => 1, 'msg' => '购买成功'];
            } else {
                $result = ['error' => -1, 'msg' => '购买失败'];
            }
        } else {
            $result = ['error' => -1, 'msg' => "余额不足"];
        }
        return $result;
    }

    public static function Closing($color)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        if ($color=='官方分分彩'){
            $redis->get('gfffc');
            if($redis->ttl('gfffc')>=10){
                return 1;
            }
        }elseif ($color=='官方快三'){
            $redis->get('gfks');
            if($redis->ttl('gfks')>=10){
                return 1;
            }
        }elseif ($color=='腾讯分分彩'){
            $redis->get('gfffc');
            if($redis->ttl('gfffc')>=10){
                return 1;
            }
        }
        return 0;
    }

    /**时间处理
     * @return mixed
     */
    public static function Time($data)
    {
        if ($data == '周') {
            $w = date("w", time());   //这天是星期几
            $start = mktime(0, 0, 0, date("m"), date("d") - $w, date("Y"));       //创建周开始时间
            $end = mktime(23, 59, 59, date("m"), date("d") - $w + 6, date("Y"));//创建周结束时间
        } elseif ($data == '天') {
            //php获取今日开始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
        } elseif ($data == '月') {
//php获取本月起始时间戳和结束时间戳

            $start = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        } elseif ($data == '昨') {
            //php获取昨日起始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
            $end = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
        } elseif ($data == '上周') {
            //php获取上周起始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
            $end = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
        }
        return ['start' => $start, 'end' => $end];
    }

}