<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:38
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    public function attributeLabels()
    {
        return [
          'name'=>'文章分类名称',
            'intro'=>'备注',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required','message'=>'不能为空'],
        ];
    }

}