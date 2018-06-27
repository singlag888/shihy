<?php
/**
 * 彩种的判断
 */

namespace frontend\models;


use yii\base\Model;

class Determine extends Model
{
    /**
     * @param $color 彩种
     * @param $play 玩法
     * @param $number 开奖号码
     * @param $content 选号
     */
    public static function TimeColor($color,$play,$number,$content)
    {
        $result = 0;
        $judgment = new Judgment();
        if ($color==112||$color==60||$color==1||$color==3||$color==7||$color==113||$color==114){
            if ($play == 1|| $play == 15 || $play == 23 || $play == 29 || $play == 41 || $play == 53  || $play == 65 || $play == 73) {
                if ($play == 1) {
                    $num = 5;
                } elseif ($play == 15) {
                    $num = 4;
                } elseif ($play == 23) {
                    $num = -4;
                } elseif ($play == 29 ) {
                    $num = 3;
                } elseif ($play == 41) {
                    $num = 1;
                } elseif ($play == 53) {
                    $num = -3;
                } elseif ($play == 65) {
                    $num = 2;
                } elseif ($play == 73) {
                    $num = -2;
                }
                $result = $judgment->DirectElection($num, $number, $content);
            }elseif ($play==2||$play==16||$play==22||$play==30||$play==42||$play==54||$play==66||$play==74){
                    if ($play==2){
                        $num=5;
                    }elseif ($play==16){
                        $num=4;
                    }elseif ($play==22){
                        $num=-4;
                    }elseif ($play==30){
                        $num=3;
                    }elseif ($play==42){
                        $num=1;
                    }elseif ($play==54){
                        $num=-3;
                    }elseif ($play==66){
                        $num=2;
                    }elseif ($play==74){
                        $num=-2;
                    }
                $result = $judgment->DirectElection($num, $number, $content);
            } elseif ($play == 3 || $play == 17 || $play == 24||$play == 37||$play == 49||$play == 61) {
                if ($play == 3) {
                    $num = 5;
                } elseif ($play == 17) {
                    $num = 4;
                } elseif ($play == 24) {
                    $num = -4;
                } elseif ($play == 37) {
                    $num = 3;
                } elseif ($play == 49) {
                    $num = 1;
                } elseif ($play == 61) {
                    $num = -3;
                }
                $result = $judgment->StraightGroup($num, $number, $content);
            }elseif ($play==10){
                $result = $judgment->Baccarat( $number, $content);
            }elseif ($play==11){
                $result = $judgment->SmoothSailing( $number, $content);
            }elseif ($play==12||$play==13||$play==14){
                if ($play==12){
                    $num = 2;
                }elseif ($play==13){
                    $num = 3;
                }elseif ($play==14){
                    $num = 4;
                }
                $result = $judgment->Double($num,$number, $content);
            }elseif ($play==4||$play==18||$play==20||$play==25||$play==27||$play==31||$play==33||$play==43||$play==45||$play==55||$play==57||$play==65||$play==73){
                if ($play==4){
                    $num = 5;
                    $chonghao = 1;
                    $frequency = 5;
                }elseif ($play==18){
                    $num = 4;
                    $chonghao = 1;
                    $frequency = 4;
                }elseif ($play==20){
                    $num = 4;
                    $chonghao = 2;
                    $frequency = 2;
                }elseif ($play==25){
                    $num = -4;
                    $chonghao = 1;
                    $frequency = 4;
                }elseif ($play==27){
                    $num = -4;
                    $chonghao = 2;
                    $frequency = 2;
                }elseif ($play==31){
                    $num = 3;
                    $chonghao = 2;
                    $frequency = 1;
                }elseif ($play==33){
                    $num = 3;
                    $chonghao = 1;
                    $frequency = 3;
                }elseif ($play==43){
                    $num = 1;
                    $chonghao = 2;
                    $frequency = 1;
                }elseif ($play==45){
                    $num = 3;
                    $chonghao = 1;
                    $frequency = 3;
                }elseif ($play==55){
                    $num = -3;
                    $chonghao = 2;
                    $frequency = 1;
                }elseif ($play==57){
                    $num = -3;
                    $chonghao = 1;
                    $frequency = 3;
                }elseif ($play==65){
                    $num = 2;
                    $chonghao = 1;
                    $frequency = 2;
                }elseif ($play==73){
                    $num = -2;
                    $chonghao = 1;
                    $frequency = 2;
                }
                $result = $judgment->Arow($num,$chonghao,$frequency,$number, $content);
            }elseif ($play==32||$play==34||$play==44||$play==46||$play==56||$play==58||$play==66||$play==74||$play==35||$play==47||$play==59){
                    if ($play==32){
                        $type = 2;
                        $num = 3;
                    }elseif ($play==34){
                        $type = 1;
                        $num = 3;
                    }elseif ($play==44){
                        $type = 2;
                        $num = 1;
                    }elseif ($play==46){
                        $type = 1;
                        $num = 1;
                    }elseif ($play==56){
                        $type = 2;
                        $num = -3;
                    }elseif ($play==58){
                        $type = 1;
                        $num = -3;
                    }elseif ($play==66){
                        $type = -1;
                        $num = 2;
                    }elseif ($play==74){
                        $type = -1;
                        $num = -2;
                    }elseif ($play==35){//混合
                        $type = 0;
                        $num = 3;
                    }elseif ($play==47){//混合
                        $type = 0;
                        $num = 1;
                    }elseif ($play==59){//混合
                        $type = 0;
                        $num = -3;
                    }
                $result = $judgment->mixing($type,$num,$number, $content);
            } elseif ($play==5||$play==6||$play==7||$play==8||$play==9||$play==19||$play==21||$play==26||$play==28){
                if ($play==5){
                    $num = 5;
                    $chonghao = 2;
                    $frequency = 1;
                    $er = 1;
                    $erci = 3;
                }elseif ($play==6){
                    $num = 5;
                    $chonghao = 2;
                    $frequency = 2;
                    $er = 1;
                    $erci = 1;
                }elseif ($play==7){
                    $num = 5;
                    $chonghao = 3;
                    $frequency = 1;
                    $er = 1;
                    $erci = 2;
                }elseif ($play==8){
                    $num = 5;
                    $chonghao = 3;
                    $frequency = 1;
                    $er = 2;
                    $erci = 1;
                }elseif ($play==9){
                    $num = 5;
                    $chonghao = 4;
                    $frequency = 1;
                    $er = 1;
                    $erci = 1;
                }elseif ($play==19){
                    $num = 4;
                    $chonghao = 2;
                    $frequency = 1;
                    $er = 1;
                    $erci = 2;
                }elseif ($play==21){
                    $num = 4;
                    $chonghao = 3;
                    $frequency = 1;
                    $er = 1;
                    $erci = 1;
                }elseif ($play==26){
                    $num = -4;
                    $chonghao = 2;
                    $frequency = 1;
                    $er = 1;
                    $erci = 2;
                }elseif ($play==28){
                    $num = -4;
                    $chonghao = 3;
                    $frequency = 1;
                    $er = 1;
                    $erci = 1;
                }
                $result = $judgment->Tworow($num,$chonghao,$frequency,$er,$erci,$number, $content);
            }elseif ($play == 36 || $play == 48 || $play == 60 || $play == 71 || $play == 79){
                if ($play == 36) {
                    $num = 3;
                } elseif ($play == 48) {
                    $num = 1;
                } elseif ($play == 60) {
                    $num = -3;
                } elseif ($play == 71) {
                    $num = 2;
                } elseif ($play == 79) {
                    $num = -2;
                }
                $result = $judgment->Hezhi($num, $number, $content);
            } elseif ($play == 40 || $play == 52 || $play == 64 || $play == 72 || $play == 80) {
                if ($play == 40) {
                    $num = 3;
                    $bao = 3;
                } elseif ($play == 52) {
                    $num = 1;
                    $bao = 3;
                } elseif ($play == 64) {
                    $num = -3;
                    $bao = 3;
                } elseif ($play == 72) {
                    $num = 2;
                    $bao = 2;
                } elseif ($play == 80) {
                    $num = -2;
                    $bao = 2;
                }
                $result = $judgment->ThreeTwoCombination($num, $number, $bao, $content);//返回的是中文
            } elseif ($play == 38 || $play == 50 || $play == 62 || $play == 69 || $play == 77) {
                if ($play == 38) {
                    $num = 3;
                } elseif ($play == 50) {
                    $num = 1;
                } elseif ($play == 62) {
                    $num = -3;
                } elseif ($play == 69) {
                    $num = 2;
                } elseif ($play == 77) {
                    $num = -2;
                }
                $result = $judgment->Span($num, $number, $content);
            } elseif ($play == 39 || $play == 51 || $play == 63 || $play == 70 || $play == 78) {
                if ($play == 39) {
                    $num = 3;
                    $bao = 2;
                } elseif ($play == 51) {
                    $num = 1;
                    $bao = 2;
                } elseif ($play == 63) {
                    $num = -3;
                    $bao = 2;
                } elseif ($play == 70) {
                    $num = 2;
                    $bao = 1;
                } elseif ($play == 78) {
                    $num = -2;
                    $bao = 1;
                }
                $result = $judgment->Sanbaobao($num, $number, $bao, $content);
            } elseif ($play == 81) {
                $result = $judgment->Positioningbile($number, $content);
            } elseif ($play == 83 || $play == 85 || $play == 82 || $play == 84 || $play == 86 || $play == 87 || $play == 88 || $play == 89 || $play == 90 || $play == 91) {
                if ($play == 83) {
                    $num = 3;
                    $ma = 1;
                } elseif ($play == 85) {
                    $num = 1;
                    $ma = 1;
                } elseif ($play == 82) {
                    $num = -3;
                    $ma = 1;
                } elseif ($play == 84) {
                    $num = 3;
                    $ma = 2;
                } elseif ($play == 86) {
                    $num = 1;
                    $ma = 2;
                } elseif ($play == 87) {
                    $num = -3;
                    $ma = 2;
                } elseif ($play == 88) {
                    $num = -4;
                    $ma = 1;
                } elseif ($play == 89) {
                    $num = -4;
                    $ma = 2;
                } elseif ($play == 90) {
                    $num = 5;
                    $ma = 2;
                } elseif ($play == 91) {
                    $num = 5;
                    $ma = 3;
                }
                $result = $judgment->NotPositioned($num, $number, $content, $ma);
            } elseif ($play == 93 || $play == 92 || $play == 94 || $play == 95) {
                if ($play == 93) {
                    $num = 2;
                } elseif ($play == 92) {
                    $num = -2;
                } elseif ($play == 94) {
                    $num = 3;
                } elseif ($play ==95) {
                    $num = -3;
                }
                $result = $judgment->SingleAndDoubleSize($num, $number, $content);
            } elseif ($play == 99 || $play == 98 || $play == 104 || $play == 105 || $play == 113 || $play == 114) {
                if ($play == 99 || $play == 98) {
                    $num = 2;
                } elseif ($play == 104 || $play == 105) {
                    $num = 3;
                } elseif ($play == 113 || $play == 114) {
                    $num = 4;
                }
                $result = $judgment->OptionalDuplex($num, $number, $content);
            } elseif ($play == 102 || $play == 107) {
                if ($play == 102) {
                    $num = 2;
                } elseif ($play == 107) {
                    $num = 3;
                }
                $result = $judgment->OptionalAndValue($num, $number, $content);
            } elseif ($play == 101 || $play == 100 || $play == 110 || $play == 109 || $play == 112 || $play == 111) {
                if ($play == 101 || $play == 100) {
                    $num= 2;
                    $bao = 1;
                } elseif ($play == 110 || $play == 109) {
                    $num = 3;
                    $bao = 2;
                } elseif ($play == 112 || $play == 111) {
                    $num = 3;
                    $bao = 1;
                }
                $result = $judgment->OptionalGroup($num, $number, $content, $bao);
            } elseif ($play == 103 || $play == 108) {
                if ($play == 103) {
                    $num = 2;
                    $bao = 1;
                } elseif ($play == 108) {
                    $num = 3;
                    $bao = 2;
                }
                $result = $judgment->GroupAnd($num, $number, $content, $bao);
            } elseif ($play == 115 || $play == 116 || $play == 117 || $play == 118) {
                if ($play == 115) {
                    $dan= 4;
                    $erchong = 0;
                    $sanchong = 0;
                    $sichong = 0;
                    $nn = [];
                    $ns = '一重号';
                    $duo =0;
                    $shao = 4;
                } elseif ($play == 116) {
                    $dan = 2;
                    $erchong = 1;
                    $sanchong = 0;
                    $sichong = 0;
                    $nn = '二重号';
                    $ns = '';
                    $duo = 1;
                    $shao = 2;
                } elseif ($play == 117) {
                    $dan = 0;
                    $erchong = 2;
                    $sanchong = 0;
                    $sichong = 0;
                    $nn = '二重号';
                    $ns = [];
                    $duo= 2;
                    $shao = 0;
                } elseif ($play == 118) {
                    $dan = 1;
                    $erchong = 0;
                    $sanchong = 1;
                    $sichong = 0;
                    $nn = '三重号';
                    $ns = '一重号';
                    $duo = 1;
                    $shao = 1;
                }
                $result = $judgment->FourGroups($dan, $erchong, $sanchong, $sichong, $nn, $ns, $duo, $shao, $number, $content);
            } elseif ($play == 96) {
                $result = $judgment->Longhu($number, $content);
            } elseif ($play == 97) {
                $result = $judgment->Sum($number, $content);
            }
        }elseif ($color==23||$color==117||$color==24||$color==37||$color==38){
            if ($play == 1||$play == 2||$play == 6||$play == 7){
                if ($play == 1||$play == 2){
                    $num = 2;
                }elseif ($play == 6||$play == 7){
                    $num = 3;
                }
                $result = $judgment->TwoDifferent($num,$number, $content);
            }elseif ($play == 3||$play == 4||$play == 9||$play == 10){
                if ($play == 3||$play == 4){
                    $num = 2;
                }elseif ($play == 9||$play == 10){
                    $num = 3;
                }
                $result = $judgment->SameNumber($num,$number, $content);
            }elseif ($play == 5){
                $result = $judgment->Check($number, $content);
            }elseif ($play == 8||$play == 12){
                if($play == 8){
                    $num = 1;
                }elseif ($play == 12){
                    $num = 2;
                }
                $result = $judgment->AndValue($num,$number, $content);
            }elseif ($play == 11){
                $result = $judgment->Select($number, $content);
            }
        }elseif ($color==116||$color==9||$color==6||$color==115||$color==22){
            if ($play == 1||$play == 2||$play == 6||$play == 7||$play == 11){
                if ($play == 1||$play == 2){
                    $num = 3;
                }elseif ($play == 6||$play == 7){
                    $num = 2;
                }elseif ($play == 11){
                    $num = 1;
                }
                $result = $judgment->Threeyards($num,$number, $content);
            }elseif ($play == 3||$play == 4||$play == 8||$play == 9){
                if ($play == 3||$play == 4){
                    $num = 3;
                }elseif ($play == 8||$play == 9){
                    $num = 2;
                }
                $result = $judgment->GroupSelectionDuplex($num,$number, $content);
            }elseif ($play == 5||$play == 10){
                if ($play == 5){
                    $num = 3;
                }elseif ($play == 10){
                    $num = 2;
                }
                $result = $judgment->Tug($num,$number, $content);
            }elseif ($play == 12){
                $result = $judgment->NotDingwei($number, $content);
            }elseif ($play == 13){
                $result = $judgment->Dingwei($number, $content);
            }elseif ($play == 14){
                $result = $judgment->OrderDouble($number, $content);
            }elseif ($play == 15){
                $result = $judgment->Median($number, $content);
            }elseif ($play == 16||$play == 24||$play == 17||$play == 25||$play == 18||$play == 26||$play == 19||$play == 27||$play == 20||$play == 28||$play == 21||$play == 29||$play == 22||$play == 30||$play == 23||$play == 31){
                if ($play == 16||$play == 24){
                    $shu = 1;
                }elseif ($play == 17||$play == 25){
                    $shu = 2;
                }elseif ($play == 18||$play == 26){
                    $shu = 3;
                }elseif ($play == 19||$play == 27){
                    $shu = 4;
                }elseif ($play == 20||$play == 28){
                    $shu = 5;
                }elseif ($play == 21||$play == 29){
                    $shu = 6;
                }elseif ($play == 22||$play == 30){
                    $shu = 7;
                }elseif ($play == 23||$play == 31){
                    $shu = 8;
                }
                $result = $judgment->Optional($shu,$number, $content);
            }elseif ($play == 32||$play == 33||$play == 34||$play == 35){
                if ($play == 32){
                    $num =2;
                }elseif ($play == 33){
                    $num =3;
                }elseif ($play == 34){
                    $num =4;
                }elseif ($play == 35){
                    $num =5;
                }
                $result = $judgment->Trailer($num,$number, $content);
            }elseif ($play == 36||$play == 37||$play == 38){
                if ($play == 36){
                    $num =6;
                }elseif ($play == 37){
                    $num =7;
                }elseif ($play == 38){
                    $num =8;
                }
                $result = $judgment->EightDrag($num,$number, $content);
            }
        }elseif ($color==118||$color==27||$color==119){//官方极速赛车
            if ($play == 1||$play == 2||$play == 3||$play == 4||$play == 7||$play == 8||$play == 11||$play == 12||$play == 15||$play == 16||$play == 19||$play == 20||$play == 23||$play == 24||$play == 27||$play == 28||$play == 31||$play == 32||$play == 35||$play == 36){
                if ($play == 1||$play == 2){
                    $num = 1;
                }elseif ($play == 4||$play == 3){
                    $num = 2;
                }elseif ($play == 7||$play == 8){
                    $num = 3;
                }elseif ($play == 11||$play == 12){
                    $num = 4;
                }elseif ($play == 15||$play == 16){
                    $num = 5;
                }elseif ($play == 19||$play == 20){
                    $num = 6;
                }elseif ($play == 23||$play == 24){
                    $num = 7;
                }elseif ($play == 27||$play == 28){
                    $num = 8;
                }elseif ($play == 31||$play == 32){
                    $num = 9;
                }elseif ($play == 35||$play == 36){
                    $num = 10;
                }
                $result = $judgment->TheFirsTfew($num,$number, $content);
            }elseif ($play == 5||$play == 6||$play == 9||$play == 10||$play == 13||$play == 14||$play == 17||$play == 18||$play == 21||$play == 22||$play == 25||$play == 26||$play == 29||$play == 30||$play == 34||$play == 33||$play == 37||$play == 38){
                if ($play == 5||$play == 6){
                    $num = 2;
                }elseif ($play == 9||$play == 10){
                    $num = 3;
                }elseif ($play == 13||$play == 14){
                    $num = 4;
                }elseif ($play == 17||$play == 18){
                    $num = 5;
                }elseif ($play == 21||$play == 22){
                    $num = 6;
                }elseif ($play == 25||$play == 26){
                    $num = 7;
                }elseif ($play == 29||$play == 30){
                    $num = 8;
                }elseif ($play == 34||$play == 33){
                    $num = 9;
                }elseif ($play == 37||$play == 38){
                    $num = 10;
                }
                $result = $judgment->TheFirstFews($num,$number, $content);
            }elseif ($play == 39){
                $result = $judgment->Positioning($number, $content);
            }elseif ($play == 39||$play == 41||$play == 42){
                if($play == 39){
                    $num = 1;
                }elseif ($play == 41){
                    $num = 2;
                }elseif ($play == 42){
                    $num = 3;
                }
                $result = $judgment->Size($num,$number, $content);
            }elseif ($play == 43||$play == 44){
                if ($play == 43){
                    $num = 2;
                }elseif ($play == 44){
                    $num = 3;
                }
                $result = $judgment->ChampionshipAndValue($num,$number, $content);
            }elseif ($play == 45){
                $result = $judgment->PkLongHu($number, $content);
            }
        }
        return $result;
    }
}