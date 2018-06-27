<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/26 0026
 * Time: 10:20
 */

namespace frontend\controllers;


use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\Gflfc;
use backend\models\Gfwfc;
use backend\models\Hg15fc;
use backend\models\Jndsfc;
use backend\models\Ltxffc;
use backend\models\Rebate;
use backend\models\Reports;
use backend\models\Xxl45mc;
use frontend\models\Ahks;
use frontend\models\Bjks;
use frontend\models\Bjpk10;
use frontend\models\Buy;
use frontend\models\Change;
use frontend\models\Cqssc;
use frontend\models\Gd11x5;
use frontend\models\Gf11x5;
use frontend\models\Gfffsc;
use frontend\models\Gfks;
use frontend\models\Gfsdwf;
use frontend\models\Gfsfsc;
use frontend\models\Gxks;
use frontend\models\Js11x5;
use frontend\models\Jsks;
use frontend\models\Jx11x5;
use frontend\models\Lottery;
use frontend\models\BuyAll;
use frontend\models\BuyMonth;
use frontend\models\BuyTogethers;
use frontend\models\BuyWeek;
use frontend\models\NoAll;
use frontend\models\NoMonth;
use frontend\models\NoWeek;
use frontend\models\RecallNumberRecords;
use frontend\models\Sh11x5;
use frontend\models\SingleAll;
use frontend\models\SingleMonth;
use frontend\models\SingleWeek;
use frontend\models\SscgfffcWin;
use frontend\models\Ssctxffc;
use frontend\models\Tjssc;
use frontend\models\Txffc;
use frontend\models\Xjplfc;
use frontend\models\Xjssc;
use backend\models\HowToPlay;
use backend\models\PlayMethod;
use yii\web\Controller;
use yii\web\Response;

header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");

/**
 * 神榜
 * Class Api4Controller
 * @package frontend\controllers
 */
class Api4Controller extends Controller
{
    //解决网页报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    public function actionTime()
    {
        $s = strtotime(date('Y-m-d H:i:s', time()));
        var_dump($s);
    }


    /**
     * 榜单接口数据
     */
    public function actionGod()
    {
        $model1 = SingleWeek::find()->orderBy('rate desc')->limit(10)->all();//单期周榜
        foreach ($model1 as $v1) {
            $v1->username = mb_substr($v1->username, 0, 3, 'utf-8') . '***';
            $v1->rate = $v1->rate . '%';
        }
        $model2 = SingleMonth::find()->orderBy('rate desc')->limit(10)->all();//单期月榜
        foreach ($model2 as $v2) {
            $v2->username = mb_substr($v2->username, 0, 3, 'utf-8') . '***';
            $v2->rate = $v2->rate . '%';
        }
        $model3 = SingleAll::find()->orderBy('rate desc')->limit(10)->all();//单期总榜
        foreach ($model3 as $v3) {
            $v3->username = mb_substr($v3->username, 0, 3, 'utf-8') . '***';
            $v3->rate = $v3->rate . '%';
        }
        $model4 = BuyWeek::find()->orderBy('rate desc')->limit(10)->all();//合买周榜
        foreach ($model4 as $v4) {
            $v4->username = mb_substr($v4->username, 0, 3, 'utf-8') . '***';
            $v4->rate = $v4->rate . '%';
        }
        $model5 = BuyMonth::find()->orderBy('rate desc')->limit(10)->all();//合买月榜
        foreach ($model5 as $v5) {
            $v5->username = mb_substr($v5->username, 0, 3, 'utf-8') . '***';
            $v5->rate = $v5->rate . '%';
        }
        $model6 = BuyAll::find()->where(['>', 'rate', 0])->orderBy('rate desc')->limit(10)->all();//合买总榜
        foreach ($model6 as $v6) {
            $v6->username = mb_substr($v6->username, 0, 3, 'utf-8') . '***';
            $v6->rate = $v6->rate . '%';
        }
        $model7 = NoWeek::find()->orderBy('rate desc')->limit(10)->all();//追号周榜
        foreach ($model7 as $v7) {
            $v7->username = mb_substr($v7->username, 0, 3, 'utf-8') . '***';
            $v7->rate = $v7->rate . '%';
        }
        $model8 = NoMonth::find()->orderBy('rate desc')->limit(10)->all();//追号月榜
        foreach ($model8 as $v8) {
            $v8->username = mb_substr($v8->username, 0, 3, 'utf-8') . '***';
            $v8->rate = $v8->rate . '%';
        }
        $model9 = NoAll::find()->orderBy('rate desc')->limit(10)->all();//追号总榜
        foreach ($model9 as $v9) {
            $v9->username = mb_substr($v9->username, 0, 3, 'utf-8') . '***';
            $v9->rate = $v9->rate . '%';
        }
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['week']['single'] = $model1;//单期周榜
        $result['data']['week']['buy'] = $model4;//合买周榜
        $result['data']['week']['no'] = $model7;//追号周榜
        $result['data']['month']['single'] = $model2;//单期月榜
        $result['data']['month']['buy'] = $model5;//合买月榜
        $result['data']['month']['no'] = $model8;//追好月榜
        $result['data']['all']['single'] = $model3;//单期总榜
        $result['data']['all']['buy'] = $model6;//合买总榜
        $result['data']['all']['no'] = $model9;//追号总榜
        return $result;
    }


    /**
     * 用户点击榜单获取最后一期
     */
    public function actionMessage()
    {
        $admin_id = \Yii::$app->request->post('admin_id');
        $type = \Yii::$app->request->post('type');
        if ($type == 0) {//单期帮
            $lottery = Lottery::find()->where(['admin_id' => $admin_id])->andWhere(['type' => 0])->orderBy('id DESC')->asArray()->one();
        } elseif ($type == 2) {//合买榜
            $lottery = Lottery::find()->where(['admin_id' => $admin_id])->andWhere(['type' => 2])->orderBy('id DESC')->asArray()->one();
        } elseif ($type == 1) {//追号
            $lottery = Lottery::find()->where(['admin_id' => $admin_id])->andWhere(['type' => 1])->orderBy('id desc')->asArray()->one();
        }
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['list']['color_id'] = $lottery['color'];
        $howtoplay = HowToPlay::findOne(['id' => $lottery['color']]);//彩种
        $playmethod = PlayMethod::find()->where(['lottery_type' => $lottery['color']])->andWhere(['number' => $lottery['play']])->one();
        $lottery['color'] = $howtoplay->name;
        $lottery['play'] = $playmethod->name;
        $lottery['color_id'] = $result['data']['list']['color_id'];
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $to = $redis->ttl($howtoplay->name);
        $lottery['control'] = time() + $to;
        $result['data']['list'] = $lottery;
        return $result;
    }


    /**
     * 用户点击合买榜单获取最后一期 并且跟单
     */
    public function actionBuy()
    {
        $query = \Yii::$app->request->post();
        $admin_ids = \Yii::$app->request->post('id');
        $admin_id = \Yii::$app->request->post('admin_id');
        $admin = Admin::findOne(['id' => $admin_id]);
        $adminss = Admin::findOne(['id' => $admin_ids]);
        if ($query['type'] == 1) {
            $recallnumberrecords = RecallNumberRecords::find()->where(['admin_id' => $admin_id, 'get_on' => 1])->orderBy('id desc')->one();
            if ($recallnumberrecords->color == 112) {//官方分分彩
                $gfffc = Gfffc::find()->orderBy('id DESC')->one();
                $countdown = resis(112);
                $closing = 10;
            } elseif ($recallnumberrecords->color == 60) {//腾讯分分彩
                $gfffc = Txffc::find()->orderBy('id DESC')->one();
                $countdown = resis(60);
                $closing = 10;
            } elseif ($recallnumberrecords->color == 113) {//官方两分彩
                $gfffc = Gflfc::find()->orderBy('id DESC')->one();
                $countdown = resis(113);
                $closing = 20;
            } elseif ($recallnumberrecords->color == 114) {//官方五分彩
                $gfffc = Gfwfc::find()->orderBy('id DESC')->one();
                $countdown = resis(114);
                $closing = 60;
            } elseif ($recallnumberrecords->color == 1) {//重庆时时彩
                $gfffc = Cqssc::find()->orderBy('id DESC')->one();
                $countdown = resis(1);
                $closing = 90;
            } elseif ($recallnumberrecords->color == 3) {//天津时时彩
                $gfffc = Tjssc::find()->orderBy('id DESC')->one();
                $countdown = resis(3);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 7) {//新疆时时彩
                $gfffc = Xjssc::find()->orderBy('id DESC')->one();
                $countdown = resis(7);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 9) {//广东11选5
                $gfffc = Gd11x5::find()->orderBy('id DESC')->one();
                $countdown = resis(9);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 6) {//江西11选5
                $gfffc = Jx11x5::find()->orderBy('id DESC')->one();
                $countdown = resis(6);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 115) {//江苏11选5
                $gfffc = Js11x5::find()->orderBy('id DESC')->one();
                $countdown = resis(115);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 22) {//上海11选5
                $gfffc = Sh11x5::find()->orderBy('id DESC')->one();
                $countdown = resis(22);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 116) {
                $gfffc = Gf11x5::find()->orderBy('id DESC')->one();
                $countdown = resis(116);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 23) {
                $gfffc = Jsks::find()->orderBy('id DESC')->one();
                $countdown = resis(23);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 24) {
                $gfffc = Ahks::find()->orderBy('id DESC')->one();
                $countdown = resis(24);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 37) {
                $gfffc = Bjks::find()->orderBy('id DESC')->one();
                $countdown = resis(37);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 38) {
                $gfffc = Gxks::find()->orderBy('id DESC')->one();
                $countdown = resis(38);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 117) {
                $gfffc = Gfks::find()->orderBy('id DESC')->one();
                $countdown = resis(117);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 27) {
                $gfffc = Bjpk10::find()->orderBy('id DESC')->one();
                $countdown = resis(27);
                $closing = 120;
            } elseif ($recallnumberrecords->color == 118) {
                $gfffc = Gfffsc::find()->orderBy('id DESC')->one();
                $countdown = resis(118);
                $closing = 10;
            } elseif ($recallnumberrecords->color == 119) {
                $gfffc = Gfsfsc::find()->orderBy('id DESC')->one();
                $countdown = resis(119);
                $closing = 120;
            }elseif ($recallnumberrecords->color == 1299) {
                $gfffc = Ltxffc::find()->orderBy('id DESC')->one();
                $countdown = resis(1299);
                $closing = 10;
            }elseif ($recallnumberrecords->color == 42) {
                $gfffc = Hg15fc::find()->orderBy('id DESC')->one();
                $countdown = resis(42);
                $closing = 15;
            }elseif ($recallnumberrecords->color == 1297) {
                $gfffc = Jndsfc::find()->orderBy('id DESC')->one();
                $countdown = resis(1297);
                $closing = 30;
            }elseif ($recallnumberrecords->color == 1298) {
                $gfffc = Xjplfc::find()->orderBy('id DESC')->one();
                $countdown = resis(1298);
                $closing = 20;
            }elseif ($recallnumberrecords->color == 66) {
                $gfffc = Xxl45mc::find()->orderBy('id DESC')->one();
                $countdown = resis(66);
                $closing = 10;
            }
            if ($countdown < $closing) {
                return messages('开奖中,请稍后...');
            }
            $Lottery = Lottery::find()->where(['type' => 1, 'recall_number_records_id' => $recallnumberrecords->id, 'period' => $gfffc->behind_period, 'status' => 0])->one();
            if (!$Lottery) {
                return messages('购买失败，用户没有购买...');
            }
        } else {
            $lottery = Lottery::find()->where(['admin_id' => $admin_id])->andWhere(['type' => $query['type']])->orderBy('id DESC')->one();
            if ($lottery->color == 112) {
                $gfffc = Gfffc::find()->orderBy('id DESC')->one();
                if (resis(112) < 10) {
                    return messages('购买失败，官方分分彩已封盘');
                }
            } elseif ($lottery->color == 60) {
                $gfffc = Txffc::find()->orderBy('id DESC')->one();
                if (resis(60) < 10) {
                    return messages('购买失败，腾讯分分彩已封盘');
                }
            } elseif ($lottery->color == 113) {
                $gfffc = Gflfc::find()->orderBy('id DESC')->one();
                if (resis(113) < 20) {
                    return messages('购买失败，官方两分彩已封盘');
                }
            } elseif ($lottery->color == 114) {
                $gfffc = Gfwfc::find()->orderBy('id DESC')->one();
                if (resis(114) < 60) {
                    return messages('购买失败，官方五分彩已封盘');
                }
            } elseif ($lottery->color == 1) {
                $gfffc = Cqssc::find()->orderBy('id DESC')->one();
                if (resis(1) < 90) {
                    return messages('购买失败，重庆时时彩已封盘');
                }
            } elseif ($lottery->color == 3) {
                $gfffc = Tjssc::find()->orderBy('id DESC')->one();
                if (resis(3) < 120) {
                    return messages('购买失败，天津时时彩已封盘');
                }
            } elseif ($lottery->color == 7) {
                $gfffc = Xjssc::find()->orderBy('id DESC')->one();
                if (resis(7) < 120) {
                    return messages('购买失败，新疆时时彩已封盘');
                }
            } elseif ($lottery->color == 9) {
                $gfffc = Gd11x5::find()->orderBy('id DESC')->one();
                if (resis(9) < 120) {
                    return messages('购买失败，广东11选5已封盘');
                }
            } elseif ($lottery->color == 6) {
                $gfffc = Jx11x5::find()->orderBy('id DESC')->one();
                if (resis(6) < 120) {
                    return messages('购买失败，江西11选5已封盘');
                }
            } elseif ($lottery->color == 115) {
                $gfffc = Js11x5::find()->orderBy('id DESC')->one();
                if (resis(115) < 120) {
                    return messages('购买失败，江苏11选5已封盘');
                }
            } elseif ($lottery->color == 22) {
                $gfffc = Sh11x5::find()->orderBy('id DESC')->one();
                if (resis(22) < 120) {
                    return messages('购买失败，上海11选5已封盘');
                }
            } elseif ($lottery->color == 116) {
                $gfffc = Gf11x5::find()->orderBy('id DESC')->one();
                if (resis(116) < 120) {
                    return messages('购买失败，官方11选5已封盘');
                }
            } elseif ($lottery->color == 23) {
                $gfffc = Jsks::find()->orderBy('id DESC')->one();
                if (resis(23) < 120) {
                    return messages('购买失败，江苏快三已封盘');
                }
            } elseif ($lottery->color == 24) {
                $gfffc = Ahks::find()->orderBy('id DESC')->one();
                if (resis(24) < 120) {
                    return messages('购买失败，安徽快三已封盘');
                }
            } elseif ($lottery->color == 37) {
                $gfffc = Bjks::find()->orderBy('id DESC')->one();
                if (resis(37) < 120) {
                    return messages('购买失败，北京快三已封盘');
                }
            } elseif ($lottery->color == 38) {
                $gfffc = Gxks::find()->orderBy('id DESC')->one();
                if (resis(38) < 120) {
                    return messages('购买失败，广西快三已封盘');
                }
            } elseif ($lottery->color == 117) {
                $gfffc = Gfks::find()->orderBy('id DESC')->one();
                if (resis(117) < 120) {
                    return messages('购买失败，官方快三已封盘');
                }
            } elseif ($lottery->color == 27) {
                $gfffc = Bjpk10::find()->orderBy('id DESC')->one();
                if (resis(27) < 120) {
                    return messages('购买失败，北京PK10已封盘');
                }
            } elseif ($lottery->color == 118) {
                $gfffc = Gfffsc::find()->orderBy('id DESC')->one();
                if (resis(118) < 10) {
                    return messages('购买失败，官方分分赛车已封盘');
                }
            } elseif ($lottery->color == 119) {
                $gfffc = Gfsfsc::find()->orderBy('id DESC')->one();
                if (resis(119) < 120) {
                    return messages('购买失败，官方三分赛车已封盘');
                }
            }elseif ($query['color_id'] == 1299) {
                $gfffc = Ltxffc::find()->orderBy('id DESC')->one();
                if (resis(1299) < 10) {
                    return messages('购买失败，老腾讯分分彩已封盘');
                }

            }elseif ($query['color_id'] == 42) {
                $gfffc = Hg15fc::find()->orderBy('id DESC')->one();
                if (resis(42) < 15) {
                    return messages('购买失败，韩国1.5分彩已封盘');
                }

            }elseif ($query['color_id'] == 1297) {
                $gfffc = Jndsfc::find()->orderBy('id DESC')->one();
                if (resis(1297) < 30) {
                    return messages('购买失败，加拿大三分彩已封盘');
                }

            }elseif ($query['color_id'] == 1298) {
                $gfffc = Xjplfc::find()->orderBy('id DESC')->one();
                if (resis(1298) < 20) {
                    return messages('购买失败，新加坡两分彩已封盘');
                }

            }elseif ($query['color_id'] == 66) {
                $gfffc = Xxl45mc::find()->orderBy('id DESC')->one();
                if (resis(66) < 10) {
                    return messages('购买失败，新西兰45秒彩已封盘');
                }

            }
            if ($lottery->period !== $gfffc->behind_period) {
                return messages('购买失败，用户没有购买');
            }
        }
        if ($query['price']  > $adminss->price) {
            return messages('购买失败，余额不足');
        }
        $E = Rebate::findOne(['id'=>1]);
        $lotterys = new Lottery();
        $lotterys->admin_id = $admin_ids;
        $lotterys->username = $adminss->username;
        $lotterys->created_time = time();
        $lotterys->color = $lottery->color;
        $lotterys->play = $lottery->play;
        $lotterys->period = $gfffc->behind_period;
        $lotterys->content = $lottery->content;
        $lotterys->price = $query['price'];
        $lotterys->yk = '- -';
        $lotterys->order = $lottery->order;
        $lotterys->multiple = $query['multiple'];
        $lotterys->mode = $query['mode'];
        $lotterys->quantity = $query['quantity'];
        $lotterys->number = '';
        $lotterys->bonus = $lottery->bonus;
        $lotterys->status = 0;
        $lotterys->type = 0;
        $lotterys->buy_id = $lottery->id;//跟单ID
        $lotterys->list = 1; //1为榜跟单
        $lotterys->withdrawal_time = '';
        $lotterys->recall_number_records_id = '';
        $lotterys->brokerage = $query['price'] * $E->brokerage;
        $change = new Change();
        $arr['admin_id'] = $admin_ids;
        $arr['username'] = $adminss->username;
        $arr['type'] = '彩票下注';
        $arr['last_price'] = '-' . $query['price'];
        $arr['price'] = $adminss->price - $query['price'];
        $arr['front_price'] = $admin->price;
        $arr['game_type'] = '彩票';
        $arr['intro'] = $lottery->color . $lottery->period . '期下注';
        $change->Change($arr);
        $adminss->price -= $query['price'];
        $adminss->save();
        $reports = Reports::findOne(['admin_id'=>$arr['admin_id']]);
        $reports->out_price += $query['price'];
        $reports->out_price_num += 1;
        $reports->save();
        $lotterys->save();
        return messages('购买成功', 1);
    }

}