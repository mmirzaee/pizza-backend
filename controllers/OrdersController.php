<?php

namespace app\controllers;

use app\models\OrderItems;
use app\models\Orders;
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
        $model = new Orders();

        $post_data = \Yii::$app->request->post();

        foreach ($model->attributes() as $attr) {
            if (isset($post_data[$attr])) {
                $model->{$attr} = $post_data[$attr];
            }
        }

        $model->user_id = isset($post_data['user_id']) ? $post_data['user_id'] : 0;
        $model->status = Orders::STATUS_SUBMITTED;
        $model->updated_at = time();
        $model->created_at = time();

        if ($model->validate() && $order_items = json_decode($post_data['items'])) {
            $model->save();
            foreach ($order_items as $oi) {
                $item = new OrderItems();
                $item->item_id = $oi->item_id;
                $item->quantity = $oi->quantity;
                $item->order_id = $model->id;
                $item->save();
            }
            return $model;
        } else {
            $errors = $model->errors;
            \Yii::$app->response->setStatusCode(422);
            return $errors;
        }
    }

    public function actionHistory()
    {

    }
}
