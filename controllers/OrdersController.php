<?php

namespace app\controllers;

use app\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;


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


    public function checkAccess($action, $model = null, $params = [])
    {
        // Only admins can add/modify/delete menu items
        if (!in_array($action, ['place-order', 'list'])) {
            $user = User::findOne(\Yii::$app->user->getIdentity()->getId());
            if (!in_array($user->getRoleName(), ['theCreator', 'admin'])) {
                throw new ForbiddenHttpException(sprintf('You can\'t %s orders.', $action));
            }
        }
    }


    public function actionPlaceOrder()
    {
    }

    public function actionList()
    {

    }
}
