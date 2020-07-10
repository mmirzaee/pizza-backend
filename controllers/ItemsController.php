<?php

namespace app\controllers;

use app\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;


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

    public function checkAccess($action, $model = null, $params = [])
    {
        // Only admins can add/modify/delete menu items
        if (in_array($action, ['update', 'delete', 'create'])) {
            $user = User::findOne(\Yii::$app->user->getIdentity()->getId());
            if (!in_array($user->getRoleName(), ['theCreator', 'admin'])) {
                throw new ForbiddenHttpException(sprintf('You can\'t %s menu items.', $action));
            }
        }
    }
}
