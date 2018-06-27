<?php
/**
 * Created by PhpStorm.
 * User: 18079
 * Date: 2018/2/13
 * Time: 18:07
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Activity extends ActiveRecord
{
    public $content;//活动详情

    public function rules()
    {
        return [
            [['theme', 'type', 'objects', 'review','status' ], 'required'],
            [['status','objects'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'theme'=>'活动主题',
            'type'=>'活动对象类型',
            'objects'=>'活动对象',
            'review'=>'需审核',
            'status'=>'状态',
            'starting_time'=>'开始时间',
            'end_time'=>'结束时间',
        ];
    }
}