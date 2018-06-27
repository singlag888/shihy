<?php
/**
 * 机器人
 */

namespace frontend\models;


use backend\models\Admin;
use backend\models\Gfffc;
use yii\base\Model;

class Robot extends Model
{
    /**生成机器人
     * @return mixed
     */
    public static function Robot()
    {
        $arr = ['14700285410','13666076631','dcq58046829','13620587631','18124867889','jh6043538','13625086637','13749052866','18925480605','ljt70704682','f13543071158','tyl25769508','by32369530','ldh5420158','gfc204685303','zxy5659834','lm28295046',
            'jmh104689208','lfl28648950','wangyong002','zhangkun1345','zhouxun1','liuxiu366','huangjie028','xianggang158','jiangjian666','tanjun508','zhangcheng542','liuyutong886','hugua005','huanghong508','wuyue33','jiangxian5656','liming1187','zhangling047',
            'duqian987','quguangping250','guangtou023','liuzong4310'];
        for ($i=1;$i<=39;++$i){
            $admin = new Admin();
            $admin->username = array_pop($arr);
            $admin->created_time = time();
            $admin->created_ip = '127.0.0.1';
            $admin->price = 10000000.000;
            $admin->type = 3;
            $admin->save(false);
            $singleweek = new SingleWeek();
            $singleweek->admin_id = $admin->id;
            $singleweek->username = $admin->username;
            $singleweek->save();

            $singlemonth = new SingleMonth();
            $singlemonth->admin_id = $admin->id;
            $singlemonth->username = $admin->username;
            $singlemonth->save();

            $singlemonth = new SingleAll();
            $singlemonth->admin_id = $admin->id;
            $singlemonth->username = $admin->username;
            $singlemonth->save();


            $buyweek = new BuyWeek();
            $buyweek->admin_id = $admin->id;
            $buyweek->username = $admin->username;
            $buyweek->save();

            $buymonth = new BuyMonth();
            $buymonth->admin_id = $admin->id;
            $buymonth->username = $admin->username;
            $buymonth->save();

            $buyall = new BuyAll();
            $buyall->admin_id = $admin->id;
            $buyall->username = $admin->username;
            $buyall->save();

            $noweek = new NoWeek();
            $noweek->admin_id =$admin->id;
            $noweek->username = $admin->username;
            $noweek->save();

            $nomonth = new NoMonth();
            $nomonth->admin_id = $admin->id;
            $nomonth->username = $admin->username;
            $nomonth->save();

            $noall = new NoAll();
            $noall->admin_id = $admin->id;
            $noall->username =$admin->username;
            $noall->save();
        }
    }

    /**
     * 机器人购买
     */
    public function RobotBuy($color)
    {

        $arr1 = [1,2,3,4];
        $arr2 = [5,6,7,8];
        $arr3 = [9,10,11,12,13];
        $arr4 = [14,15,16,17];
        $arr5 = [18,19,20,21,22];
        $arr6 = [23,24,25,26];
        $arr7 = [27,28,29,30];
        $arr8 = [31,32,33,34];
        $arr9 = [36,37,38];
        $arr10 = [39,40,41,42];
        $arr11=[43,44,45,46];
        $arr12=[48,49,50];
        $arr13=[51,52,53,54];
        $arr14=[55,56,57,58];
        $arr15=[60,61,62];
        $arr16=[63,64,65,66];
        $arr17=[68,69,70];
        $arr18=[70,71,72,73];
        $arr19=[74,75,76,77];
        $arr20=[78,79,77,80];
        $arr21 =[82,83,84];
        $arr22 =[85,86,87,88];
        $arr23 =[89,90,91,92];
        $arr24 =[93,94,95];
        $arr25 =[14,25,26];
        $arr26 =[1,22,24];
        $arr27 =[31,18,19,14];
        $arr28 =[12,17,16];
        $arr29 =[22,23,26];
        $arr30 =[1,4,5,6];
        $arr31 =[7,8,9,10];
        $arr32 =[11,4,5,6];
        $arr33 =[12,23,25,26];
        $arr34 =[14,18,19,41];
        $arr35 =[77,78,12,14];
        $arr36 =[15,19,50,51];
        $arr37 =[52,58,59,54];
        $arr38 =[14,66,71];
        $arr39 =[1,14,70];
        $admin = Admin::find()->where(['type'=>3])->orderBy('id desc')->asArray()->all();
        $mode = ['元','角','分','厘'];
        foreach ($admin as $item){
            if ($item['id']==1076){
                $play =  $arr1[array_rand($arr1)];//玩法
            }elseif ($item['id']==1075){
                $play =  $arr2[array_rand($arr2)];//玩法
            }elseif ($item['id']==1074){
                $play =  $arr3[array_rand($arr3)];//玩法
            }elseif ($item['id']== 1073){
                $play =  $arr4[array_rand($arr4)];//玩法
            }elseif ($item['id']==1072){
                $play =  $arr5[array_rand($arr5)];//玩法
            }elseif ($item['id']== 1071){
                $play =  $arr6[array_rand($arr6)];//玩法
            }elseif ($item['id']==1070){
                $play =  $arr7[array_rand($arr7)];//玩法
            }elseif ($item['id']==1069){
                $play =  $arr8[array_rand($arr8)];//玩法
            }elseif ($item['id']==1068){
                $play =  $arr9[array_rand($arr9)];//玩法
            }elseif ($item['id']==1067){
                $play =  $arr10[array_rand($arr10)];//玩法
            }elseif ($item['id']==1066){
                $play =  $arr11[array_rand($arr11)];//玩法
            }elseif ($item['id']==1065){
                $play =  $arr12[array_rand($arr12)];//玩法
            }elseif ($item['id']==1064){
                $play =  $arr13[array_rand($arr13)];//玩法
            }elseif ($item['id']==1063){
                $play =  $arr14[array_rand($arr14)];//玩法
            }elseif ($item['id']==1062){
                $play =  $arr15[array_rand($arr15)];//玩法
            }elseif ($item['id']==1061){
                $play =  $arr16[array_rand($arr16)];//玩法
            }elseif ($item['id']==1060){
                $play =  $arr17[array_rand($arr17)];//玩法
            }elseif ($item['id']==1059){
                $play =  $arr18[array_rand($arr18)];//玩法
            }elseif ($item['id']==1058){
                $play =  $arr19[array_rand($arr19)];//玩法
            }elseif ($item['id']==1057){
                $play =  $arr20[array_rand($arr20)];//玩法
            }elseif ($item['id']==1056){
                $play =  $arr21[array_rand($arr21)];//玩法
            }elseif ($item['id']==1055){
                $play =  $arr22[array_rand($arr22)];//玩法
            }elseif ($item['id']==1054){
                $play =  $arr23[array_rand($arr23)];//玩法
            }elseif ($item['id']==1053){
                $play =  $arr24[array_rand($arr24)];//玩法
            }elseif ($item['id']==1052){
                $play =  $arr25[array_rand($arr25)];//玩法
            }elseif ($item['id']==1051){
                $play =  $arr26[array_rand($arr26)];//玩法
            }elseif ($item['id']==1050){
                $play =  $arr27[array_rand($arr27)];//玩法
            }elseif ($item['id']==1049){
                $play =  $arr28[array_rand($arr28)];//玩法
            }elseif ($item['id']==1077){
                $play =  $arr29[array_rand($arr29)];//玩法
            }elseif ($item['id']==1078){
                $play =  $arr30[array_rand($arr30)];//玩法
            }elseif ($item['id']==1079){
                $play =  $arr31[array_rand($arr31)];//玩法
            }elseif ($item['id']==1080){
                $play =  $arr32[array_rand($arr32)];//玩法
            }elseif ($item['id']==1081){
                $play =  $arr33[array_rand($arr33)];//玩法
            }elseif ($item['id']==1082){
                $play =  $arr34[array_rand($arr34)];//玩法
            }elseif ($item['id']==1083){
                $play =  $arr35[array_rand($arr35)];//玩法
            }elseif ($item['id']== 1084){
                $play =  $arr36[array_rand($arr36)];//玩法
            }elseif ($item['id']==1085){
                $play =  $arr37[array_rand($arr37)];//玩法
            }elseif ($item['id']==1086){
                $play =  $arr38[array_rand($arr38)];//玩法
            }elseif ($item['id']==1087){
                $play =  $arr39[array_rand($arr39)];//玩法
            }
            if ($color == 60) {//腾讯分分彩
                $db = Ssctxffc::find();
            } elseif ($color == 1) {//重庆时时彩
                $db = Cqssc::find();
            } elseif ($color == 3) {//天津时时彩
                $db = Tjssc::find();
            } elseif ($color == 7) {//新疆时时彩
                $db = Xjssc::find();
            } elseif ($color == 112) {//官方分分彩
                $db = Gfffc::find();
            } elseif ($color == 113) {//官方两分彩
                $db = Gflfc::find();
            } elseif ($color == 114) {//官方五分彩
                $db = Gfwfc::find();
            }
            $period = $db->orderBy('id desc')->asArray()->one();
            $data['admin_id'] = $item['id'];
            $data['username'] = $item['username'];
            $data['created_time'] = time();
            $data['color_id'] = $color;
            $data['play_id'] = $play;
            $data['period'] = $period['behind_period'];
            $data['content'] = $this->Pan($play);
            $data['price'] = rand(100,2000);
            $data['rebate'] = rand(1,4);
            $data['multiple'] = rand(1,4);
            $data['mode'] = $mode[rand(0,3)];
            $data['quantity'] = rand(1,10);
            $data['bonus'] = rand(1,1990);
            $data['zhuihao'] = 0;
            $data['time'] = time();
            $data['num'] = 0;
            $data['type'] = 0;
            $data['root'] = 1;
            $data['recall_number_records_id'] = 0;
            $lottery = new Lottery();
            $lottery->Lottery($data);
        }
    }

    /**判断玩法上次投注号码
     * @param $data 玩法
     * @return string
     */
    public function Pan($data)
    {
        $zimu = [0,1,2,3,4,5,6,7,8,9];
        if ($data==1||$data==2||$data==3||$data==11||$data==12||$data==13||$data==14){
            //生成五个号码的
            $wan = implode('',array_slice($zimu,rand(0,9)));
            $qian = implode('',array_slice($zimu,rand(0,9)));
            $bai = implode('',array_slice($zimu,rand(0,9)));
            $shi = implode('',array_slice($zimu,rand(0,9)));
            $ge = implode('',array_slice($zimu,rand(0,9)));
            $number = $wan.','.$qian.','.$bai.','.$shi.','.$ge;
        }elseif ($data==31||$data==32||$data==33||$data==34||$data==43||$data==44||$data==45||$data==46||$data==55||$data==56||$data==57||$data==58||$data==75||$data==76||$data==67||$data==68
            ||$data==83||$data==85||$data==82||$data==84||$data==86||$data==87||$data==88||$data==89||$data==90||$data==91){
            //生成一个号码的
            $number = implode('',array_slice($zimu,rand(0,9)));
        }elseif ($data==5||$data==6||$data==7||$data==8||$data==9||$data==19||$data==21||$data==26||$data==28||$data==65||$data==66||$data==73||$data==74){
            //生成两个号码
            $wan = implode('',array_slice($zimu,rand(0,9)));
            $qian = implode('',array_slice($zimu,rand(0,9)));
            $number = $wan.','.$qian;
        }elseif ($data==10){
            $zi = ['庄闲','对子','豹子','天王'];
            $one = array_slice($zi,rand(0,3));
            $on = implode('',$one);
            $two = array_slice($zi,rand(0,3));
            $tw = implode('',$two);
            $number = $on.','.$tw;
        }elseif ($data==15||$data==16||$data==17||$data==24||$data==22||$data==23){
            //生成四个号码的
            $wan = implode('',array_slice($zimu,rand(0,9)));
            $qian = implode('',array_slice($zimu,rand(0,9)));
            $bai = implode('',array_slice($zimu,rand(0,9)));
            $shi = implode('',array_slice($zimu,rand(0,9)));
            $number = $wan.','.$qian.','.$bai.','.$shi;
        }elseif ($data==29||$data==30||$data==37||$data==41||$data==42||$data==49||$data==53||$data==54||$data==61){
            //生成三个号码的
            $wan = implode('',array_slice($zimu,rand(0,9)));
            $qian = implode('',array_slice($zimu,rand(0,9)));
            $bai = implode('',array_slice($zimu,rand(0,9)));
            $number = $wan.','.$qian.','.$bai;
        }elseif ($data==36||$data==48||$data==60){
            $z = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27];
            $two = array_slice($z,rand(0,27));
            $number = implode('$',$two);
        }elseif ($data==38||$data==50||$data==62||$data==77||$data==69){
            $wan = implode(',',array_slice($zimu,rand(0,9)));
            $number = $wan;
        }elseif ($data==40||$data==52||$data==64){
            $z = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26];
            $two = array_slice($z,rand(0,25));
            $number = implode('$',$two);
        }elseif ($data==39||$data==51||$data==63||$data==78||$data==70){
            $two = array_slice($zimu,rand(0,9));
            $number = implode('$',$two);
        }elseif ($data==71||$data==79){
            $z = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18];
            $two = array_slice($z,rand(0,18));
            $number = implode('$',$two);
        }elseif ($data==72||$data==80){
            $z = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17];
            $two = array_slice($z,rand(0,16));
            $number = implode('$',$two);
        }elseif ($data==93||$data==92){
            $a = ['大','小','单','双'];
            $one = implode('',array_slice($a,rand(0,3)));
            $two = implode('',array_slice($a,rand(0,3)));
            $number = $one.','.$two;
        }elseif ($data==94||$data==95){
            $a = ['大','小','单','双'];
            $one = implode('',array_slice($a,rand(0,3)));
            $two = implode('',array_slice($a,rand(0,3)));
            $three = implode('',array_slice($a,rand(0,3)));
            $number = $one.','.$two.','.$three;
        }elseif ($data==20||$data==18||$data==25||$data==27||$data==4){
            $number = implode('',array_slice($zimu,rand(0,9))).',';
        }
        return $number;
    }
}