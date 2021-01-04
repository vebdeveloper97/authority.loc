<?php

use yii\db\Migration;

/**
 * Class m191023_094838_alter_barcode_column_goods_table
 */
class m191023_094838_alter_barcode_column_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('barcode', '{{%goods}}', 'barcode', $unique = true );
        $this->createIndex('barcode1', '{{%goods}}', 'barcode1', $unique = true );
        $this->createIndex('barcode2', '{{%goods}}', 'barcode2', $unique = true );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('barcode', '{{%goods}}');
        $this->dropIndex('barcode1', '{{%goods}}');
        $this->dropIndex('barcode2', '{{%goods}}');
    }
}
