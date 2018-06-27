<?php
/**
 * 腾讯分分彩的历史中奖
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class SsctxffcWin extends ActiveRecord
{
    /**
     * @param $db 开奖的表
     * @param array $data 用户中奖的数据
     */
    public function Win($db,$data=[])
    {
        if ($db = 'ssctxffc_win'){
            $d = new SsctxffcWin();
        }
        $d->admin_id =$data['admin_id'];//用户id
        $d->username =$data['username'];//用户名
        $d->game_play =$data['game_play'];//游戏玩法
        $d->winning_amount =$data['winning_amount'];//中奖金额
        $d->winning_time =time();//中奖时间
        $d->save();
    }
}