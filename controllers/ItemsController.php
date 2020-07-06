<?php

namespace app\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;


class ItemsController extends ActiveController
{
    public $modelClass = 'app\models\Items';

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

        $behaviors['authenticator']['except'] = ['index', 'view', 'options'];

        return $behaviors;
    }
}
