<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 14:45
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Immediately extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'title'=>'公告标题',
            'object'=>'发布对象',
            'type'=>'公告类型',
            'start_time'=>"生效时间",
            'end_time'=>'结束时间',
            'time'=>'发布时间',
            'name'=>'发布者',
            'status'=>'状态',
        ];
    }

    public function rules()
    {
        return [
            [['title','object','type','status'],'required'],
            [['time','name','start_time','end_time'],'safe'],
        ];
    }
}