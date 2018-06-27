<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 9:58
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Admin extends ActiveRecord
{
    public $result = [];
    //关联上级用户的名称
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'username']);
    }
    public function Register($player,$password)
    {
        $data = array(
            'MerchantCode' => 'OZs9oP2s9lazLTK4kzca4TaDTUkdmpIO',
            'PlayerId' => $player,
            'Currency' => 'CNY',
            'Password' => $password,
            'Country' => 'CN',
        );
        $url = "http://imone.imaegisapi.com/Player/Register";
        $data = $this->curl($url, $data);
        return $data;
    }

    function curl($url,$data){
        $data =json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);
        $datas = json_decode($res,true);
        return $datas;
    }

    public function getAdmins()
    {
        return $this->hasOne(Admin::className(),['id'=>'parent_id']);
    }

    public function attributeLabels()
    {
        return [
            'username'=>"用户名",
            'last'=>"充值金额 (负数为扣款)",
            'password_login'=>"登录密码",
            'password_pay'=>"支付密码",
            'type'=>"用户类型",
            'status'=>"状态",
            'max'=>"最大返点",
            'min'=>"最小返点",
        ];
    }

    public function rules()
    {
        return [
            ['username','required','message'=>"用户名不能为空"],
            ['username', 'unique', 'message' => '这个用户名已经被采取。'],
            ['username', 'string', 'min'=>5, 'tooShort' => '用户名最少五位'],
            ['password_login', 'string', 'min'=>5, 'tooShort' => '密码最少五位'],
            ['password_login','required','message'=>"登录密码不能为空"],
            ['password_pay','required','message'=>"支付密码不能为空"],
            ['status','safe','message'=>"状态不能为空"],
            ['last','safe'],
            //['type','required'],
            ['max','safe'],
            ['min','safe'],
            ['login_time','safe'],
            ['login_ip','safe'],
        ];
    }
    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->token = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }



    /**
     * 递归查询所有下级ID
     */
    public function all($admin_id){
        //查出ID为1下面得所有下级
        $admin = Admin::find()->where(['parent_id'=>$admin_id])->all();
        if($admin){
            //循环取出ID
            foreach ($admin as $a){
                //将每次循环得到得ID保存到数组中
                $this->result[] = $a['id'];
                $this->all($a['id']);
            }
        }else{
            return $this->result;
        }
//        $ss[] = $result;
        return $this->result;

    }

    public static function online($admin_id)
    {
        static $child =0;
        $admin = Admin::find()->where(['parent_id'=>$admin_id])->all();
        if ($admin){
            foreach ($admin as $item){
                if ($item->online==1){
                    ++$child;
                }
                self::online($item->id);
            }
        }
        return $child;
    }



}