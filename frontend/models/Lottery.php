<?php
/**
 * 用户投注记录
 */

namespace frontend\models;

use backend\models\Hg15fc;
use backend\models\Hg15fcWin;
use backend\models\Jndsfc;
use backend\models\JndsfcWin;
use backend\models\Ltxffc;
use backend\models\LtxffcWin;
use backend\models\Rebate;
use backend\models\Reports;
use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\GfffcWin;
use backend\models\Gflfc;
use backend\models\GflfcWin;
use backend\models\Gfwfc;
use backend\models\GfwfcWin;
use backend\models\HowToPlay;
use backend\models\PlayMethod;
use backend\models\XjplfcWin;
use backend\models\Xxl45mc;
use backend\models\Xxl45mcWin;
use yii\db\ActiveRecord;

class Lottery extends ActiveRecord
{
    /** 投注记录 用户点击投注的时候保存的
     * @param array $data 用户购买的数据
     */
    public function Lottery($data = [])
    {
        $zimu = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $shuzi = str_shuffle('123456789');
        $dingdan = substr($shuzi, 0, 5) . substr($zimu, 0, 5);
        $this->admin_id = $data['admin_id'];//用户ID
        $this->username = $data['username'];//用户名
        $this->created_time = $data['time'];//投注时间
        $this->color = $data['color_id'];//彩种
        $this->play = $data['play_id'];//玩法
        $this->period = $data['period'];//期号
        $this->content = $data['content'];//投注内容
        $this->price = $data['price'];//投注金额
        $this->order = $dingdan;//订单号
        $this->rebate = $data['rebate'];//返点
        $this->multiple = $data['multiple'];//倍数
        $this->mode = $data['mode'];//元角分离
        $this->quantity = $data['quantity'];//(投注数量
        $this->bonus = $data['bonus'];//单株奖金
        $this->status = 0;//0未开奖 1中奖 -1撤单
        $this->type = $data['type'];//是不是追号
        if ($data['root']) {
            $this->root = $data['root'];//是不是机器人 
        }
        $this->recall_number_records_id = $data['recall_number_records_id'];//是不是追号
        if ($this->save()) {
            return $this->id;
        } else {
            return 0;
        }
    }


    /**
     * 用户投注后撤单
     * @param array $data 撤单的id
     */
    public function Withdrawal($data)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接

        //查询出这条可以撤单不
        if ($data['id']) {//普通投注
            $lotterys = Lottery::find()->where(['id' => $data['id']])->andWhere(['admin_id' => $data['admin_id']])->andWhere(['type' => 0])->andWhere(['status' => 0])->all();
        } else {
            $RecallNumberRecords = RecallNumberRecords::findOne(['id' => $data['r_id']]);
            $lotterys = Lottery::find()->where(['recall_number_records_id' => $RecallNumberRecords->id])->andWhere(['admin_id' => $RecallNumberRecords->admin_id])->andWhere(['type' => 1])->andWhere(['status' => 0])->all();
        }
        if ($lotterys) {
            foreach ($lotterys as $key => $lottery) {
                if ($key == 0) {
                    if ($lottery->color == 60) {//腾讯分分彩
                        //倒计时
                        $countdown = resis(60);
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 1) {//重庆时时彩
                        //倒计时
                        $countdown =resis(1);
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 3) {//天津时时彩
                        //倒计时
                        $countdown = resis(3);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 7) {//新疆时时彩
                        //倒计时
                        $countdown = resis(7);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 112) {//官方分分彩
                        //倒计时
                        $countdown = resis(112);
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 113) {//官方两分彩
                        //倒计时
                        $countdown = resis(113);
                        //封盘时间
                        $closing_time = 20;
                    } elseif ($lottery->color == 114) {//官方五分彩
                        //倒计时
                        $countdown = resis(114);
                        //封盘时间
                        $closing_time = 60;
                    } elseif ($lottery->color == 9) {//广东11选5
                        //倒计时
                        $countdown = resis(9);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 6) {//江西11选5
                        //倒计时
                        $countdown = resis(6);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 115) {//江苏11选5
                        //倒计时
                        $countdown = resis(115);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 22) {//上海11选5
                        //倒计时
                        $countdown = resis(22);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 116) {//官方11选5
                        //倒计时
                        $countdown = resis(116);
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 23) {//江苏快三
                        //倒计时
                        $countdown = resis(23);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 24) {//安徽快三
                        //倒计时
                        $countdown = resis(24);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 37) {//北京快三
                        //倒计时
                        $countdown = resis(37);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 38) {//广西快三
                        //倒计时
                        $countdown = resis(38);
                        //封盘时间
                        $closing_time = 120;
                    } elseif ($lottery->color == 117) {//官方快三
                        //倒计时
                        $countdown = resis(117);
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 27) {//北京PK10
                        //倒计时
                        $countdown = resis(27);
                        //封盘时间
                        $closing_time = 20;
                    } elseif ($lottery->color == 118) {//官方分分赛车
                        //倒计时
                        $countdown = resis(118);;
                        //封盘时间
                        $closing_time = 10;
                    } elseif ($lottery->color == 119) {//官方三分赛车
                        $countdown = resis(119);
                        //封盘时间
                        $closing_time = 10;
                    }elseif ($lottery->color== 1299) {
                        $countdown = resis(1299);
                        $closing_time = 10;
                    }elseif ($lottery->color == 42) {
                        $countdown = resis(42);
                        $closing_time = 15;
                    }elseif ($lottery->color= 1297) {
                        $countdown = resis(1297);
                        $closing_time = 30;
                    }elseif ($lottery->color == 1298) {
                        $countdown = resis(1298);
                        $closing_time = 20;
                    }elseif ($lottery->color == 66) {
                        $countdown = resis(66);
                        $closing_time = 10;
                    }
                    if ($countdown < $closing_time) {//改期已经锁定
                        return ['error' => -1, 'msg' => '该期已经锁定,撤单失败', 'data' => (object)[]];
                    }
                }
                //=====追号=============//
                if ($lottery->type == 1) {
                    $recallnumberrecords = RecallNumberRecords::find()->where(['admin_id' => $lottery->admin_id])->andWhere(['id' => $lottery->recall_number_records_id])->one();
                    Lottery::updateAll(['status' => -1, 'withdrawal_time' => time(), 'yk' => '已撤单'], ['id' => $lottery->id]);
                    Admin::updateAllCounters(['price' => $lottery->price], ['id' => $lottery->admin_id]);//加钱
                    $recallnumberrecords->withdrawal_amount += $lottery->price;
                    $recallnumberrecords->get_on = 0;//撤单
                    $recallnumberrecords->save();
                    $result['error'] = 1;
                    $result['msg'] = '撤单成功';
                    $result['data'] = (object)[];
                } else {
                    //给用户加钱
                    $admin = Admin::updateAllCounters(['price' => $lottery->price], ['id' => $data['admin_id']]);
                    if ($admin) {
                        $lottery->status = -1;
                        $lottery->yk = '已撤单';
                        $lottery->withdrawal_time = time();
                        $lottery->save();
                        $result['error'] = 1;
                        $result['msg'] = '撤单成功';
                        $result['data'] = (object)[];
                    }
                }
            }
        } else {
            $result['error'] = -1;
            $result['msg'] = '撤单失败';
            $result['data'] = (object)[];
        }
        return $result;
    }

    /**
     * @param $db 开奖的表
     * @param array $data 用户中奖的数据
     */
    public function Win($d, $data = [])
    {

        if ($d == 112) {//官方分分彩
            $db = new GfffcWin();
        } elseif ($d == 60) {//腾讯分分彩
            $db = new SsctxffcWin();
        } elseif ($d == 117) {//官方快三
            $db = new GfksWin();
        } elseif ($d == 118) {//官方分分赛车
            $db = new GfffscWin();
        } elseif ($d == 113) {//官方两分彩
            $db = new GflfcWin();
        } elseif ($d == 114) {//官方五分彩
            $db = new GfwfcWin();
        } elseif ($d == 116) {//官方11选5
            $db = new Gf11x5Win();
        } elseif ($d == 1) {//重庆时时彩
            $db = new CqsscWin();
        } elseif ($d == 3) {//天津时时彩
            $db = new TjsscWin();
        } elseif ($d == 7) {//新疆时时彩
            $db = new XjsscWin();
        } elseif ($d == 9) {//广东11选5
            $db = new Gd11x5Win();
        } elseif ($d == 6) {//江西11选5
            $db = new Jx11x5Win();
        } elseif ($d == 115) {//江苏11选5
            $db = new Js11x5Win();
        } elseif ($d == 22) {//上海11选5
            $db = new Sh11x5Win();
        } elseif ($d == 23) {//江苏快三
            $db = new JsksWin();
        } elseif ($d == 24) {//安徽快三
            $db = new AhksWin();
        } elseif ($d == 37) {//北京快三
            $db = new BjksWin();
        } elseif ($d == 38) {//广西快三
            $db = new GxksWin();
        } elseif ($d == 27) {//北京PK10
            $db = new Bjpk10Win();
        }elseif ($d == 1299) {
            $db = new LtxffcWin();
        }elseif ($d  == 42) {
            $db = new Hg15fcWin();
        }elseif ($d = 1297) {
            $db = new JndsfcWin();
        }elseif ($d == 1298) {
            $db = new XjplfcWin();
        }elseif ($d == 66) {
            $db = new Xxl45mcWin();
        }
        $db->admin_id = $data['admin_id'];//用户id
        $db->username = $data['username'];//用户名
        $db->game_play = $data['game_play'];//游戏玩法
        $db->winning_amount = $data['winning_amount'];//中奖金额
        $db->winning_time = time();//中奖时间
        $db->save();
    }


    /**判断中奖没有
     * @param $db 彩种id
     */
    public function Winning($db)
    {
        //佣金
        $e = Rebate::findOne(['id'=>1]);
        if ($db == 60) {//腾讯分分彩
            $dbs = Ssctxffc::find();
        } elseif ($db == 1) {//重庆时时彩
            $dbs = Cqssc::find();
        } elseif ($db == 3) {//天津时时彩
            $dbs = Tjssc::find();
        } elseif ($db == 7) {//新疆时时彩
            $dbs = Xjssc::find();
        } elseif ($db == 112) {//官方分分彩
            $dbs = Gfffc::find();
        } elseif ($db == 113) {//官方两分彩
            $dbs = Gflfc::find();
        } elseif ($db == 114) {//官方五分彩
            $dbs = Gfwfc::find();
        } elseif ($db == 9) {//广东11选5
            $dbs = Gd11x5::find();
        } elseif ($db == 6) {//江西11选5
            $dbs = Jx11x5::find();
        } elseif ($db == 115) {//江苏11选5
            $dbs = Js11x5::find();
        } elseif ($db == 22) {//上海11选5
            $dbs = Sh11x5::find();
        } elseif ($db == 116) {//官方11选5
            $dbs = Gf11x5::find();
        } elseif ($db == 23) {//江苏快三
            $dbs = Jsks::find();
        } elseif ($db == 24) {//安徽快三
            $dbs = Ahks::find();
        } elseif ($db == 37) {//北京快三
            $dbs = Bjks::find();
        } elseif ($db == 38) {//广西快三
            $dbs = Gxks::find();
        } elseif ($db == 117) {//官方快三
            $dbs = Gfks::find();
        } elseif ($db == 27) {//北京PK10
            $dbs = Bjpk10::find();
        } elseif ($db == 118) {//官方分分赛车
            $dbs = Gfffsc::find();
        } elseif ($db == 119) {//官方三分赛车
            $dbs = Gfsfsc::find();
        }elseif ($db == 1299) {
            $db = Ltxffc::find();
        }elseif ($db  == 42) {
            $db = Hg15fc::find();
        }elseif ($db = 1297) {
            $db =  Jndsfc::find();
        }elseif ($db == 1298) {
            $db =  Xjplfc::find();
        }elseif ($db == 66) {
            $db =  Xxl45mc::find()();
        }
        $ssctxffc = $dbs->orderBy('id desc')->where(['status' => 1])->one();
        if ($ssctxffc->number) {
            $lottery = Lottery::find()->where(['period' => $ssctxffc->period])->andWhere(['color' => $db])->andWhere(['status' => 0])->all();
            foreach ($lottery as $item) {
                $aa = Lottery::find()->where(['id' => $item->id])->one();
                if ($aa->status != 0) {
                    continue;
                }
                //=============合买开始===============//
                if ($item->type == 2) {
                    $buytogether = BuyTogether::find()->where(['period' => $ssctxffc->period])->all();
                    foreach ($buytogether as $b) {
                        if ($b['last_quantity'] != 0 && $b['last_price'] != 0) {
                            BuyTogether::updateAll(['status' => 2], ['id' => $b['id']]);
                            $lo = Lottery::find()->where(['type' => 2])->andWhere(['buy_id' => $b['id']])->andWhere(['period' => $ssctxffc->period])->all();
                            foreach ($lo as $l) {
                                Admin::updateAllCounters(['price' => $l['price']], ['id' => $l['admin_id']]);
                                Lottery::updateAll(['status' => -1, 'yk' => '合买失败'], ['id' => $l['id']]);
                            }
                            continue;
                        }
                    }
                }
                //==========合买结束==============//
                //--------------实例化对象 得到注数开始-------//
                $result = Determine::TimeColor($db, $item->play, $ssctxffc->number, $item->content);
                //------------------结束--------------------//
                $admin = Admin::findOne(['id' => $item->admin_id]);
                $reports = Reports::findOne(['admin_id' => $item->admin_id]);
                if ($result) {
                    //时时彩
                    if ($item->color == 1 || $item->color == 3 || $item->color == 7 || $item->color == 112 || $item->color == 113 || $item->color == 114) {
                        //组合直选
                        if ($item->play == 3 || $item->play == 17 || $item->play == 24 || $item->play == 37 || $item->play == 49 || $item->play == 61) {
                            $ccc = [$item->bonus];
                            for ($i = 1; $i <= $result; ++$i) {
                                array_push($ccc, end($ccc) * 0.1);
                            }
                            $item->bonus = end($ccc);
                            $jine = (array_sum($ccc) * $item->multiple) + $item->rebate;
                            //组三或者组六的
                        } elseif ($item->play == 35 || $item->play == 47 || $item->play == 36 || $item->play == 50 || $item->play == 62 || $item->play == 39 || $item->play == 53 || $item->play == 65 || $item->play == 52 || $item->play == 40 || $item->play == 64 || $item->play == 51 || $item->play == 59 || $item->play == 63) {
                            if (substr($result, 0, 6) == '组六') {
                                $jine = ($item->bonus / 2 * $item->multiple * substr($result, -1, 1)) + $item->rebate;
                                $item->bonus = $item->bonus / 2;//组六的奖金是组三的一半
                            } else {
                                $jine = ($item->bonus * $item->multiple * substr($result, -1, 1)) + $item->rebate;
                            }
                            $result = substr($result, -1, 1);
                            //五星百家乐
                        } elseif ($item->play == 10) {
                            $cc = 0;
                            $c2 = 0;
                            $c3 = 0;
                            foreach ($result as $it) {
                                //豹子
                                $ds = 200.000;
                                $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                                $percentage = sprintf('%.5f', (float)(($ds - $max) / $ds));//截取小数点后几位  计算后的百分比
                                if ($it == '庄闲') {
                                    $ds = 4.000;
                                    //根据返点计算最大值
                                    $max = ($ds * $fandian) / 2000;
                                } elseif ($it == '对子') {
                                    $ds = 20.000;
                                    //根据返点计算最大值
                                    $max = ($ds * $fandian) / 2000;
                                } elseif ($it == '天王') {
                                    $ds = 10.000;
                                    //根据返点计算最大值
                                    $max = ($ds * $fandian) / 2000;
                                }
                                if ($it !== '豹子') {
                                    $bonus = (1 - $percentage) * $max;//计算后的单注奖金
                                }else{
                                    $bonus = $item->bonus;
                                }
                                ++$c2;//注数
                                $cc += $bonus;
                                $c3 = $bonus;
                            }
                            $result = $c2;//中奖注数
                            $item->bonus = $c3;//单注奖金
                            $jine = ($cc * $item->multiple) + $item->rebate;
                            //龙虎合
                        } elseif ($item->play == 96) {
                            $ae = 0;
                            $b = 0;
                            foreach ($result as $k => $c) {
                                $b += $c;
                                if ($k == '龙' || $k == '虎') {
                                    $ae += (($item->bonus / 4.5) * $c);
                                } else {
                                    $ae += (($item->bonus) * $c);
                                }
                            }
                            $item->bonus = $item->bonus / 4.5;
                            $jine = $ae + $item->rebate;
                            $result = $b;
                            //任选混合 和组选三
                        } elseif ($item->play == 106 || $item->play == 108) {
                            $sc = explode(',', $result);
                            if (substr($sc[1], 0, 6) == '组六') {
                                $qian1 = $item->bonus / 2 * $item->multiple * substr($sc[1], 6);
                                $xx1 = substr($sc[1], -1, 1);
                            }
                            if (substr($sc[0], 0, 6) == '组三') {
                                $qian2 = $item->bonus * $item->multiple * substr($sc[0], 6);
                                $xx = substr($sc[0], -1, 1);
                            }
                            $result = $xx1 + $xx;
                            $item->bonus = $item->bonus / 2;
                            $jine = $qian1 + $qian2 + $item->rebate;
                        } else {
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        }
                        //======================11选5========================//
                    } elseif ($item->color == 9 || $item->color == 6 || $item->color == 115 || $item->color == 22 || $item->color == 116) {
                        if ($item->play == 14 || $item->play == 15) {
                            $ds = 922.222;
                            $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                            //根据返点计算最大值
                            $max = ($ds * $fandian) / 2000;
                            $percentage = sprintf('%.5f', (float)(($ds - $max) / $ds));//截取小数点后几位  计算后的百分比
                            if ($result == '4单1双') {
                                $ds = 30.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == '3单2双') {
                                $ds = 6.111;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == '2单3双') {
                                $ds = 4.444;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == '1单4双') {
                                $ds = 12.222;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == '0单5双') {
                                $ds = 153.333;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 3 || $result == 9) {
                                $ds = 32.222;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 4 || $result == 8) {
                                $ds = 14.444;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 5 || $result == 7) {
                                $ds = 10.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 6) {
                                $ds = 8.889;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;//用户的每个最大
                            }
                            if ($result !== '0单5双') {
                                $item->bonus = (1 - $percentage) * $max;//计算后的单注奖金
                            }
                            $result = 1;
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        } else {
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        }
                        //==========快三============//
                    } elseif ($item->color == 23 || $item->color == 117 || $item->color == 24 || $item->color == 37 || $item->color == 38) {
                        //========和值=========//
                        if ($item->play == 12) {
                            $ds = 432.000;
                            $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                            //根据返点计算最大值
                            $max = ($ds * $fandian) / 2000;
                            $percentage = sprintf('%.5f', (float)(($item->bonus - $max) / $ds));//截取小数点后几位  计算后的百分比
                            if ($result == 4 || $result == 17) {
                                $ds = 144.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 5 || $result == 16) {
                                $ds = 27.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 6 || $result == 15) {
                                $ds = 43.200;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 7 || $result == 14) {
                                $ds = 28.800;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 9 || $result == 12) {
                                $ds = 17.280;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 10 || $result == 11) {
                                $ds = 16.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 8 || $result == 13) {
                                $ds = 20.580;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            }
                            if ($result !== 3 || $result !== 18) {
                                $item->bonus = (1 - $percentage) * $max;//计算后的单注奖金
                            }
                            $result = 1;
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        }
                        //========PK10========//
                    } elseif ($item->color == 118 || $item->color == 27 || $item->color == 119) {
                        //================冠亚和值==============//
                        if ($item->play == 43) {
                            $ds = 90.000;
                            $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                            //根据返点计算最大值
                            $max = ($ds * $fandian) / 2000;
                            $percentage = sprintf('%.5f', (float)(($item->bonus - $max) / $ds));//截取小数点后几位  计算后的百分比
                            if ($result == 5 || $result == 6 || $result == 16 || $result == 17) {
                                $ds = 45.000;
                                $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 7 || $result == 8 || $result == 14 || $result == 15) {
                                $ds = 30.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 9 || $result == 10 || $result == 12 || $result == 13) {
                                $ds = 22.500;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 11) {
                                $ds = 18.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            }
                            if ($result !== 5 || $result !== 6 || $result !== 16 || $result !== 17) {
                                $item->bonus = (1 - $percentage) * $max;//计算后的单注奖金
                            }
                            $result = 1;
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                            //==================冠亚季和值==============//
                        } elseif ($item->play == 44) {
                            $ds = 240.000;
                            $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                            //根据返点计算最大值
                            $max = ($ds * $fandian) / 2000;
                            $percentage = sprintf('%.5f', (float)(($item->bonus - $max) / $ds));//截取小数点后几位  计算后的百分比
                            if ($result == 8 || $result == 25) {
                                $ds = 120.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 9 || $result == 24) {
                                $ds = 80.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 10 || $result == 23) {
                                $ds = 60.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 11 || $result == 22) {
                                $ds = 48.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 12 || $result == 21) {
                                $ds = 34.284;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 13 || $result == 20) {
                                $ds = 30.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 14 || $result == 19) {
                                $ds = 26.667;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            } elseif ($result == 15 || $result == 16 || $result == 17 || $result == 18) {
                                $ds = 24.000;
                                //根据返点计算最大值
                                $max = ($ds * $fandian) / 2000;
                            }
                            if ($result !== 6 || $result !== 7 || $result !== 26 || $result !== 27) {
                                $item->bonus = (1 - $percentage) * $max;//计算后的单注奖金
                            }
                            $result = 1;
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        } else {
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        }
                    } else {
                        //中奖金额
                        $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                    }
                    if ($item->type == 2) {
                        $BuyTogether = BuyTogether::findOne(['id' => $item->buy_id]);
                        $jine = ($item->quantity / $BuyTogether->quantity) * $jine;
                    }
                    $data['front_price'] = $admin->price;//变动前的金额
                    //盈利金额
                    $win_price = $jine - $item->price;
                    if($item->type ==0 && $item->list ==1){//榜上跟单
                            if($win_price<0){
                                $bro = 0;
                                $my = 0;
                            }else{
                                //佣金返点
                                $bro = $win_price * $e->brokerage;
                                //平台返点
                                $my = $win_price * $e->rebate;
                            }
                            $admin->price = $admin->price + $jine - $bro - $my;
                            $bang = Lottery::findOne(['id'=>$item->buy_id]);
                            $bang_name = Admin::findOne(['id'=>$bang->admin_id]);
                            $bang_name->price += $bro;
                            $bang_name->save();
                            $admin->save();
                        }elseif ($item->type ==2 && $item->list ==2){//合买跟单
                            if($win_price<0){
                                $bro = 0;
                                $my = 0;
                                }else{
                                //佣金返点
                                $bro = $win_price * $e->brokerage;
                                //平台返点
                                $my = $win_price * $e->rebate;
                             }
                            $admin->price = $admin->price + $jine - $bro - $my;
                            $bang = BuyTogether::findOne(['id'=>$item->buy_id]);
                            $bang_name = Admin::findOne(['id'=>$bang->admin_id]);
                            $bang_name->price += $bro;
                            $bang_name->save();
                            $admin->save();
                        }else{
                            $bro = 0;
                            $my = 0;
                            $admin->price = $admin->price + $jine - $bro - $my;
                            $admin->save();
                        }


                    //帐变
                    $change = new Change();
                    $data['admin_id'] = $item->admin_id;
                    $data['username'] = $item->username;
                    $data['type'] = '彩票中奖';
                    $data['last_price'] = $jine;//变动金额
                    $data['price'] = $admin->price;//变动后的金额
                    $data['game_type'] = '彩票';
                    $data['time'] = time();
                    $PlayMethod = PlayMethod::find()->where(['lottery_type' => $item->color])->andWhere(['number' => $item->play])->one();
                    $data['intro'] = $PlayMethod->name . $item->period . '期派彩';//备注
                    $change->Change($data);


                    //中奖一注
                    if ($admin) {
                        if ($jine > 0) {
                            //============中奖公告=========//
                            $dat = ['admin_id' => $item->admin_id, 'username' => $item->username, 'winning_amount' => $jine, 'game_play' => $PlayMethod->name];
                            $this->Win($db, $dat);
                        }
                        //==========追号=================//
                        if ($item->type == 1) {
                            $recallnumberrecords = RecallNumberRecords::find()->where(['admin_id' => $item->admin_id])->andWhere(['id' => $item->recall_number_records_id])->one();
                            if ($recallnumberrecords->status == 0) {//中奖就停
                                $lottery = Lottery::find()->where(['admin_id' => $item->admin_id])->andWhere(['recall_number_records_id' => $recallnumberrecords->id])->andWhere(['>', 'period', $item->period])->all();
                                $time = time();
                                foreach ($lottery as $it) {
                                    Lottery::updateAll(['status' => -1, 'withdrawal_time' => $time, 'yk' => '已撤单'], ['id' => $it->id]);
                                    Admin::updateAllCounters(['price' => $it->price], ['id' => $it->admin_id]);//加钱
                                    $recallnumberrecords->withdrawal_amount += $it->price;
                                }
                                $recallnumberrecords->get_on = 0;//中奖就撤单
                            } else {
                                $recallnumberrecords->get_on = 1;
                            }
                            $recallnumberrecords->winning_amount += $jine;//中奖金额
                            $recallnumberrecords->winning_note += substr($result, -1, 1);//中奖注数
                            $recallnumberrecords->completion_period = $recallnumberrecords->completion_period + 1;//完成期数
                            $recallnumberrecords->completed_amount = $recallnumberrecords->completed_amount + $item->price;//完成金额
                            $recallnumberrecords->yk_amount += $jine - $item->price;//盈亏金额
                            $recallnumberrecords->save();
                        }
                        //==========追号===============//
                        //==========修改Lottery里面的数据==========//
                        Lottery::updateAll(['winning_note' => $result, 'status' => 1, 'yk' => round($jine - $item->price, 3), 'number' => $ssctxffc->number, 'win_price' => round($jine, 3), 'bonus' => sprintf('%.3f', (float)$item->bonus)], ['id' => $item->id]);//投注记录中的盈亏
                        $reports->price += $jine;
                        $reports->yk += round($jine - $item->price);
                        $reports->game_price += round($jine - $item->price);
                        $reports->save();
                    }
                } else {
                    Lottery::updateAll(['winning_note' => 0, 'status' => 2, 'yk' => $item->rebate - $item->price, 'number' => $ssctxffc->number], ['id' => $item->id]);//投注记录中的盈亏
                    $reports->price += $item->price;
                    $reports->yk += -$item->price;
                    $reports->game_price += -$item->price;
                    $reports->save();
                    //没有中奖
                    $admin->price += $item->rebate;
                    $admin->save();
                    if ($item->type == 1) {//追号
                        $recallnumberrecords = RecallNumberRecords::find()->where(['admin_id' => $item->admin_id])->andWhere(['id' => $item->recall_number_records_id])->one();
                        if ($recallnumberrecords->get_on == -1) {//未开奖
                            $recallnumberrecords->get_on = -2;//未中奖
                        }
                        $recallnumberrecords->completion_period = $recallnumberrecords->completion_period + 1;
                        $recallnumberrecords->completed_amount = $recallnumberrecords->completed_amount + $item->price;
                        $recallnumberrecords->yk_amount += (-$item->price);
                        $recallnumberrecords->save();
                    }
                }
                BuyTogether::updateAll(['number' => $ssctxffc->number, 'status' => '已开奖'], ['id' => $item->buy_id]);
            }
            /**
             * 看用户买的期号是否有
             * 没有就给用户返还金额
             */
            //$this->Retur();
        }
    }

    public function Retur()
    {
        $lottery = Lottery::find()->where(['status' => 0])->all();
        foreach ($lottery as $item) {
            $admin = Admin::findOne(['id' => $item->admin_id]);
            $admin->price += $item->price;
            $admin->save();
        }
    }


    /**
     * (3)到时间点,调这个接口,判断用购买的号码是否中奖
     */
    public function Ssctxffc()
    {
        $redis = new \Redis();
        if ($redis->ttl('time') <= 56) {
            $result = $this->Winning();//判断中奖没有
            if ($result) {
                $a = [];
                foreach ($result as $v) {//将用户分类
                    $re[$v['admin_id']][] = $v;
                }
                foreach ($re as $k => $item) {
                    $b = 0;
                    foreach ($re[$k] as $value) {
                        $a[$value['admin_id']]['admin_id'] = $k;
                        $a[$value['admin_id']]['yk'] = round($b += $value['yk'], 3);
                    }
                }
                //每期每个用户购彩的数据 相加计算出盈亏
                return messages('成功', 1, $a);
            }
        }
    }


    /**
     * 合买计算是否中奖
     * @param $period
     */
    public function BuyCount($period)
    {
        //根据期号来查数据库那些用户购买了这期
        $model = BuyTogethers::find()->where(['period' => $period])->all();
        //如果有,计算出每个用户金额  没有不做任何操作
        if ($model) {
            //循环取出所有用户ID
            foreach ($model as $v) {
                $admin = Admin::find()->where(['id' => $v['admin_id']])->all();
                foreach ($admin as $k) {
                    $price = $v['my'] / $v['quantity'];
                    $ss = $v['bonus'] * $price;
                    Admin::updateAll(['price' => $ss], ['id' => $k['id']]);
                }
            }
        }
    }


}