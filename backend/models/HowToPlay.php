<?php
/**
 * 玩法
 */

namespace backend\models;


use yii\db\ActiveRecord;

class HowToPlay extends ActiveRecord
{
    public function rules()
    {
        return [
          [['name','status'],'required'],
          ['name','unique'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'彩种名称',
            'status'=>'状态',
        ];
    }
    /**
     * 在插入钱执行的这个方法
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){//父类执行成功
            if ($this->isNewRecord){//判断是新的纪录
                $this->add_time=time();//设置添加的时间
            }
            return true;
        }
        return false;
    }
}