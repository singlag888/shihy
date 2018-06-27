<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/27
 * Time: 9:53
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Ratio extends ActiveRecord
{
    public function rules()
    {
        return[
          ['name','safe','message'=>'不能为空'],
          ['bonus','safe'],
          ['bonuss','safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'游戏名',
            'bonus'=>'游戏总类名称',
            'bonuss'=>'设置奖金',
        ];
    }
}