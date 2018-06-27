<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/31
 * Time: 9:19
 */

namespace frontend\models;


use backend\models\Gfffc;
use backend\models\Gflfc;
use backend\models\Gfwfc;
use Codeception\Module\FTP;
use yii\base\Model;

class Test extends Model
{
    public function Test($db)
    {
        if ($db == 112) {//官方分分彩
            $dbs = Gfffc::find();
            $number = rand(0, 9) . ',' . rand(0, 9) . ',' . rand(0, 9) . ',' . rand(0, 9) . ',' . rand(0, 9);
        } elseif ($db == 113) {//官方两分彩
            $dbs = Gflfc::find();
            $string = '0123456789';
            $str =str_shuffle($string);
            $rand= substr($str,5);
            $array = str_split($rand);
            $number = implode(',',$array);
        } elseif ($db == 114) {//官方五分彩
            $dbs = Gfwfc::find();
            $string = '0123456789';
            $str =str_shuffle($string);
            $rand= substr($str,5);
            $array = str_split($rand);
            $number = implode(',',$array);
        } elseif ($db == 116) {//官方11选5
            $dbs = Gf11x5::find();
            $string = '01,02,03,04,05,06,07,08,09,10,11';
            $string  = explode(',',$string);
            shuffle($string);
            $array= array_slice($string,0,5);
            $number = implode(',',$array);
        } elseif ($db == 117) {//官方快三
            $dbs = Gfks::find();
            $one1 = rand(01,06);
            $one2 = rand(01,06);
            $one3 = rand(01,06);
            $number = '0'.$one1.','.'0'.$one2.','.'0'.$one3;
        } elseif ($db == 118) {//官方分分赛车
            $dbs = Gfffsc::find();
            $string = '1,2,3,4,5,6,7,8,9,10';
            $string  = explode(',',$string);
            shuffle($string);
            $number = implode(',',$string);
        } elseif ($db == 119) {//官方三分赛车
            $dbs = Gfsfsc::find();
            $string = '1,2,3,4,5,6,7,8,9,10';
            $string  = explode(',',$string);
            shuffle($string);
            $number = implode(',',$string);
        }
        $ssctxffc = $dbs->orderBy('id desc')->where(['status' => 1])->one();
        if ($ssctxffc->number) {
            $lottery = Lottery::find()->where(['period' => $ssctxffc->behind_period])->andWhere(['color' => $db])->andWhere(['status' => 0])->all();
            $price = Lottery::find()->where(['period' => $ssctxffc->behind_period])->andWhere(['color' => $db])->andWhere(['status' => 0])->sum('price') * 0.8;//开奖池
            $a = 0;//所有用户中奖金额
            $b = 0;//没有中奖的人
            if ($lottery) {
                foreach ($lottery as $key => $item) {
                    $result = Determine::TimeColor($db, $item->play, $number, $item->content);//中奖注数
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
                                $c3 = [];
                                foreach ($result as $it) {
                                    //豹子
                                    $ds = 200.000;
                                    $fandian = (2000 * $item->bonus) / $ds;//用户的返点
                                    //根据返点计算最大值
                                    $max = ($ds * $fandian) / 2000;
                                    $percentage = sprintf('%.5f', (float)(($max - $item->bonus) / $max));//截取小数点后几位  计算后的百分比
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
                                    } else {
                                        $bonus = $item->bonus;
                                    }
                                    ++$c2;//注数
                                    $cc += $bonus;
                                    $c3[] = $bonus;
                                }
                                $result = $c2;//中奖注数
                                $item->bonus = min($c3);//单注奖金
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
                                $percentage = sprintf('%.5f', (float)(($max - $item->bonus) / $max));//截取小数点后几位  计算后的百分比
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
                                $percentage = sprintf('%.5f', (float)(($max - $item->bonus) / $max));//截取小数点后几位  计算后的百分比
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
                                $percentage = sprintf('%.5f', (float)(($max - $item->bonus) / $max));//截取小数点后几位  计算后的百分比
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
                                $percentage = sprintf('%.5f', (float)(($max - $item->bonus) / $max));//截取小数点后几位  计算后的百分比
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
                            $jine = ($item->bonus * $item->multiple * $result) + $item->rebate;
                        }
                        $a += $jine;//用户所有的金钱
                    }else{
                        ++$b;//没有中奖
                    }
                }
                if ($a <= $price) {
                    $nu = $number;
                } else {
                    if (count($lottery)==$b){
                        $nu = $number;
                    }else{
                        $this->Test($db);
                    }
                }
//                var_dump($nu);
                if ($db == 112) {//官方分分彩
                    $model = new Gfffc();
                    $name = '官方分分彩';
                } elseif ($db == 113) {//官方两分彩
                    $model = new Gflfc();
                    $name = '官方两分彩';
                } elseif ($db == 114) {//官方五分彩
                    $model = new Gfwfc();
                    $name = '官方五分彩';
                } elseif ($db == 116) {//官方11选5
                    $model = new Gf11x5();
                    $name = '官方11选5';
                } elseif ($db == 117) {//官方快三
                    $model = new Gfks();
                    $name = '官方快三';
                } elseif ($db == 118) {//官方分分赛车
                    $model = new Gfffsc();
                    $name = '官方分分赛车';
                } elseif ($db == 119) {//官方三分赛车
                    $model = new Gfsfsc();
                    $name = '官方三分赛车';
                }
                $time = date('Ymd');
                $model->name = $name;
                $model->number = $nu;
                $model->time = date('Ymd');
                $model->receive_time = time();
                $model->period = $ssctxffc->behind_period;
                $model->color_id = $db;
                if ($model->period == $time . '1440') {
                    $num = substr($model->period, 0, -4);
                    $model->behind_period = ($num + 1) . '0001';
                    $model->save();//保存
                } else {
                    $model->behind_period = $model->period + 1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
        }
    }
}