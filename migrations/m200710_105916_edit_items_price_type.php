<?php

use yii\db\Migration;

/**
 * Class m200710_105916_edit_items_price_type
 */
class m200710_105916_edit_items_price_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('items','price','TEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('items','price','INT');

    }

}
