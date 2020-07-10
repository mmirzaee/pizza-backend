<?php

use yii\db\Migration;

/**
 * Class m200710_141821_add_price_to_order_items_and_total_to_order
 */
class m200710_141821_add_price_to_order_items_and_total_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('orders', 'total', $this->string(64));
        $this->addColumn('order_items', 'unit_price', $this->string(32));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('orders', 'total');
        $this->dropColumn('order_items', 'unit_price');
    }

}
