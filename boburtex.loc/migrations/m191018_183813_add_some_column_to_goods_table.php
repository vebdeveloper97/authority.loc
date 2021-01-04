<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m191018_183813_add_some_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%goods}}', 'barcode2', $this->integer(13)->after('barcode'));
        $this->addColumn('{{%goods}}', 'barcode1', $this->integer(13)->after('barcode'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%goods}}', 'barcode2');
        $this->dropColumn('{{%goods}}', 'barcode1');
    }
}
