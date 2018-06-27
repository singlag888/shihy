<?php
/**
 *  用户变动的金额
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Change extends ActiveRecord
{
    /**
     * 添加用户的帐变
     */
    public function Change($data=[])
    {
        $change = new Change();
        $change->admin_id = $data['admin_id'];//用户ID
        $change->updated_time = time();//变动时间
        $change->username = $data['username'];//用户账号
        $change->type = $data['type'];//变动类型
        $change->last_price = $data['last_price'];//变动金额
        $change->price = $data['price'];//变动后余额
        $change->front_price = $data['front_price'];//变动前余额
        $change->game_type = $data['game_type'];//游戏类型
        $change->intro = $data['intro'];//备注
        $change->save();
    }
}