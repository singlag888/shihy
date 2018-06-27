<?php
namespace backend\controllers;

use backend\models\Admin;
use backend\models\Recharge;
use backend\models\Withdrawals;
use common\models\User;
use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Menu;

/**
 * 首页控制器
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','captcha'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4
            ]
        ];
    }





    public function actionIndex()
    {
        $begintime=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d'),date('Y')));
        $endtime=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);
        //入款
        $model1 = Recharge::find()->where(['status'=>0])->count();
        //出款
        $total1 = Withdrawals::find()->where(['status'=>0])->count();
        //统计网页今日注册人数
        $register  = Admin::find()->andWhere(['>=','created_time',$begintime])->andWhere(['<=','created_time',$endtime])->count();
        //统计网页当前在线人数

        $user_id=Yii::$app->user->identity->getId();
        $touxiang = User::findOne($user_id);
        $user_info = Yii::$app->authManager->getRolesByUser($user_id);
        $menu = new Menu();
        $menu = $menu->getLeftMenuList();
        return $this->render('index',[
            'menu' => $menu,
            'model1' => $model1,
            'total1' => $total1,
            'register' => $register,
            'user_info' => key($user_info),
            'touxiang'=>$touxiang->logo
        ]);
    }

    /**
     * 登录
     */
    public function actionLogin()
    {
//        var_dump(Yii::$app->request->post());die;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $model->loginLog();
            return $this->redirect('index');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 注销登录
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


}
