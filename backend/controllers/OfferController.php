<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/1 0001
 * Time: 14:22
 */

namespace backend\controllers;


class OfferController extends CommonController
{

    /**
     * 死数据  分红设置
     * @return string
     */
    public function actionIndex(){
        return $this->render('index1');
    }

    /**
     * 死数据  红包列表
     * @return string
     */
    public function actionFh(){
    return $this->render('index');
    }



    /**
 * 死数据  契约分红设置
 * @return string
 */
    public function actionQyfgsz(){
        return $this->render('qyfgsz');
    }



    /**
     * 死数据  契约分红记录
     * @return string
     */
    public function actionQyfgjl(){
        return $this->render('qyfgjl');
    }



    /**
     * 死数据  日工资列表
     * @return string
     */
    public function actionRgzlb(){
        return $this->render('rgz');
    }



    /**
     * 死数据  契约工资设置
     * @return string
     */
    public function actionQygzsz(){
        return $this->render('qygzsz');
    }



    /**
     * 死数据  契约工资记录
     * @return string
     */
    public function actionQygzjl(){
        return $this->render('qygzjl');
    }


    /**
     * 死数据 三级退佣设置
     * @return string
     */
    public function actionSjtysz(){
        return $this->render('sjtysz');
    }



    /**
     * 死数据   推荐退佣设置
     * @return string
     */
    public function actionTjtysz(){
        return $this->render('tjtysz');
    }



    /**
     * 死数据 消费退佣统计
     * @return string
     */
    public function actionXftytj(){
        return $this->render('xftytj');
    }


    /**
     * 死数据 亏损退佣统计
     * @return string
     */
    public function actionKstytj(){
        return $this->render('kstytj');
    }



    /**
     * 死数据 推荐消费退佣统计
     * @return string
     */
    public function actionTjxftytj(){
        return $this->render('tjxftytj');
    }



    /**
     * 死数据 推荐亏损退佣统计
     * @return string
     */
    public function actionTjkstytj(){
        return $this->render('tjkstytj');
    }

}