<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10 0010
 * Time: 11:36
 */

namespace frontend\models;


use backend\models\BigColor;
use yii\db\ActiveRecord;

class Ssc1 extends ActiveRecord
{
    public function getNam()
    {
        return $this->hasOne(BigColor::className(),['id'=>'big_color_id']);
    }
  public function attributeLabels()
  {
      return[
          'name'=>"玩法名称",
          'bonuss'=>"返点",
          'status'=>"状态",
          'note'=>'限制注数(不填为无限)'
      ];
  }
  public function rules()
  {
      return[
        [['name','bonuss','status'],'required'],
          ['note','safe'],
          ['note','integer'],
          ['note','number'],
          ['note','compare', 'compareValue' => 0, 'operator' => '>'],
      ];
  }
}