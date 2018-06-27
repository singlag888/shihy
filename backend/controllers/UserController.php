<?php

namespace backend\controllers;
use backend\models\AuthItem;
use backend\models\Avatar;
use backend\models\PasswordForm;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use backend\models\User;
use Yii;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\UploadedFile;

class UserController extends CommonController
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 用户列表
     */
    public function actionList()
    {
        Yii::$app->user->identity->username;
        $data = User::find();
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '15']);
        $user = $data->joinWith('usergroup')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'user'=>$user,
            'pages' => $pages
        ]);
    }
    /**
     * 新增用户
     */
    public function actionCreate()
    {
        $model = new User();
        $model1 = new AuthItem();
        $auth = Yii::$app->authManager;
        $item = $auth->getRoles();
        $itemArr =array();
        foreach($item as $v){
            $itemArr[] .= $v->name;
        }
        foreach($itemArr as $key=>$value)
        {
            $item_one[$value]=$value;
        }
        if ($model->load(Yii::$app->request->post())) {
            /* 表单验证 */
            $post = Yii::$app->request->post();
            $data = $post['User'];
            $data['created_at']     = time();
            /* 表单数据加载和验证，具体验证规则在模型rule中配置 */
            /* 密码单独验证，否则setPassword后密码肯定符合rule */
            if (empty($data['auth_key']) || strlen($data['auth_key']) < 6) {
                $this->error('密码为空或小于6字符');
            }
            $model->setAttributes($data);
            $model->generateAuthKey();
            $model->setPassword($data['auth_key']);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                //获取插入后id
                $user_id = $model->attributes['id'];
                $role = $auth->createRole($post['AuthItem']['name']);     //创建角色对象
                $auth->assign($role, $user_id);                           //添加对应关系
                return $this->redirect(['list']);
            }else{
                var_dump($model->getErrors());die;
                $this->error('操作错误');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'model1' => $model1,
                'item' => $item_one
            ]);
        }
    }
    public function actionUpload()
    {
        $img = UploadedFile::getInstanceByName('file');
        $file = '/upload/' . uniqid() . '.' . $img->getExtension();
        if ($img->saveAs(\Yii::getAlias('@webroot') .$file)) {
            return Json::encode(['url' =>  $file]);
        }
    }

    public function actionEdit($id)
    {
        $request = new Request();
        $id = Yii::$app->request->get('id');
        $model = User::find()->joinWith('usergroup')->where(['id'=>$id])->one();
        $auth = Yii::$app->authManager;
        $item = $auth->getRoles();
        $itemArr =array();
        foreach($item as $v){
            $itemArr[] .= $v->name;
        }
        foreach($itemArr as $key=>$value)
        {
            $item_one[$value]=$value;
        }
        $model1 = $this->findModel($id);
        if ($model1->load(Yii::$app->request->post())) {

           $passowrd =Yii::$app->security->generatePasswordHash($model1->password);

            $model1->save(false);
            Yii::$app->session->setFlash('success','修改成功');
            if(!empty($post['AuthAssignment']['item_name'])){
                //分配角色
                $role = $auth->createRole($post['AuthAssignment']['item_name']);    //创建角色对象
                $user_id = $id;
                $auth->revokeAll($user_id);
                $auth->assign($role, $user_id);       //分配角色与用户对应关系
            }
            return $this->redirect(['list']);
            return $this->redirect(['user/update', 'id' => $model1->id]);
        }
        return $this->render('edit',[
            'model' => $model,
            'item' => $item_one
        ]);
    }
    /**
     * 更新用户
     */
    public function actionUpdate(){
        $id = Yii::$app->request->get('id');
        $model = User::find()->joinWith('usergroup')->where(['id'=>$id])->one();
        $auth = Yii::$app->authManager;
        $item = $auth->getRoles();
        $itemArr =array();
        foreach($item as $v){
            $itemArr[] .= $v->name;
        }
        foreach($itemArr as $key=>$value)
        {
            $item_one[$value]=$value;
        }
        $model1 = $this->findModel($id);
        if ($model1->load(Yii::$app->request->post())) {
            //$post = Yii::$app->request->post();
//var_dump($model1);die;
            //更新密码
//            if(empty($post['User']['auth_key_new'])){
//                $model1->setPassword($post['User']['auth_key_new']);
//                $model1->generateAuthKey();
//            }else{
//                $model1->auth_key = $post['User']['auth_key'];
//            }
            $model1->password_hash = Yii::$app->security->generatePasswordHash($model1->password_hash);
//            $model1->email = $post->email
//            )
            $model1->save(false);
            if(!empty($post['AuthAssignment']['item_name'])){
                //分配角色
                $role = $auth->createRole($post['AuthAssignment']['item_name']);    //创建角色对象
                $user_id = $id;
                $auth->revokeAll($user_id);
                $auth->assign($role, $user_id);       //分配角色与用户对应关系
            }
            return $this->redirect(['list']);
            return $this->redirect(['user/update', 'id' => $model1->id]);
        }

        return $this->render('update',[
            'model' => $model,
            'item' => $item_one
        ]);
    }

    /**
     * 删除用户
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->success('删除成功！','list');
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            $this->error('删除失败！');
        }
    }

    public function actionAvatar()
    {
        $model = Avatar::findOne(['id'=>Yii::$app->user->id]);
        if (Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['list']);
            }else{
                $model->getErrors();
            }
        }
//        var_dump($model);die;
        return $this->render('avatar',['model'=>$model]);
    }
    //配置验证码的
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4
            ]
        ];
    }
}
