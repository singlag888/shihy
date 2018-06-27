<?php
/**
 * 站内信模型
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Letter extends ActiveRecord
{
        public function attributeLabels()
        {
            return [
              'name'=>'名称',
              'url'=>'路由',
            ];
        }

        public function rules()
        {
            return[
              [['name','url'],'required']
            ];
        }
}