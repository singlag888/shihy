<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 10:00
 */

namespace backend\models;


use yii\db\ActiveRecord;

class BankList extends ActiveRecord
{
    public function attributeLabels()
    {
        return[
            'logo'=>'LOGO',
            'name'=>'银行名称',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['name','logo'],'required'],
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            $file_path = 'upload/' . time() .".". $this->file->extension;
            $this->file->saveAs($file_path);
            return $file_path;
        } else {
            return false;
        }
    }
}