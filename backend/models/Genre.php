<?php
/**
 * 用户类型模型
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Genre extends ActiveRecord
{
    public function rules()
    {
        return [
          [['type','name','status'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'type'=>'类型',
          'name'=>'名称',
          'status'=>'状态',
        ];
    }
}