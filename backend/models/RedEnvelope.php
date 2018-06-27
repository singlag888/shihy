<?php
/**
 * 红包模型
 */

namespace backend\models;


use yii\db\ActiveRecord;

class RedEnvelope extends ActiveRecord
{
    //关联用户信息
    public function getAdmini()
    {
        return $this->hasOne(Admini::className(),['id'=>'admin_id']);
    }

    public function rules()
    {
        return [
          [['content','amount','number','starting_time','end_time'],'required']  ,
            ['amount','number'],
            ['number','integer'],
            ['end_time','compare','compareAttribute'=>'starting_time','operator'=>'>=','message'=>'结束时间时间的值必须大于开始时间'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'content'=>'红包内容',
            'amount'=>'红包金额',
            'number'=>'红包个数',
            'starting_time'=>'开始时间',
            'end_time'=>'结束时间',
        ];
    }
}