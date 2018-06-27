<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23 0023
 * Time: 16:07
 */

namespace frontend\controllers;


use yii\web\Controller;

class LogoController extends Controller
{
    public $enableCsrfValidation=false;

    /**
     * 图片列表
     */
    public function actionIndex(){
            return $this->render('index');
    }


    /**
     * 上传图片
     */
    public function actionAdd(){
        $upload_url = 'http://192.168.0.99:5000/upload';

        $image_file = $_FILES;

// 获取图片后缀
        $value = explode(".", $image_file);
        $extension = strtolower(array_pop($value));

// 生成本地临时存储路径，并生成相应文件夹
        $dir = 'aurthur';
        $save_path = 'uploads/'.$dir.'/'.date('Y').'/'.date('md').'/';
        $save_rule = md5(uniqid(mt_rand(), true));
        if(!is_dir($save_path)){
            if(false === mkdir($save_path, 0700, true)){
                exit('创建文件夹失败');
            }
        }
        $save_image_file = $save_path.$save_rule.".$extension";

// 把图片存储到临时路径
        file_put_contents($save_image_file, file_get_contents($image_file));

// 获取临时保存的图片的真实地址(绝对路径)
        $realpath = realpath($save_image_file);

// 上传图片到 zimg 图片存储服务
        $ch = curl_init();

// 将图片内容读取到变量 $post_data ;
        $post_data = file_get_contents($realpath);

        $headers = array();
// 一定要添加此 header
        $headers[] = 'Content-Type:'.$extension;

        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// raw_post方式

        $info = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($info, true);
        $signature = $json['info']['md5'];
        echo $signature;
    }


    /**
     * 图片修改
     */
    public function actionUpdate(){

    }


    /**
     * 图片删除
     */
    public function actionDelect(){

    }
}