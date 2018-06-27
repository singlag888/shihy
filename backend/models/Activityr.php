<?php
/**
 * 活动记录模型
 */

namespace backend\models;




use yii\db\ActiveRecord;

class Activityr extends ActiveRecord
{

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'title'=>'活动主题',
            'time'=>'申请时间',
            'content'=>"申请信息",
            'price'=>'奖励金额',
            'pepole'=>'审核人',
            'xx'=>'审核信息',
            'status'=>'状态',
        ];
    }

    public function rules()
    {
        return [
            [['username','title','time','content','price','status'],'required'],
            [['pepole','xx'],'safe'],
        ];
    }

    //关联审核人
    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
    //关联用户信息
    public function getAdmini()
    {
        return $this->hasOne(Admini::className(),['id'=>'admin_id']);
    }
    //关联活动信息
    public function getActivity()
    {
        return $this->hasOne(Activity::className(),['id'=>'activity_id']);
    }
}