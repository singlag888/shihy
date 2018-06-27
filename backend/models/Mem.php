<?php
/**
 * Created by PhpStorm.
 * User: 18079
 * Date: 2018/2/16
 * Time: 14:19
 */

namespace backend\models;


class Mem extends Member
{
    public static function tableName()
    {
        return 'member';
    }

    public function rules()
    {
        return [
          [['coupon_id','rule_id'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'coupon_id'=>'优惠卷名称',
          'rule_id'=>'优惠规则',
        ];
    }
}