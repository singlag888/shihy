<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/6 0006
 * Time: 15:32
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Logo extends ActiveRecord
{
    public $status;
    public function attributeLabels()
    {
        return[
          'file'=>'LOGO',
            'title'=>'标题'
        ];
    }

    public function rules()
    {
        return [
            [['title','file'],'required'],
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