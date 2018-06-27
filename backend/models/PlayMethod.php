<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/16
 * Time: 10:59
 */

namespace backend\models;


use yii\db\ActiveRecord;

class PlayMethod extends ActiveRecord
{
    /**彩种名称
     * @return \yii\db\ActiveQuery
     */
    public function getLottery()
    {
        return $this->hasOne(HowToPlay::className(),['id'=>'lottery_type']);
    }
    /**类型名称
     * @return \yii\db\ActiveQuery
     */
    public function getNam()
    {
        return $this->hasOne(BigColor::className(),['id'=>'type']);
    }

    public function attributeLabels()
    {
        return [
          'name'=>'玩法名称',
          'lottery_type'=>'彩种名称',
          'type'=>'彩票类型',
          'note'=>'限制注数(空表示不限)',
          'status'=>'状态',
        ];
    }
    public function rules()
    {
        return[
            [['name','lottery_type','type','status'],'required'],
            ['note','safe'],
            ['note','integer'],
            ['note','number'],
            ['note','compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }
}