<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 10:11
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleImmediately extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'content'=>"简介",
            'title'=>"标题",
            'status'=>"状态",
        ];
    }
    public function rules()
    {
        return [
            ['content','required'],
            ['title','required'],
            ['status','required'],
        ];
    }
}