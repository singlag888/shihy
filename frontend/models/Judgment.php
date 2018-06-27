<?php
/**
 * 判断是否中奖的方法
 */

namespace frontend\models;


use yii\base\Model;

class Judgment extends Model
{
    /**只支持五星到后二中的直选复式或者直选单式
     * @param $num 几星 中三传 1
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     */
    public function DirectElection($num, $number, $xu_number)
    {
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        array_slice($array_num, $c, -4);
        $array_xu_number = explode(',', $xu_number);
        $a = 0;
        foreach ($cc as $key => $value) {
            $pos = strpos(strval($array_xu_number[$key]), strval($value));
            if ($pos !== false) {
                ++$a;
            }
        }
        if ($a == $num) {
            return 1;
        }
        return 0;
    }

    /**百家乐
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Baccarat($number,$xu_number)
    {
//        $number = '6,1,5,0,9';
//        $xu_number = '庄闲对子豹子天王,庄闲对子豹子天王';
        $numbers = explode(',',$number);
        $xu_number = explode(',',$xu_number);
        $res['庄'] =[];
        $res['闲'] =[];
        if (substr(($numbers[0]+$numbers[1]),-1,1)>substr(($numbers[3]+$numbers[4]),-1,1)){
            $res['庄'][] = '庄闲';
        }
        if (substr(($numbers[0]+$numbers[1]),-1,1)<substr(($numbers[3]+$numbers[4]),-1,1)){
            $res['闲'][] = '庄闲';
        }
        if ($numbers[0]==$numbers[1]){
            $res['庄'][] = '对子';
        }
        if ($numbers[3]==$numbers[4]){
            $res['闲'][] = '对子';
        }
        if ($numbers[0]===$numbers[1]&&$numbers[1]===$numbers[2]){
            $res['庄'][] = '豹子';
        }
        if ($numbers[2]===$numbers[3]&&$numbers[3]===$numbers[4]){
            $res['闲'][] = '豹子';
        }
        if (substr(($numbers[0]+$numbers[1]),-1,1)==8||substr(($numbers[0]+$numbers[1]),-1,1)==9){
            $res['庄'][] = '天王';
        }
        if (substr(($numbers[3]+$numbers[4]),-1,1)==8||substr(($numbers[3]+$numbers[4]),-1,1)==9){
            $res['闲'][] = '天王';
        }
        $a = 0;
        $b = [];
        if ($res['庄']){
            foreach ($res['庄'] as $re){
                if (strpos($xu_number[0],$re)!==false){
                    array_push($b,$re);
                }
            }
        }
        if ($res['闲']){
            foreach ($res['闲'] as $re){
                if (strpos($xu_number[1],$re)!==false){
                    array_push($b,$re);
                }
            }
        }
        if ($b){
            return $b;
        }else{
            return $a;
        }
    }
    /**一帆风顺
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function SmoothSailing($number,$xu_number)
    {
//        $number = '8,1,9,5,1';
//        $xu_number = '0123456789';
        $numbers = explode(',',$number);
        $aa = array_unique($numbers);
        $a = 0;
        foreach ($aa as $number){
            if (strpos($xu_number,$number)!==false){
                ++$a;
            }
        }
        return $a;
    }

    /**好事成双和三星报喜和四季发财
     * @param $num 好事成双(2)和三星报喜(3)和四季发财(4)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Double($num,$number,$xu_number)
    {
//        $number = '	8,5,8,4,8';
//        $xu_number = '0123456789';
        $numbers = explode(',',$number);
        $xu_number = str_split($xu_number);
        $a = 0;
        if (count($numbers) != count(array_unique($numbers))){
            $aa = array_count_values($numbers);
            foreach ($aa as $k=>$v){
                if ($v>=$num){
                    if (in_array($k,$xu_number)!==false){
                        ++$a;
                    }
                }
            }
        }
        return $a;
    }

    /**时时彩的直选单式
     * @param $num
     * @param $number
     * @param $xu_number
     * @return int
     */
    public function Singlestyle($num,$number,$xu_number)
    {
//        $num =  5;
//        $number = '9,2,8,5,2';
//        $xu_number = '123$124$125$126$127$128$129$121$131$134$125$126';
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $xu_number = explode('$',$xu_number);
        $a = 0;
        foreach ($xu_number as $item){
            $n = str_split($item);
            if (array_intersect($cc,$n)==$cc){
                ++$a;
            }
        }
        return $a;
    }


    public function mixing($type,$num,$number,$xu_number)
    {
//        $type = 2;//组三(2) 混合(0) 组六(1) 二星(-1)
//        $num = 1;
//        $number = '9,2,2,5,2';
//        $xu_number = '112$113$114$115$116$117$118$522';
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $chonghao = array_count_values($cc);
        //判断是不是混合
        $a = 0;
        $result = 0;
        if ($type==0){
            //是混合
            if (max($chonghao)<=2){
                if (max($chonghao)==2){
                    $arr = '组三';
                }
                if (max($chonghao)==1){
                    $arr = '组六';
                }
                $xu_number = explode('$', $xu_number);
                foreach ($xu_number as $item){
                    $n = str_split($item);
                    if (array_intersect($n,$cc)==$cc){
                        ++$a;
                    }
                }
                if ($a){
                    $result = $arr.$a;
                }
            }
        }elseif($type==1||$type==2){
            if(max($chonghao)==$type){
                if ($type==2){
                    $arr = '组三';
                }
                if ($type==1){
                    $arr = '组六';
                }
                $xu_number = explode('$', $xu_number);
                foreach ($xu_number as $item){
                    $n = str_split($item);
                    if (array_intersect($cc,$n)==$cc){
                        ++$a;
                    }
                }
                if ($a){
                    $result = $arr.$a;
                }
            }
        }elseif ($type==-1){//二星
            if (max($chonghao)==1){
                $xu_number = explode('$', $xu_number);
                foreach ($xu_number as $item){
                    $n = str_split($item);
                    if (array_intersect($cc,$n)==$cc){
                        ++$a;
                    }
                }
                $result = $a;
            }
        }
        return $result;
    }

    /**只支持五星到后二中的直选组合
     * @param $num 几星  中三传 1
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     */
    public function StraightGroup($num, $number, $xu_number)
    {
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $a = 0;
        for ($i=0;$i<count($cc);++$i){
            if (strpos($xu_number[$i],$cc[$i])!==false){
                ++$a;
            }else{
                $a = 0;
            }
        }
        return $a;
    }

    /**只支持五星到四星中的组选
     * @param $num 几星 中三传 1
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $vv 重号(1,2,3....)
     * @param $dan 单号(1,2,3....)有几个单号 用与判断开奖号码
     * @param $erchong 二重号(1,2,3....)有几个单号
     * @param $sanchong 三重号(1,2,3....)有几个单号
     * @param $sichong 四重号(1,2,3....)有几个单号
     * @param $nn 四重号(四重号)多的重号 多得
     * @param $ns (一重号)多的重号  少的
     * @param $duo (一重号)多的重号 多的有几个{去重} 用于判断选择的玩法
     * @param $shao (一重号)多的重号 少的
     */
    public function GroupTwoElection($dan, $erchong, $sanchong, $sichong, $nn, $ns, $duo, $shao, $num, $number, $xu_number)
    {
//        $number = '1,5,5,5,5';
//        $num = -4;
//        $xu_number = '5,524';
//        $nn = '四重号';//多得
//        $ns = [];//少的
        //将开奖号码拆开
        $einzige_nummer = [];//单号装
        $doppelte_zahl = [];//二重号装
        $dreifache_nummer = [];//三重号装
        $vierfachnummer = [];//四重号装
//        $dan = 1;//用与判断开奖号码
//        $erchong = 0;
//        $sanchong = 0;

//        $sichong = 1;
//        $duo = 1;//用于判断选择的玩法
//        $shao = 0;
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $temp_count = array_count_values($array_num);
        foreach ($temp_count as $key => $value) {
            if ($value == 4) {
                $vierfachnummer[$key] = $value;
            }
            if ($value == 3) {
                $dreifache_nummer[$key] = $value;//记录单号
            }
            if ($value == 2) {
                $doppelte_zahl[$key] = $value;//记录单号
            }
            if ($value == 1) {
                $einzige_nummer[$key] = $value;//记录单号
            }
        }
        if (count($vierfachnummer) !== $sichong || count($dreifache_nummer) !== $sanchong || count($doppelte_zahl) !== $erchong || count($einzige_nummer) !== $dan) {
            return 0;
        }
        $einzige_nummer = [];//单号装
        $doppelte_zahl = [];//二重号装
        $dreifache_nummer = [];//三重号装
        $vierfachnummer = [];//四重号装
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $temp_count = array_count_values($cc);
        foreach ($temp_count as $key => $value) {
            if ($value == 4) {
                $vierfachnummer[$key] = $value;
            }
            if ($value == 3) {
                $dreifache_nummer[$key] = $value;//记录单号
            }
            if ($value == 2) {
                $doppelte_zahl[$key] = $value;//记录单号
            }
            if ($value == 1) {
                $einzige_nummer[$key] = $value;//记录单号
            }
        }
        if ($nn == '二重号') {
            $nn = $doppelte_zahl;
        }
        if ($nn == '三重号') {
            $nn = $dreifache_nummer;
        }
        if ($nn == '四重号') {
            $nn = $vierfachnummer;
        }
        if ($ns == '一重号') {
            $ns = $einzige_nummer;
        }
        if ($ns == '二重号') {
            $ns = $doppelte_zahl;
        }
        if ($dan==0){
            str_split($xu_number);
        }
        $array_xu_number = explode(',', $xu_number);
        $arrays = str_split($array_xu_number[0]);
        $vvv = array_intersect($arrays, array_keys($nn));
        $arrays = str_split($array_xu_number[1]);
        $vvvs = array_intersect($arrays, array_keys($ns));//少的
        if ($duo == count($vvv) && $shao == count($vvvs)) {
            return 1;
        }
        return 0;
    }

    /**支持五星到后二中选号只有一排的
     * @param $num 是几星 中三传1
     * @param $chonghao 是几重号
     * @param $frequency 有几个
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Arow($num,$chonghao,$frequency,$number,$xu_number)
    {
//        $num = 4;
//        $chonghao = 1;//限制重号的
//        $frequency = 4;//有几个
//        $number = '1,4,0,3,9';
//        $xu_number ='0123456789';
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $number = explode(',', $number);
        $number = array_slice($number, $c, $num);//得到开奖号码
        $funt = array_count_values($number);
        $a = 0;
        foreach ($funt as $k=>$item){
            if ($chonghao==$item){
                ++$a;
            }
        }
        $b = 0;
        if ($frequency==$a){
            $arr = str_split($xu_number);
            if (array_intersect(array_unique($number),$arr)==array_unique($number)){
                ++$b;
            }
        }
        return $b;
    }

    /**支持五星到后二中选号只有二排的
     * @param $num 几星 中三(1)
     * @param $chonghao 限制重号的 (第一排的)
     * @param $frequency 有几个(第一排的)
     * @param $er (第二排的)
     * @param $erci (第三排的)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Tworow($num,$chonghao,$frequency,$er,$erci,$number,$xu_number)
    {
//        $num = 4;
//        $chonghao = 2;//限制重号的 (第一排的)
//        $frequency = 1;//有几个(第一排的)
//        $er = 1;//(第二排的)
//        $erci = 2;//(第三排的)
//        $number = '8,8,3,7,4';
//        $xu_number ='0123456789,0123456789';
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $number = explode(',', $number);
        $number = array_slice($number, $c, $num);//得到开奖号码
        $funt = array_count_values($number);
        $a = 0;
        $c = 0;
        foreach ($funt as $item){
            if ($chonghao==$item){
                ++$a;
            }
            if ($er ==$item ){
                ++$c;
            }
        }
        $b = 0;
        if ($frequency==$a&&$c==$erci){
            $arr = explode(',',$xu_number);
            $yi = array_search(max($funt),$funt);//获取数组中最大值键
            if (strpos($arr[0],(string)$yi)!==false){
                unset($funt[$yi]);//删除一排的
                $e = str_split($arr[1]);
                if (array_intersect(array_keys($funt),$e)==array_keys($funt)){
                    ++$b;
                }
            }
        }
        return $b;
    }
    /**支持三星中的组选到二星
     * @param $num 几星的 如果是中三传1
     * @param $chonghao 有没有重号 没有为0 有一对1
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Threegroups($num,$chonghao,$number,$xu_number)
    {
//        $num = 3;
//        $chonghao = 1;//重号为几
//        $number = '3,3,3,8,9';
//        $xu_number = '0123456789';
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num==1){
            $num=3;
            $c = 1;
        }
        $array_num =explode(',',$number);
        $xu_number = str_split($xu_number);
        $cc = array_slice($array_num,$c,$num);//得到开奖号码
        // 获取去掉重复数据的数组
        $unique_arr = array_count_values($cc);
        // 获取重复数据的数组
        $a = 0;
        if (max($unique_arr)==$chonghao){
            if (array_intersect($cc,$xu_number)==$cc){
                ++$a;
            }
        }
        return $a;
    }

    /**只支持不定位
     * @param $num 几星
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $ma 几码
     *
     */
    public function NotPositioned($num, $number, $xu_number, $ma)
    {
        $arrays = new Arrays();
//        $number = '1,5,4,5,6';
//        $num = 5;
//        $xu_number = '23647890';
//        $ma = 1;
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $array_num = $arrays->combinations(array_unique($cc), $ma);//奖开奖号码转成
        foreach ($array_num as $value) {
            $num_k[] = implode('', $value);
        }
        $a = 0;
        $xu_number = str_split($xu_number);
        foreach ($num_k as $item) {
            $vvv = str_split($item);
            if (array_intersect($vvv, $xu_number) == $vvv) {//求数组的交集,判断是否是和开奖的一样
                ++$a;

            }
        }
        return $a;
    }

    /**只支持大小单双
     * @param $num 几星
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     */
    public function SingleAndDoubleSize($num, $number, $xu_number)
    {
//        $arrays = new Arrays();
//        $number = '1,5,4,5,6';
//        $num = 3;
//        $xu_number = '小单双,小双,双';
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $arr = ['万', '千', '百', '十', '个'];
        $rr = array_slice($arr, $c, $num);//得到开奖号码
        $kai = array_combine($rr, $cc);
        $jie = [];
        foreach ($kai as $k => $item) {
            $ks = [];
            if ($item > 4) {
                $ks[] = '大';
            } else {
                $ks[] = '小';
            }
            if ($item % 2 == 0) {
                $ks[] = '双';
            } else {
                $ks[] = '单';
            }
            $jie[] = $ks;
        }
        $a = [];
        $xu_number = explode(',', $xu_number);
        foreach ($jie as $key => $value) {
            $b = 0;
            foreach ($value as $v) {
                if (strpos($xu_number[$key],$v) !== false) {
                    ++$b;
                    $a[$key] = $b;
                }else{
                    if (!$a[$key]){
                        $a[$key] = 0;
                    }
                }
            }
        }
        return array_product($a);//数组乘积
    }

    /**只支持任选->直选复式
     * @param $num 几星 几个号码为一注
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $ma 几码(逗号隔开)
     */
    public function OptionalDuplex($num, $number, $xu_number)
    {
//        $number = '6,4,2,9,3';
//        $num = 4;
//        $xu_number = '13579,13579,13579,13579,';
        //将开奖号码的键到选好中查
        $number = explode(',', $number);
        $a = 0;
        $xu_number = explode(',', $xu_number);
        $arr = ['万', '千', '百', '十', '个'];
        $kiajiang = array_combine($arr, $number);
        $xunhao = array_combine($arr, $xu_number);
        foreach ($kiajiang as $k => $value) {
            if (strpos(strval($xunhao[$k]), strval($value)) !== false) {
                ++$a;
            }
        }
        $b = 0;
        if ($num == 2) {
            if ($a == 2) {
                $b = 1;
            } elseif ($a == 3) {
                $b = 3;
            } elseif ($a == 4) {
                $b = 6;
            } elseif ($a == 5) {
                $b = 10;
            }
        }
        if ($num == 3) {
            if ($a == 3) {
                $b = 1;
            } elseif ($a == 4) {
                $b = 4;
            } elseif ($a == 5) {
                $b = 10;
            }
        }
        if ($num == 4) {
            if ($a == 3) {
                $b = 1;
            } elseif ($a == 4) {
                $b = 1;
            } elseif ($a == 5) {
                $b = 5;
            }
        }
        return $b;
    }

    /**任选直选单式
     * @param $num
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function Straightsingle($num,$number,$xun_number)
    {
//        $num = 2;
//        $number = '2,1,3,4,7';
//        $xun_number = '万,千,百,十,个-21$12$15$16$17$18$19$20$13';
        $baishi =explode(',',substr($xun_number, 0, strrpos($xun_number, '-'))) ;//-前面的内容
        $ne = strstr($xun_number, '-');
        $nes = substr($ne, 1);//-线后面的内容
        $number = explode(',', $number);
        $arr = ['万', '千', '百', '十', '个'];
        $array = new Arrays();
        $d =  $array->combinations($arr,$num);
        foreach ($d as $item){
            $a[] = implode('',$item);
        }
        $dd =  $array->combinations($number,$num);
        foreach ($dd as $items){
            $aa[] = implode('',$items);
        }
        $kiajiang = array_combine($a, $aa);
        $dd =  $array->combinations($baishi,$num);
        foreach ($dd as $items){
            $as[] = implode('',$items);
        }
        $b = 0;
        foreach ($as as $a){
            if (strpos($nes,$kiajiang[$a])!==false){
                ++$b;
            }
        }
        return $b;

    }
    /**只支持任选->直选和值
     * @param $num 几星 几个号码为一注
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     */
    public function OptionalAndValue($num, $number, $xu_number)
    {
        $arrays = new Arrays();
//        $number = '6,3,5,1,3';
//        $num = 3;
//        $xu_number = '万,千,个-0$1$2$3$4$5$6$11$12$13$14$15$16';
        $arr = ['万', '千', '百', '十', '个'];
        $x = $arrays->combinations($arr, $num);
        foreach ($x as $v) {
            $re[] = implode('', $v);;
        }
        $number = explode(',', $number);
        $hao = $arrays->combinations($number, $num);
        foreach ($hao as $v) {
            $r[] = array_sum($v);;
        }
        $sun = array_combine($re, $r);
        //将开奖号码的键到选好中查
        $string = substr($xu_number, 0, strrpos($xu_number, '-'));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
        $values = explode('$', $str);//得到用户选择的号码
        $stri = explode(',', $string);//将用户选的号码分割成数组
        $xx = $arrays->combinations($stri, $num);
        foreach ($xx as $v) {
            $res[] = implode('', $v);;
        }
        $a = 0;
        foreach ($res as $vi) {
            if (in_array($sun[$vi], $values)) {
                ++$a;
            }
        }
        return $a;
    }

    /**只支持任选->组选复式
     * @param $num 几星 几个号码为一注
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $bao 判断支持豹子的个数 不支持1
     */
    public function OptionalGroup($num, $number, $xu_number, $bao)
    {
        $arrays = new Arrays();
//        $number = '4,9,2,9,0';
//        $num = 2;
//        $bao = 1;
//        $xu_number = '万,千,百,十,个-013456789';
        $arr = ['万', '千', '百', '十', '个'];
        $x = $arrays->combinations($arr, $num);
        foreach ($x as $v) {
            $re[] = implode('', $v);;
        }
        $number = explode(',', $number);
        $hao = $arrays->combinations($number, $num);
        $sun = array_combine($re, $hao);
        //将开奖号码的键到选好中查
        $string = substr($xu_number, 0, strrpos($xu_number, '-'));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
        $values = str_split($str);//得到用户选择的号码
        $stri = explode(',', $string);//将用户选的号码分割成数组
        $xx = $arrays->combinations($stri, $num);
        foreach ($xx as $v) {
            $res[] = implode('', $v);;
        }
        $a = 0;
        foreach ($res as $vi) {
            $c = array_count_values($sun[$vi]);
            if ($bao==max($c)){
                if (in_array($bao,$c)!==false){
                    if (array_intersect($sun[$vi], $values) == $sun[$vi]) {
                        ++$a;
                    }
                }
            }

        }
        return $a;
    }

    /**只支持任选->组选和值
     * @param $num 几星 几个号码为一注
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $bao 判断支持豹子的个数 不支持1
     */
    public function GroupAnd($num, $number, $xu_number, $bao)
    {
        $arrays = new Arrays();
//        $number = '4,9,9,9,0';
//        $num = 3;
//        $bao = 2;
//        $xu_number = '万,千,百,十,个-1$2$3$4$5$11$12$13$14$15$18';
        $arr = ['万', '千', '百', '十', '个'];
        $x = $arrays->combinations($arr, $num);
        foreach ($x as $v) {
            $re[] = implode('', $v);;
        }
        $number = explode(',', $number);
        $hao = $arrays->combinations($number, $num);
        $sun = array_combine($re, $hao);
        //将开奖号码的键到选好中查
        $string = substr($xu_number, 0, strrpos($xu_number, '-'));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
        $values = explode('$', $str);//得到用户选择的号码
        $stri = explode(',', $string);//将用户选的号码分割成数组
        $xx = $arrays->combinations($stri, $num);
        foreach ($xx as $v) {
            $res[] = implode('', $v);;
        }
        $a = 0;
        $b = 0;
        foreach ($res as $vi) {
            $c = array_count_values($sun[$vi]);
            $t = array_filter($c, function ($c) use ($bao) {
                return $bao < $c;
            });
            if (!$t) {
                if ($bao==2){
                    if (max($c)==2&&in_array(array_sum($sun[$vi]), $values)){
                        ++$b;//组三
                    }elseif(max($c)==1&&in_array(array_sum($sun[$vi]), $values)) {
                        ++$a;//组六
                    }
                }else{
                    if (in_array(array_sum($sun[$vi]), $values)){
                        ++$a;//组选二
                    }
                }

            }
        }
        if ($bao==2){
            if ($a||$b){
                $c = '组三'.$b.','.'组六'.$a;
            }else{
                $c = 0;
            }
        }else{
            $c = $a;
        }

        return $c;
    }

    /**任选组选单式
     * @param $type
     * @param $num
     * @param $bao
     * @param $number
     * @param $xun_number
     * @return int|string
     */
    public function Singlelist($num,$bao,$number,$xun_number)
    {
//        $type = 0;//任选二(0) 组三(2) 组六(1)
//        $num = 2;
//        $bao = 1;
//        $number = '2,1,3,4,7';
//        $xun_number = '万,千,百,十,个-21$12$15$16$18$19$20$13';
        $baishi =explode(',',substr($xun_number, 0, strrpos($xun_number, '-'))) ;//-前面的内容
        $ne = strstr($xun_number, '-');
        $nes = substr($ne, 1);//-线后面的内容
        $number = explode(',', $number);
        $arr = ['万', '千', '百', '十', '个'];
        $array = new Arrays();
        $d =  $array->combinations($arr,$num);
        foreach ($d as $item){
            $a[] = implode('',$item);
        }
        $dd =  $array->combinations($number,$num);
        foreach ($dd as $items){
            $aa[] = implode('',$items);
        }
        $kiajiang = array_combine($a, $aa);
        $dd =  $array->combinations($baishi,$num);
        foreach ($dd as $items){
            $as[] = implode('',$items);
        }
        $b = 0;
        foreach ($as as $a){
            $nn = str_split($kiajiang[$a]);
            if (max(array_count_values($nn))==$bao){
                if (strpos($nes,$kiajiang[$a])!==false){
                    ++$b;
                }
            }
        }
        return $b;
    }

    /**任选混合
     * @param $num
     * @param $number
     * @param $xun_number
     * @return int|string
     */
    public function Anymix($num,$number,$xun_number)
    {
//        $num = 3;
//        $number = '2,1,1,4,7';
//        $xun_number = '万,千,百,十,个-211';
        $baishi =explode(',',substr($xun_number, 0, strrpos($xun_number, '-'))) ;//-前面的内容
        $ne = strstr($xun_number, '-');
        $nes = substr($ne, 1);//-线后面的内容
        $number = explode(',', $number);
        $arr = ['万', '千', '百', '十', '个'];
        $array = new Arrays();
        $d =  $array->combinations($arr,$num);
        foreach ($d as $item){
            $a[] = implode('',$item);
        }
        $dd =  $array->combinations($number,$num);
        foreach ($dd as $items){
            $aa[] = implode('',$items);
        }
        $kiajiang = array_combine($a, $aa);
        $dd =  $array->combinations($baishi,$num);
        foreach ($dd as $items){
            $as[] = implode('',$items);
        }
        $c = 0;
        $b = 0;
        foreach ($as as $a){
            $dddd = str_split($kiajiang[$a]);
            $uu = array_count_values($dddd);
            if (max($uu)<$num){
                if (strpos($nes,$kiajiang[$a])!==false){
                    if(max($uu)==2){
                        ++$c;
                    }elseif (max($uu)==1){
                        ++$b;
                    }
                }
            }
        }
        if ($c||$b){
            $redult = '组三'.$c.','.'组六'.$b;
        }else{
            $redult =0;
        }
        return $redult;
    }
    
    /**只支持任选四->组选
     *
     * @param $number 开奖号码
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $vv 重号(1,2,3....)
     * @param $dan 单号(1,2,3....)有几个单号 用与判断开奖号码
     * @param $erchong 二重号(1,2,3....)有几个单号
     * @param $sanchong 三重号(1,2,3....)有几个单号
     * @param $sichong 四重号(1,2,3....)有几个单号
     * @param $nn 四重号(四重号)多的重号 多得
     * @param $ns (一重号)多的重号  少的
     * @param $duo (一重号)多的重号 多的有几个{去重} 用于判断选择的玩法
     * @param $shao (一重号)多的重号 少的
     *
     */
    public function FourGroups($dan, $erchong, $sanchong, $sichong, $nn = [], $ns = [], $duo, $shao, $number, $xu_number)
    {
        $einzige_nummer = [];//单号装
        $doppelte_zahl = [];//二重号装
        $dreifache_nummer = [];//三重号装
        $vierfachnummer = [];//四重号装
//        $nn = '二重号';//多得
//        $ns = '一重号';//少的
//        $number = '5,5,5,6,9';
//        $duo = 1;//用于判断选择的玩法
//        $shao = 2;
//        $dan = 2;//用与判断开奖号码
//        $erchong = 1;
//        $sanchong = 0;
//        $sichong = 0;
//        $xu_number = '万,百,十,个-5,012356789';
        $arr = ['万', '千', '百', '十', '个'];
        $number = explode(',', $number);
        $sun = array_combine($arr, $number);
        //将开奖号码的键到选好中查
        $string = substr($xu_number, 0, strrpos($xu_number, '-'));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
        $values = explode(',', $str);//得到用户选择的号码
        $stri = explode(',', $string);//将用户选的号码分割成数组
        foreach ($stri as $v) {
            $nu[] = $sun[$v];
        }
        $unique_arr = array_count_values($nu);
        foreach ($unique_arr as $key => $value) {
            if ($value == 4) {
                $vierfachnummer[$key] = $value;
            }
            if ($value == 3) {
                $dreifache_nummer[$key] = $value;//记录单号
            }
            if ($value == 2) {
                $doppelte_zahl[$key] = $value;//记录单号
            }
            if ($value == 1) {
                $einzige_nummer[$key] = $value;//记录单号
            }
        }
        if (count($vierfachnummer) !== $sichong || count($dreifache_nummer) !== $sanchong || count($doppelte_zahl) !== $erchong || count($einzige_nummer) !== $dan) {
            echo 1;
            return 0;
        }
        $einzige_nummer = [];//单号装
        $doppelte_zahl = [];//二重号装
        $dreifache_nummer = [];//三重号装
        $vierfachnummer = [];//四重号装
        $temp_count = array_count_values($nu);
        foreach ($temp_count as $key => $value) {
            if ($value == 4) {
                $vierfachnummer[$key] = $value;
            }
            if ($value == 3) {
                $dreifache_nummer[$key] = $value;//记录单号
            }
            if ($value == 2) {
                $doppelte_zahl[$key] = $value;//记录单号
            }
            if ($value == 1) {
                $einzige_nummer[$key] = $value;//记录单号
            }
        }
        if ($nn == '二重号') {
            $nn = $doppelte_zahl;
        }
        if ($nn == '三重号') {
            $nn = $dreifache_nummer;
        }
        if ($nn == '四重号') {
            $nn = $vierfachnummer;
        }
        if ($ns == '一重号') {
            $ns = $einzige_nummer;
        }
        if ($ns == '二重号') {
            $ns = $doppelte_zahl;
        }
        $arrays = str_split($values[0]);
        $vvv = array_intersect($arrays, array_keys($nn));
        $arrays = str_split($values[1]);
        $vvvs = array_intersect($arrays, array_keys($ns));//少的
        if ($duo == count($vvv) && $shao == count($vvvs)) {
            return 1;
        }
        return 0;
    }

    /**只支持前三道后二->组选和值
     *
     * @param $number 开奖号码
     * @param $num 几星 中三是 1
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $bao 是不是豹子 多的来算
     */
    public function ThreeTwoCombination($num, $number, $bao, $xu_number)
    {
//        $number = '1,2,3,4,5';//开奖号码
//        $num = 2;//玩法
//        $bao = 1;
//        $xu_number = '3$5$6$7$8$9$10$16$17$18$19$20';
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $xuan = explode('$', $xu_number);
        $vvv = array_count_values($cc);
        $vvv = array_filter($vvv, function ($vvv) use ($bao) {
            return $bao < $vvv;
        });
        $a = 0;
        if (!$vvv) {
            //玩法正确 判断号码是否正确
            if ($num == 3) {
                if (in_array(array_sum($cc), $xuan)) {
                    if (count($cc) == count(array_unique($cc))) {
                        return '组六'.'1';
                    } else {
                        return '组三'.'1';
                    }
                }
            }elseif ($num==2||$num==-2){
                if (in_array(array_sum($cc), $xuan)){
                    ++$a;
                }
            }
        }
        return $a;
    }

    /**和值
     * @param $num
     * @param $number
     * @param $xu_number
     * @return int
     */
    public function Hezhi($num, $number, $xu_number)
    {
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $he = array_sum($cc);
        $aa = explode('$',$xu_number);
        $a = 0;
        if (in_array($he,$aa)!==false){
            ++$a;
        }
        return $a;
    }

    /**支持前三到后二的跨度
     * @param $num 几星 其中中三为1
     * @param $number 开奖号码
     * @param $xu_number  选号
     */
    public function Span($num, $number, $xu_number)
    {
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $v = max($cc) - min($cc);
        $a = 0;
        if (in_array($v, explode(',', $xu_number))) {
            $a = 1;
        }
        return $a;
    }


    /**只支持前三道后二->组选包胆
     *
     * @param $number 开奖号码
     * @param $num 几星 中三是 1
     * @param $xu_number 用户选的号码(逗号隔开)
     * @param $bao 是不是豹子 多的来算
     */
    public function Sanbaobao($num, $number, $bao, $xu_number)
    {
//        $number = '3,1,3,4,5';//开奖号码
//        $num = 3;//玩法
//        $bao = 2;
//        $xu_number = '3$5$6$7$8$9$10$16$17$18$19$20';
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num == 1) {
            $num = 3;
            $c = 1;
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num, $c, $num);//得到开奖号码
        $xuan = explode('$', $xu_number);
        $vvv = array_count_values($cc);
        $vvv = array_filter($vvv, function ($vvv) use ($bao) {
            return $bao < $vvv;
        });
        $a = 0;
        if (!$vvv) {
            //玩法正确 判断号码是否正确
            if (count(array_intersect($cc, $xuan))==$num){
                $a = 1;
            }
            if ($num == 3) {
                if (count($cc) == count(array_unique($cc))) {
                    return '组六' . $a;
                } else {
                    return '组三' . $a;
                }
            }
            return $a;
        }
        return $a;
    }
    /** 只支持定位胆->五星定位胆
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Positioningbile($number, $xu_number)
    {
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $array_num = explode(',', $number);
        $xuan = explode(',', $xu_number);
        $arr = ['万', '千', '百', '十', '个'];
        $kai = array_combine($arr, $array_num);
        $xx = array_filter(array_combine($arr, $xuan));
        $a = 0;
        foreach ($xx as $k => $v) {
            if (strpos((string)$v, (string)$kai[$k]) !== false) {
                ++$a;
            }
        }
        return $a;
    }

    /**龙虎斗
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return array|int
     */
    public function Longhu($number, $xu_number)
    {
//        $number = '7,7,8,2,1';
//        $xu_number ='万个,万十,万百-龙,虎,合';
        $Arrays = new Arrays();
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $array_num = explode(',', $number);
        $arr = ['万', '千', '百', '十', '个'];
        $values = $Arrays->combinations($array_num, 2);
        foreach ($values as $value) {
            $aa[] = implode('', $value);
        }
        $arr_s = $Arrays->combinations($arr, 2);
        foreach ($arr_s as $value) {
            $ac[] = implode('', $value);
        }
        $kai = array_combine($ac, $values);
        foreach ($kai as $ks=>$value){
            if (reset($value) > end($value)) {
                $as[$ks] = '龙';
            } elseif (reset($value) < end($value)) {
                $as[$ks] = '虎';
            } else {
                $as[$ks] = '合';
            }
        }
        $baishi = substr($xu_number, 0, strrpos($xu_number, '-'));//-前面的内容
        $ne = strstr($xu_number, '-');
        $nes = substr($ne, 1);//-线后面的内容
        $k = explode(',', $baishi);
        $aa = [];
//        var_dump($k);
//        var_dump($as);die;
        foreach ($k as $value) {
            if (strpos($nes,$as[$value])!==false){
                array_push($aa,$as[$value]);
            }
        }
        if ($aa){
            return array_count_values($aa);
        }else{
            return 0;
        }
    }

    /**总和大小单双
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Sum($number, $xu_number)
    {
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        $array_num = explode(',', $number);
        $xuan = explode(',', $xu_number);
        $sum = array_sum($array_num);
        if ($sum >= 23) {
            $re[] = '大';
        }
        if ($sum <= 22) {
            $re[] = '小';
        }
        if ($sum % 2 == 0) {
            $re[] = '双';
        } else {
            $re[] = '单';
        }
        $a = 0;
        foreach ($xuan as $value) {
            if (in_array($value, $re)) {
                ++$a;
            }
        }
        return $a;
    }

    /**只支持类似十一选五中三码到一码的直选复式
     * @param $num  是几码
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function Threeyards($num, $number, $xuan_number)
    {
//        $number = '09,02,08,11,07';//开奖号码
//        $xuan_number = '01,02,03,04,05,06,07,08,10,11-01,02,03,04,05,06,07,08,09,10,11';//选号
//        $num = 2;
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num,$c,$num);//得到开奖号码
        $xuan_number = explode('-', $xuan_number);
        $a = 0;
        if (count($cc) === count(array_unique($cc))) {
            foreach ($cc as $k => $value) {
                $ff = explode(',', $xuan_number[$k]);
                if (in_array($value, $ff)) {
                    ++$a;
                }
            }
            if ($a === $num) {
                $a = 1;
            } else {
                $a = 0;
            }
        }
        return $a;
    }

    /**11选五的直选单式
     * @param $num
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function single($num,$number,$xun_number)
    {
//        $number = '07,05,09,11,08';
//        $xun_number = '07,05,09$7,5,9';
//        $num = 2;
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num,$c,$num);//得到开奖号码
        $vv = preg_replace('/^0*/', '', $cc);//删除数字前面的0
        $b = 0;
        if (count($cc)==count(array_unique($vv))){
            $as = explode('$',$xun_number);
            foreach ($as as $a){
                $ccc = explode(',',$a);
                $ccc = preg_replace('/^0*/', '', $ccc);//删除数字前面的0
                if (array_intersect($ccc,$vv)==$vv){
                    ++$b;
                }
            }
        }
        return $b;
    }
    
    /**只支持类似十一选五中三码到一码的组选复式  (拖胆)
     * @param $num 几码
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function GroupSelectionDuplex($num,$number,$xuan_number)
    {
//        $number = '09,02,08,11,07';//开奖号码
//        $xuan_number = '01,02,03,04,05,06,07,08,10,11';//选号
//        $num = 2;
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num==1){
            $num=3;
            $c = 1;
        }
        $array_num =explode(',',$number);
        $cc = array_slice($array_num, $c,$num);//得到开奖号码
        $a = 0;
        if (count($cc) === count(array_unique($cc))) {
            foreach ($cc as $k=>$value){
                if (strpos($xuan_number,$value)!==false){
                    ++$a;
                }
            }
            if ($a===$num){
                $a=1;
            }else{
                $a=0;
            }
        }
        return $a;
    }

    /**11选五的组选单式
     * @param $num
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function complex($num,$number,$xun_number)
    {
//        $number = '07,05,09,11,08';
//        $xun_number = '07,05,09$1,2,3$1,2,3';
//        $num = 3;
        $c = 0;
        if ($num < 0) {
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $array_num = explode(',', $number);
        $cc = array_slice($array_num,$c,$num);//得到开奖号码
        $vv = preg_replace('/^0*/', '', $cc);//删除数字前面的0
        $b = 0;
        if (count($cc)==count(array_unique($vv))){
            $as = explode('$',$xun_number);
            foreach ($as as $a){
                $ccc = explode(',',$a);
                $ccc = preg_replace('/^0*/', '', $ccc);//删除数字前面的0
                if (array_intersect($vv,$ccc)==$vv){
                    ++$b;
                }
            }
        }
        return $b;
    }

    /**11选5任选单式
     * @param $shu
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function solitary($shu,$number,$xun_number)
    {
        //1,2,3$1,2,3$1,2,3
//        $number = '07,05,09,10,08';
//        $xun_number = '07,5$10,5$2,3';
//        $shu = 2;
        $Arrays = new Arrays();
//        $number = '01,02,03,04,05';//开奖号码
//        $xuan_number = '01,02,03,04,05,06,07,08,09';//选号
//        $shu = 7;//数字是在5前面的数数是好多
        $array_num =explode(',',$number);
        $xuan_number =explode('$',$xun_number);
        $a = 0;
        if ($shu<=5){
            $vv = $Arrays->combinations($array_num,$shu);
            if ($shu==1){//中一
                $ccc = preg_replace('/^0*/', '', $xuan_number);//删除数字前面的0
                foreach ($ccc as $item){
                    foreach ($vv as $value){
                        $ccccc = preg_replace('/^0*/', '', $value);//删除数字前面的0
                        if (in_array($item,$ccccc)!==false){
                            ++$a;
                        }
                    }

                }
            }else{//上中二
                foreach ($xuan_number as $item){
                    $vvv = explode(',',$item);
                    $ccc = preg_replace('/^0*/', '', $vvv);//删除数字前面的0 选号
                    foreach ($vv as $value){
                        $ccccc = preg_replace('/^0*/', '', $value);//删除数字前面的0  开奖号码
                        if (array_intersect($ccccc,$ccc)==$ccccc){
                            ++$a;
                        }
                    }
                }
            }
        }else{
            //1,2,3,4,5,6$1,4,7,2,5,8
//            $b = $shu-5;
            foreach ($xuan_number as $item){
                $cc = explode(',',$item);
                $ccc = preg_replace('/^0*/', '', $cc);//删除数字前面的0 选号
                $array_num = preg_replace('/^0*/', '', $array_num);//删除数字前面的0 选号
                if (array_intersect($array_num,$ccc)==$array_num){
                    ++$a;
                }
            }
//            if ($intersection==$array_num){//判断是否中奖
//                $j = array_merge(array_diff($xuan_number,$array_num),array_diff($array_num,$xuan_number));//删除选号中的开奖号码
//                $vv = $Arrays->combinations($j,$b);
//                $a = count($vv);
//            }
        }
        return $a;
    }
    
    
    
    /**只支持类似十一选五中三码到一码的组选胆拖
     * @param $num 几码
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function Tug($num,$number,$xuan_number)
    {
//        $number = '09,02,08,11,07';//开奖号码
//        $xuan_number = '07,09-01,03,02,04,05,06,08,09';//选号
//        $num = 3;
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num==1){
            $num=3;
            $c = 1;
        }
        $array_num =explode(',',$number);
        $cc = array_slice($array_num, $c,$num);//得到开奖号码
        $xuan = explode('-',$xuan_number);
        $a = 0;
        $da = array_unique($cc);
        foreach ($da as $key=>$value){
            if (strpos($xuan[0],$value)!==false){
                ++$a;
                unset($da[$key]);
                foreach ($da as $item){
                    if (strpos($xuan[1],$item)!==false){
                        ++$a;
                    }
                }
            }
        }
        if ($a==$num){
            $a = 1;
        }else{
            $a = 0;
        }
        return $a;
    }

    /**支持类似11选五中的不定位的
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function NotDingwei($number,$xuan_number)
    {
//        $number = '02,09,07,01,03';//开奖号码
//        $xuan_number = '01,03,04,05,06,07,08,09,10,11';//选号
        $num = 3;
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num==1){
            $num=3;
            $c = 1;
        }
        $array_num =explode(',',$number);
        $xuan_number =explode(',',$xuan_number);
        $cc = array_slice($array_num, $c,$num);//得到开奖号码
        $dt = array_unique($cc);
        $a = 0;
        foreach ($dt as $value){
            if (in_array($value,$xuan_number)){
                ++$a;
            }
        }
        return $a;
    }

    /**支持类似11选五中的定位的
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function Dingwei($number,$xuan_number)
    {
//        $number = '02,02,07,01,03';//开奖号码
//        $xuan_number = '01,03,04,05,06,07,08,09,10,11-01,02,03,04,05,06,07,08,09,10,11-01,02,03,04,05,06,07,08,09,10,11';//选号
        $num = 3;
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        //判断是不是中三
        if ($num==1){
            $num=3;
            $c = 1;
        }
        $array_num =explode(',',$number);
        $xuan_number =explode('-',$xuan_number);
        $cc = array_slice($array_num, $c,$num);//得到开奖号码
        $a = 0;
        foreach ($cc as $key=>$item){
            $ss = explode(',',$xuan_number[$key]);
            if (in_array($item,$ss)){
                ++$a;
            }
        }
        return $a;
    }

    /**支持类似11选五中的趣味型->定单双
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function OrderDouble($number,$xuan_number)
    {
//        $number = '10,04,09,05,01';//开奖号码
//        $xuan_number = '5单0双,4单1双,2单3双,1单4双,0单5双';//选号
        $array_num =explode(',',$number);
        $shuang = 0;
        $dan = 0;
        foreach ($array_num as $item){
            if ($item%2==0){
                ++$shuang;
            }else{
                ++$dan;
            }
        }
        $res = $dan.'单'.$shuang.'双';
        if (strpos($xuan_number,$res)!==false){
            return $res;
        }else{
            return 0;
        }
    }

    /**支持类似11选五中的趣味型->猜中数
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function Median($number,$xuan_number)
    {
//        $number = '10,04,09,05,01';//开奖号码
//        $xuan_number = '3$4$6$7$8$9';//选号
        $array_num =explode(',',$number);
        $array_num = preg_replace('/^0*/', '', $array_num);
        $xuan_number =explode('$',$xuan_number);
        rsort($array_num);
        if (in_array($array_num[2],$xuan_number)!==false){
            return $array_num[2];
        }else{
            return 0;
        }
    }

    /** 11选5->任选复式
     * @param $shu 数字是在5前面的数数是好多
     * @param $number 开奖号码
     * @param $xuan_number 选号
     * @return int
     */
    public function Optional($shu,$number,$xuan_number)
    {
        $Arrays = new Arrays();
//        $number = '01,02,03,04,05';//开奖号码
//        $xuan_number = '01,02,03,04,05,06,07,08,09';//选号
//        $shu = 7;//数字是在5前面的数数是好多
        $array_num =explode(',',$number);
        $xuan_number =explode(',',$xuan_number);
        $intersection = array_intersect($array_num,$xuan_number);
        $a = 0;
        if ($shu<=5){
            if ($intersection){//存在交集
                $vv = $Arrays->combinations($intersection,$shu);
                $a =count($vv);
            }
        }else{
            $b = $shu-5;
            if ($intersection==$array_num){//判断是否中奖
                $j = array_merge(array_diff($xuan_number,$array_num),array_diff($array_num,$xuan_number));//删除选号中的开奖号码
                $vv = $Arrays->combinations($j,$b);
                $a = count($vv);
            }
        }
        return $a;
    }

    /**只支持11选5->任选拖胆->2胆拖到5胆拖
     * @param $num 是几拖就填几
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Trailer($num,$number,$xu_number)
    {
        $arrays = new Arrays();
//        $num = 3;
//        $number = '01,08,09,03,07';
//        $xu_number = '04,05-01,02,03,07,08,09,10,11';
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        //将开奖号码的键到选好中查
        $string = explode(',',substr($xu_number, 0, strrpos($xu_number, '-')));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = explode(',',str_replace('-', '', $strs));//得到用户选择的号码  去掉-
        $number = explode(',',$number);
        $a = 0;
        $zz = array_intersect($string,$number);
        if($num-count($zz)>0){
            if ($zz==$string){
                $arr = array_diff($number,$zz);
                $st = $arrays->combinations($str,$num-count($zz));
                foreach ($st as $v){
                    var_dump(array_intersect($v,$arr));
                    if (array_intersect($v,$arr)==$v){
                        ++$a;
                    }
                }
            }
        }
        return $a;
    }

    /**阶乘
     * @param $n 大的那个数
     * @param $m 小的那个数
     * @return float|int
     */
    public function actionFactorial($n,$m) {
        return $this->getJieCheng($n)/($this->getJieCheng($m)*$this->getJieCheng(($n-$m)));
    }

    /**
     * @param $num
     * @return int
     */
    public function getJieCheng($num){
        if($num!=1){
            return $this->getJieCheng($num-1) * $num;
        }else{
            return 1;
        }
    }

    /**只支持11选5的六胆拖上的(包含六)
     * @param $num是几胆拖 6,7,8
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return float|int
     */
    public function EightDrag($num,$number,$xu_number)
    {
        //$num = 8;
        //$number = '11,07,08,09,06';
        //$xu_number = '01,06,07,08,09,10,11-02,03,04,05';
        if ($num==6)$bb = 1;elseif ($num==7)$bb = 2;elseif ($num==8)$bb = 3;//胆码没有中的个数不能超过$bb
        //将开奖号码拆开
        //将开奖号码的键到选好中查
        //将开奖号码的键到选好中查
        $string = explode(',',substr($xu_number, 0, strrpos($xu_number, '-')));//截取用户勾选的位
        $strs = substr($xu_number, strrpos($xu_number, '-'));
        $str = explode(',',str_replace('-', '', $strs));//得到用户选择的号码  去掉-
        $number = explode(',',$number);
        $a = 0;
        $zz = array_diff($string,$number);
        if (count($zz)<=$bb){
            $danshu= count($string);//选了好多个胆
            $tuo = count($str);
            $cc = count(array_intersect($string,$number));//这是中了胆的数
            //中奖拖数 (5-$cc)
            //剩于买的拖号个数为$tuo-(5-$cc)
            //如果$tuo=(5-$cc)时为一注
            if ($tuo==(5-$cc)){
                $a=1;
            }else{
                $xia = $tuo-(5-$cc);
                $shang = $num-$danshu-(5-$cc);
                if ($shang==0){
                    $a =1;
                }else{
                    $a = $this->actionFactorial($xia,$shang);
                }
            }
        }
        return $a;
    }

    /**只支持快三->二不同号和三不同(标准和单式)
     * @param $num 是几不同
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TwoDifferent($num,$number,$xu_number)
    {
        //$num = 2;
        //$number = '02,02,05';
        //$xu_number = '1,2,5';
        $number =(str_replace('0','',$number));//去掉字符串中的某个字符
        $arrays = new Arrays();
        $number = explode(',',$number);
        $number = array_unique($number);
        $xu_number = explode(',',$xu_number);
        $arr = $arrays->combinations($number,$num);
        $a = 0;
        foreach ($arr as $value){
            $cc[] = implode('',$value);
        }
        foreach ($cc as $item){
            $dd = str_split($item);
            if (count(      $dd)==$num){
                if (array_intersect($dd,$xu_number)==$dd){
                    ++$a;
                }
            }
        }
        return $a;
    }

    /**单式
     * @param $num
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function Notonly($num,$number,$xun_number)
    {
//        $num = 2;
//        $number = '02,10,03';
//        $xun_number = '02,03$3,10$2,3';
        $arrays = new Arrays();
        $kaijiang = explode(',',$number);
        $kaijiang = array_unique($kaijiang);
        $arr = $arrays->combinations($kaijiang,$num);
        $aa = explode('$',$xun_number);
        $b = 0;
        foreach ($aa as $value){//选号
            $bb = explode(',',$value);

            $bb = preg_replace('/^0*/', '', $bb);//删除数字前面的0
            foreach ($arr as $item){//开奖号码
                $vv = preg_replace('/^0*/', '', $item);//删除数字前面的0
                if (array_intersect($vv,$bb)==$vv){
                    ++$b;
                }
            }
        }
        return $b;
    }

    /**快三二同号单式
     * @param $number
     * @param $xun_number
     * @return int
     */
    public function with($number,$xun_number)
    {
//        $number = '04,02,04';
//        $xun_number = '4-2$1-5$2-6';
        $number = explode(',',$number);
        $xun_number = explode('$',$xun_number);
        $arr = array_count_values($number);
        krsort($arr);
        $b = 0;
        if (max($arr)==2){
            $arr = preg_replace('/^0*/', '', $arr);//删除数字前面的0
            foreach ($xun_number as $item){
                $vv = explode('-',$item);
                $vv = preg_replace('/^0*/', '', $vv);//删除数字前面的0
                if ($vv[0]==array_search(reset($arr),$arr)&&$vv[1]==array_search(end($arr),$arr)){
                    ++$b;
                }
            }
        }
        return $b;
    }
    
    /**只支持快三->二同号(标准和单式)和三同号(单选和通选)
     * @param $num 是几不同 (2不同号,有2个;3不同,有3个)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function SameNumber($num,$number,$xu_number)
    {
//        $num = 2;
//        $chongfu = 3;
//        $number = '04,04,04';
//        $xu_number = '111,222,333,444,555,666';
        $number =(str_replace('0','',$number));//去掉字符串中的某个字符
        $number = explode(',',$number);
        $aa =array_count_values($number);
        $a = 0;
        if ($num==max($aa)){
            if ($num==2){
                $string = explode(',',substr($xu_number, 0, strrpos($xu_number, '-')));//截取用户勾选的位
                $strs = substr($xu_number, strrpos($xu_number, '-'));
                $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
                $values = explode(',', $str);//得到用户选择的号码
                arsort($aa);// 函数用于对数组单元从高到低进行排序并保持索引关系。
                $one=key($aa);//获取重号
                end($aa);//最后一个值
                $two = key($aa);//当前操作的数组的键
                if (in_array($one.$one,$string)!==false&&in_array($two,$values)!==false){
                    ++$a;
                }
            }
            if ($num==3){
                $xu_number = explode(',',$xu_number);
                foreach ($xu_number as $v){
                    $nu = str_split($v);
                    if (in_array(key($aa),array_unique($nu))){
                        ++$a;
                    }
                }
            }
        }
        return $a;
    }

    /**只支持二同号->二同复选
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Check($number,$xu_number)
    {
//        $number = '04,02,02';
//        $xu_number = '11*,22*,33*,44*,55*,66*';
        $number =(str_replace('0','',$number));//去掉字符串中的某个字符
        $number = explode(',',$number);
        $xu_number = explode(',',$xu_number);
        $aar = array_count_values($number);
        arsort($aar);
        $a = 0;
        if (reset($aar)>=2){
            foreach ($xu_number as $value){
                $nu = array_unique(str_split($value));
                if (in_array(key($aar),$nu)){
                    ++$a;
                }
            }
        }
        return $a;
    }

    /**只支持三不同号->不同和值和 和值
     * @param $num 三不同和值(1)  和值(2)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function AndValue($num,$number,$xu_number)
    {
//        $num = 1;
//        $number = '02,04,05';
//        $xu_number = '6$7$8$9$10$12$13$14$15';
        $number =(str_replace('0','',$number));//去掉字符串中的某个字符
        $number = explode(',',$number);
        $xu_number = explode('$',$xu_number);
        $aar = array_count_values($number);
        arsort($aar);
        $a = 0;
        if ($num==1){
            if (max($aar)==$num){
                if (in_array(array_sum($number),$xu_number)){
                    ++$a;
                }
            }
        }elseif ($num==2){
            if (in_array(array_sum($number),$xu_number)!==false){
                return array_sum($number);
            }
        }
        return $a;
    }

    /**只支持三同号中的通选
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Select($number,$xu_number)
    {
        // $number = '03,05,04';
        $number =(str_replace('0','',$number));//去掉字符串中的某个字符
        // $xu_number = '123,234,345,456';
        $number = explode(',',$number);
        $xu_number = explode(',',$xu_number);
        sort($number);
        $a = 0;
        if ($number[0]+1==$number[1]){
            if ($number[1]+1==$number[2]){
                foreach ($xu_number as $value){
                    $nn = str_split($value);
                    if (array_intersect($number,$nn)==$number){
                        ++$a;
                    }
                }
            }
        }
        return $a;
    }

    /**只支持快乐十分->首位数投或者首位红投
     * @param $num 首位数投(1) 首位红投(2)
     * @param $number
     * @param $xu_number
     * @return int
     */
    public function HappyFirstplace($num,$number,$xu_number)
    {
        //$num = 2;//数投
        //$number = '19,01,12,08,05,14,20,07';
        //$xu_number = '20';
        $number = explode(',',$number);
        $xu_number = explode(',',$xu_number);
        $a = 0;
        if ($num==1&&$number[0]!==19&&$number[0]!==20){
            if (in_array($number[0],$xu_number)){
                ++$a;
            }
        }elseif ($num==2){
            if ($number[0]==19||$number[0]==20){
                if (in_array($number[0],$xu_number)){
                    ++$a;
                }
            }
        }
        return $a;
    }

    /**只支持快乐十分->二连直和二连组
     * @param $num 直选(2) 组选(1)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function worth($num,$number,$xu_number)
    {
//        $num = 1;//直
//        $number = '10,16,17,08,20,11,04,19';
//        $xu_number = '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20';
        $number = explode(',', $number);
        for ($i = 0; $i <= count($number) - 2; $i++) {
            $res[] = $number[$i] . ',' . $number[$i + 1];
        }
        $a = 0;
        if ($num == 2) {//值
            $string = explode(',', substr($xu_number, 0, strrpos($xu_number, '-')));//截取用户勾选的位
            $strs = substr($xu_number, strrpos($xu_number, '-'));
            $str = str_replace('-', '', $strs);//得到用户选择的号码  去掉-
            $values = explode(',', $str);//得到用户选择的号码
            foreach ($res as $v) {
                $nu = explode(',', $v);
                if (count($nu) == count(array_unique($nu))) {
                    if (in_array($nu[0], $string) && in_array($nu[1], $values)) {
                        ++$a;
                    }
                }
            }
        } elseif ($num == 1) {//组
            $xu_number = explode(',', $xu_number);
            foreach ($res as $v){
                $aA =explode(',',$v);
                if (array_intersect($aA,$xu_number)==$aA){
                    ++$a;
                }
            }
        }
        return $a;
    }

    /**只支持快乐十分->三连直和三连组
     * @param $num 三直(3) 三组(1)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function ThreeStraight($num,$number,$xu_number)
    {
//        $num = 1;//直
//        $number = '10,16,17,08,20,11,04,19';
//        $xu_number = '01,02,03,04,05,06,07,08,09,11,12,13,14,15,16,17,18,19,20';
        $number = explode(',', $number);
        $a = 0;
        if ($num==3){//直选
            $xuan = explode('-',$xu_number);
            if (strpos($xuan[0],$number[0])!==false&&strpos($xuan[1],$number[1])!==false&&strpos($xuan[2],$number[2])!==false){
                ++$a;
            }
        }elseif ($num==1){//组选
            $xu_number = explode(',',$xu_number);
            if (in_array($number[0],$xu_number)&&in_array($number[1],$xu_number)&&in_array($number[2],$xu_number)){
                ++$a;
            }
        }
        return $a;
    }

    /**只支持快乐十分->快乐几
     * @param $num 快乐几
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function HappyCouple($num,$number,$xu_number)
    {
//        $num = 1;//直
//        $number = '14,01,20,19,02,17,11,03';
//        $xu_number = '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20';
        $number = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $a = 0;
        $arrays = new Arrays();
        $arr = $arrays->combinations($number,$num);
        foreach ($arr as $item){
            $res[] = implode(',',$item);
        }
        foreach ($res as $re){
            $n = explode(',',$re);
            if (array_intersect($n,$xu_number)==$n){
                ++$a;
            }
        }
        return $a;
    }

    /**北京赛车->只支持猜前几中的->第几名
     * @param $num 第几名
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TheFirsTfew($num,$number,$xu_number)
    {
//        $num = 1;//直
//        $number = '05,10,09,06,07,01,08,02,03,04';
//        $xu_number = '1,2,3,4,6,7,8,9,10';
        $number = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $a = 0;
        if (in_array($number[$num-1],$xu_number)){
            ++$a;
        }
        return $a;
    }
    /**北京赛车->只支持猜前几单式->第几名
     * @param $num 第几名
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TheFirsTfewdan($num,$number,$xu_number)
    {
//        $num = 1;
//        $number = '04,02,06,08,07,05,03,09,10,01';
//        $xun_number = '1$2$2$3$5$04$04$7';
        $number = explode(',',$number);
        $v = preg_replace('/^0*/', '', $number);//删除数字前面的0
        $b = 0;
        $xun_number = explode('$',$xu_number);
        $cc = preg_replace('/^0*/', '', $xun_number);//删除数字前面的0
        foreach ($cc as $item){
            if ($item==$v[$num-1]){
                ++$b;
            }
        }
        return $b;
    }
    /**北京赛车->只支持猜前几中的->前几名
     * @param $num 前几
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TheFirstFews($num,$number,$xu_number)
    {
//        $num = 3;//直
//        $number = '05,10,09,06,07,01,08,02,03,04';
//        $xu_number = '1,2,3,4,5,6,7,8,9,10-1,2,3,4,5,6,7,8,9,10-1,2,3,4,5,6,7,8,9,10';
        $number = explode(',', $number);
        $xu_number = explode('-', $xu_number);
        $a = 0;
        for ($i=0;$i<$num;++$i){
            $nn= explode(',',$xu_number[$i]);
            if (in_array($number[$i],$nn)){
                ++$a;
            }
        }
        if ($a&&$a==$num){
            $a=1;
        }else{
            $a=0;
        }
        return $a;
    }

    /**北京赛车->只支持猜前几中的单式->前几名
     * @param $num 前几
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TheFirstFewsdan($num,$number,$xu_number)
    {
//        $num = 2;
//        $number = '04,02,06,08,07,05,03,09,10,01';
//        $xun_number = '4,2$4,2$1,5';
        $number = explode(',',$number);
        $v = preg_replace('/^0*/', '', $number);//删除数字前面的0
        $data =  array_slice($v,0,$num);
        $b = 0;
        $xun_number = explode('$',$xu_number);
        $cc = preg_replace('/^0*/', '', $xun_number);//删除数字前面的0
        foreach ($cc as $item){
            $dd = explode(',',$item);
            if (array_intersect($dd,$data)==$data){
                ++$b;
            }
        }
        return $b;
    }

    /**北京赛车->定位胆
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Positioning($number,$xu_number)
    {
//        $number = '05,10,10,06,07,01,08,02,03,04';
//        $xu_number = '	--10-------';
        $number = explode(',', $number);
        $xu_number = explode('-', $xu_number);
        $a = 0;
        foreach ($xu_number as $k=>$item){
            $nn = explode(',',$item);
            if (in_array($number[$k],$nn)){
                ++$a;
            }
        }
        return $a;
    }

    /**北京赛车->趣味玩法->大小单双
     * @param $num 第几
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Size($num,$number,$xu_number)
    {
//        $num =2;
//        $number = '02,10,10,06,07,01,08,02,03,04';
//        $xu_number = '大小单双';
        $number = explode(',', $number);
        $a = 0;
        if ($number[$num-1]<=5){
            $res[]='小';
        }else{
            $res[]='大';
        }
        if ($number[$num-1]%2){
            $res[]='单';
        }else{
            $res[]='双';
        }
        foreach ($res as $re){
            if (strpos($xu_number,$re)!==false){
                ++$a;
            };
        }
        return $a;
    }

    /**北京赛车->趣味玩法->冠亚军的和值
     * @param $num 有几个的和值
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function ChampionshipAndValue($num,$number,$xu_number)
    {
//        $num =3;
//        $number = '03,10,06,05,09,01,02,08,07,04';
//        $xu_number = '3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19';
        $number = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $a = 0;
        $ns = array_slice($number,0,$num);
        $n = array_sum($ns);
        if (in_array($n,$xu_number)){
            return $n;
        }
        return $a;
    }

    /**北京赛车->趣味玩法->龙虎斗
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function PkLongHu($number,$xu_number)
    {
//        $number = '03,10,06,05,09,01,02,08,07,04';
//        $xu_number = '0,1,2,3,4,5,6,7,8,9';
        $number = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $res = [];
        if ($number[0]>$number[9]){
            $res[]='0';
        }else{
            $res[]='1';
        }
        if ($number[1]>$number[8]){
            $res[]='2';
        }else{
            $res[]='3';
        }
        if ($number[2]>$number[7]){
            $res[]='4';
        }else{
            $res[]='5';
        }
        if ($number[3]>$number[6]){
            $res[]='6';
        }else{
            $res[]='7';
        }
        if ($number[4]>$number[5]){
            $res[]='8';
        }else{
            $res[]='9';
        }
        $a = 0;
        foreach ($res as $r){
            if (in_array($r,$xu_number)==!false){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的直选复式(三星-后一)
     * @param $num 是几星 后选为(-)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function FucaiDirectElection($num,$number,$xu_number)
    {
//        $num = -2;
//        $number = '3,0,5';
//        $xu_number = '0123456789,12346789';
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $number = explode(',', $number);
        $xu_number = explode(',', $xu_number);
        $cc = array_slice($number, $c,$num);//得到开奖号码
        $a = 0;
        foreach ($cc as $k=>$v){
            if (strpos((string)$xu_number[$k],(string)$v)!==false){
                ++$a;
            }
        }
        if ($a==$num){
            $a = 1;
        }else{
            $a = 0;
        }
        return $a;
    }

    /**福彩的三星和值
     * @param $num 几星(3)
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function FucaiSun($num,$number,$xu_number)
    {
//        $num = 3;
//        $number = '3,0,5';
//        $xu_number = '5$6$8$15$16$25$26$27';
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $number = explode(',', $number);
        $xu_number = explode('$', $xu_number);
        $cc = array_slice($number, $c,$num);//得到开奖号码
        $a = 0;
        if (in_array(array_sum($cc),$xu_number)){
            ++$a;
        }
        return $a;
    }
    /**
     * 福彩的组三复式和组三单式,组六复式和组六单式
     * 见方法Threegroups
     */

    /**
     * 福彩的三星组选和值
     * 见方法ThreeTwoCombination
     */

    /**福彩的三星趣味012
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Taste($number,$xu_number)
    {
//        $number = '8,3,0';
//        $xu_number = '800$001$002$012$110$210';
        $number =explode(',',$number);
        $array_num =explode('$',$xu_number);
        $a = 0;
        foreach ($array_num as $item){
            $nn = str_split($item);
            if ($number[0]==$nn[0]&&$number[1]==$nn[1]&&$number[2]==$nn[2]){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的三星趣味大小
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function TasteSize($number,$xu_number)
    {
//        $number = '8,8,0';
//        $xu_number = '小大大$大大小';
        $number =explode(',',$number);
        foreach ($number as $item){
            if ($item>5){
                $r[]='大';
            }else{
                $r[]='小';
            }
        }
        $array_num =explode('$',$xu_number);
        $a = 0;
        foreach ($array_num as $item){
            $nn = str_split($item,3);
            if ($r[0]==$nn[0]&&$r[1]==$nn[1]&&$r[2]==$nn[2]){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的三星趣味质合
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Merging($number,$xu_number)
    {
//        $number = '7,6,0';
//        $xu_number = '质合质$合质合';
        $number = explode(',', $number);
        $xu_number = explode('$',$xu_number);
        foreach ($number as $item){
            if ($item==1||$item==2||$item==3||$item==5||$item==7){
                $r[] = '质';
            }else{
                if ($item>0){
                    $r[] = '合';
                }
            }
        }
        $a = 0;
        if (count($r)==3){
            foreach ($xu_number as $item){
                $v = str_split($item,3);
                if ($r[0]==$v[0]&&$r[1]==$v[1]&&$r[2]==$v[2]){
                    ++$a;
                }
            }
        }
        return $a;
    }


    /**福彩的三星趣味奇偶
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function Parity($number,$xu_number)
    {
//        $number = '8,8,0';
//        $xu_number = '奇奇偶$奇偶奇$偶奇偶$偶偶偶';
        $number =explode(',',$number);
        foreach ($number as $item){
            if ($item%2==0){
                $r[]='偶';
            }else{
                $r[]='奇';
            }
        }
        $array_num =explode('$',$xu_number);
        $a = 0;
        foreach ($array_num as $item){
            $nn = str_split($item,3);
            if ($r[0]==$nn[0]&&$r[1]==$nn[1]&&$r[2]==$nn[2]){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的二星->前二复式到后二单式
     * @param $num  是前几
     * @param $number
     * @param $xu_number
     * @return int
     */
    public function Fushi($num,$number,$xu_number)
    {
//        $num = 2;
//        $number = '5,0,1';
//        $xu_number = '0123456789';
        $c =0;
        if ($num<0){
            $c = $num;
            $num = str_replace("-", "", $num);
        }
        $number = explode(',', $number);
        $xu_number = str_split($xu_number);
        $cc = array_slice($number, $c,$num);//得到开奖号码
        $count = array_count_values($cc);
        $a=0;
        if (max($count)==1){
            if (array_intersect($cc,$xu_number)==$cc){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的->不定位->不定位复式
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function NoReset($number,$xu_number)
    {
//        $number = '0,5,9';
//        $xu_number = '0123456789';
        $number = explode(',', $number);
        $xu_number = str_split($xu_number);
        $a = 0;
        foreach ($number as $v){
            if (in_array($v,$xu_number)){
                ++$a;
            }
        }
        return $a;
    }

    /**福彩的->大小单双->前二到后二
     * 见方法 SingleAndDoubleSize
     */

    /** 香港六合彩->直选特码
     * @param $number 开奖号码
     * @param $xu_number 选号
     * @return int
     */
    public function HongKong($number,$xu_number)
    {
//        $number = '21,25,20,10,01,05,50';
//        $xu_number = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49';
        $number = explode(',',$number);
        $xu_number = explode(',',$xu_number);
        $end_number = end($number);
        $b = 0;
        if (in_array($end_number,$xu_number)){
            ++$b;
        }
        return $b;
    }
}