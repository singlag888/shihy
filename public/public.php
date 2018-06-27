<?php
use backend\models\Admin;
/**
 * @param $msg 返回提示信息
 * @param $error 返回的状态码 -1失败 1成功
 * @param $data  返回的数据
 */
function messages($msg='',$error=-1,$data=[]){
    if (empty($data)){
        $datas = new \stdClass();
    }else{
        $datas = new \stdClass();
        $datas->list = $data;
    }
    $result['msg']=$msg;
    $result['error']=$error;
    $result['data']=$datas;
    return $result;
}


function token($admin_id,$token)
{
    $admin = Admin::findOne(['id' => $admin_id]);
    if ($token != $admin->tokens) {
        return 0;
    } else {
        return 1;
    }
}
function resis($color_id)
{
    $data = array(
        "color" => $color_id
    );
    $url="http://gif.shenbang9.com/redis/redis";
    $ch = curl_init();//创建连接
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//将数组转换为URL请求字符串，否则有些时候可能服务端接收不到参数
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); //接收服务端范围的html代码而不是直接浏览器输出
    curl_setopt($ch, CURLOPT_HEADER, false);
    $responds = curl_exec($ch);//接受响应
    curl_close($ch);//关闭连接
    return json_decode($responds,true)['data']['list']['redis'];
}



function curl($url,$data){
    $data =json_encode($data);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    curl_close($curl);
    $datas = json_decode($res,true);
    return $datas;
}


function getCity($ip = '')
{
    if($ip == ''){
        $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
        $ip=json_decode(file_get_contents($url),true);
        $data = $ip;
    }else{
        $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
        $ip=json_decode(file_get_contents($url));
        if((string)$ip->code=='1'){
            return false;
        }
        $data = (array)$ip->data;
    }

    return $data;
}

