<?php

use yii\db\Migration;

/**
 * Class m200521_073049_alter_barcode_column_to_string
 */
class m200521_073049_alter_barcode_column_to_string extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tikuv_goods_doc','barcode', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tikuv_goods_doc','barcode', $this->integer());
    }
}
