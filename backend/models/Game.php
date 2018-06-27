<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3
 * Time: 14:20
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Game extends ActiveRecord
{


    public function rules()
    {
        return[
            [['name','zgjj','zdjj','zgzs','status'],'safe']
        ];
    }
}