<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 17:02
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Vip extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'分层名称',
            'min'=>'最低入款笔数',
            'max'=>'最低入款总额',
            'intro'=>'备注',
        ];
    }

    public function rules()
    {
        return[
            [['name','min','max','intro'],'required']
        ];
    }
}