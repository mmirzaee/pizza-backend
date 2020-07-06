<?php

use yii\db\Migration;

/**
 * Class m200706_125423_Add_initial_tables_for_pizza_delivery
 */
class m200706_125423_Add_initial_tables_for_pizza_delivery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        /*
         *  We can add another table for managing file/attachments and simply use AWS S3 or Minio.
         *  But lets keep it simple! Just upload images somewhere and save the url into DB
         */

        $this->createTable('items', [
            'id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull(),
            'price' => $this->integer()->notNull(),
            'image_url' => $this->string(),
            'description' => $this->string(),
        ]);

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'status' => $this->integer(1)->notNull()->defaultValue(1),
            'address' => $this->string()->notNull(),
            'mobile' => $this->string(16),
            'full_name' => $this->string(64),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('order_items', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'item_id' => $this->integer(11)->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('items');
        $this->dropTable('orders');
        $this->dropTable('order_items');
    }

}
