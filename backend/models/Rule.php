<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Rule extends  ActiveRecord{

public function rules()
{
    return [
        ['amount','number'],
        ['name','required']


    ];
}
  public function attributeLabels()
  {
      return[
        'name'=>'优惠卷的规则名称',
          'amount'=>'金额'

      ];
  }

}


