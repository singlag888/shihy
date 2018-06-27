<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9 0009
 * Time: 14:32
 */

namespace frontend\models;


use yii\base\Model;

class Publics extends Model
{

    /**
     * 数组
     * @return mixed
     */
    public function Arraies()
    {
        $result = [
            'error'=>1,
            'msg'=>'',
            'data'=>[]
        ];
        return $result;
    }


    /**
     * 账号错误
     * @return mixed
     */
    public function Username($msg,$error,$data)
    {
        $result['msg']=$msg;
        $result['error']=$error;
        $result['data']=$data;
        return $result;
    }
}