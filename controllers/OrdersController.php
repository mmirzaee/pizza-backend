<?php

namespace app\controllers;

use app\models\Items;
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

        $behaviors['authenticator']['optional'] = ['place-order'];


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


        $model->user_id = 0;
        if ($user = \Yii::$app->user->getIdentity()) {
            $model->user_id = $user->getId();
        }
        $model->status = Orders::STATUS_SUBMITTED;
        $model->updated_at = time();
        $model->created_at = time();

        if ($model->validate() && $order_items = json_decode($post_data['items'])) {
            $model->save();
            $total = 0;

            foreach ($order_items as $oi) {
                if ($item = Items::findOne(['id' => $oi->item_id])) {
                    $order_item = new OrderItems();
                    $order_item->item_id = $oi->item_id;
                    $order_item->quantity = $oi->quantity;
                    $order_item->order_id = $model->id;
                    $order_item->unit_price = $item->price;
                    $order_item->save();

                    $total += floatval($item->price) * $oi->quantity;
                }
            }


            // saving total price of order
            $total += $this->calcDeliveryFee($model);
            $model->total = '' . $total;
            $model->save();


            return $model;
        } else {
            $errors = $model->errors;
            \Yii::$app->response->setStatusCode(422);
            return $errors;
        }
    }

    public function actionHistory()
    {
        $orders = Orders::find()->where(['user_id' => \Yii::$app->user->getIdentity()->getId()])->orderBy('updated_at DESC')->all();
        $ret = [];
        foreach ($orders as $order) {
            $normalized_res = [
                'created_at' => date('Y/m/d H:i:s', $order->created_at),
                'address' => $order->address,
                'mobile' => $order->mobile,
                'full_name' => $order->full_name,
                'total' => $order->total,
                'status' => $order->status,
            ];

            // Generating list of items
            $items = [];
            foreach ($order->items as $item) {
                $items[] = ['title' => $item->item->title, 'quantity' => $item->quantity, 'unit_price' => $item->unit_price];
            }

            // Adding Delivery Fee to the list
            $delivery_fee = $this->calcDeliveryFee($order);
            $items[] = ['title' => 'Delivery Fee', 'quantity' => 1, 'unit_price' => $delivery_fee];

            $normalized_res['items'] = $items;
            $ret[] = $normalized_res;
        }

        return $ret;
    }

    private function calcDeliveryFee($order)
    {
        // TODO: we can calc it based on manythings but for now lets keep it simple!
        return 5;
    }
}
