<?php
/**
 * 分类活动记录表
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Occupation extends ActiveRecord
{
    public $img;//图片
    public function rules()
    {
        return [
            [['category_name','img'],'required'],
//            ['img','file'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'category_name'=>'分类名称',
            'img'=>'图片'
        ];
    }
}