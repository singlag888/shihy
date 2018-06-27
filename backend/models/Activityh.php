<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22
 * Time: 10:31
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Activityh extends ActiveRecord
{

    public function rules()
    {
        return [
          [['content','sort','status'],'required'],
            ['sort','integer'],
            ['sort','number'],
            ['sort','unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'content'=>'活动内容',
          'sort'=>'序号',
          'status'=>'状态',
        ];
    }
}