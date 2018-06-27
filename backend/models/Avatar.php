<?php
/**
 * Created by PhpStorm.
 * User: 18079
 * Date: 2018/2/24
 * Time: 10:19
 */

namespace backend\models;


class Avatar extends User
{
    public $img;
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            ['logo','required']
        ];
    }
    public function attributeLabels()
    {
        return [
          'logo'=>'头像'
        ];
    }
}