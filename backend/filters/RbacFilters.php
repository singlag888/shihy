<?php

namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;


/**
 * Created by PhpStorm.
 * User: 18079
 * Date: 2017/12/29
 * Time: 15:59
 */
class RbacFilters extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can($action->uniqueId)){
            //没有登录
            if (\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException(403,'对不起,没有权限');
        }
        return true;
    }
}