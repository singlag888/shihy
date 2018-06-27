<?php
/**
 * 老师表的模型
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Teacher extends ActiveRecord
{
//    public $img;//图片上传
//    public $imageFiles;//图片上传
    //public $file;//图片上传
    //配置验证规则
    public function rules()
    {
        return [
            [['name','age','sex','email','phone','certificate','opposite','work_unit','domicile','education','balance','status',],'required'],
            ['email','email'],
            ['phone','match','pattern'=>'/^[1][3|4|5|7|8][0-9]{9}$/'],
            [['certificate','opposite'],'file','extensions'=>['png', 'jpg', 'gif']],
            //[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
            [['age','life','star','phone'],'integer'],
            [['star','img'],'safe']
        ];
    }
    //Labels的显示
    public function attributeLabels()
    {
        return [
            'certificate'=>'资格证正面',
            'opposite'=>'资格证里面',
            'name'=>'老师名称',
            'age'=>'年龄',
            'sex'=>'性别',
            'phone'=>'手机号',
            'email'=>'邮箱',
            'life'=>'工龄',
            'work_unit'=>'单位',
            'domicile'=>'居住地',
            'img'=>'资格证',
            'star'=>'星级',
            'balance'=>'余额',
            'education'=>'学历',
            'status'=>'状态'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}