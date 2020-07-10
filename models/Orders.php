<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string $address
 * @property string $mobile
 * @property string $full_name
 * @property int $created_at
 * @property int $updated_at
 * @property string $total
 */
class Orders extends \yii\db\ActiveRecord
{

    const STATUS_SUBMITTED = 1;
    const STATUS_IN_THE_WAY = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_CANCELED = 0;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'address'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 16],
            [['full_name', 'total'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'address' => 'Address',
            'mobile' => 'Mobile',
            'full_name' => 'Full Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'total' => 'Total',
        ];
    }

    public function extraFields()
    {
        return ['items'];
    }

    public function getItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'id']);
    }
}
