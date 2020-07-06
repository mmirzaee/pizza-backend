<?php

namespace app\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;


class OrdersController extends ActiveController
{
    public $modelClass = 'app\models\Orders';

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        $behaviors['authenticator']['except'] = ['place-order'];


        return $behaviors;
    }

    public function actionPlaceOrder(){

    }

    public function actionList(){
        
    }
}
