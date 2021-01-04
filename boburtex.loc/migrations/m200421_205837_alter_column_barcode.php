<?php

use yii\db\Migration;

/**
 * Class m200421_205837_alter_column_barcode
 */
class m200421_205837_alter_column_barcode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('goods', 'barcode', $this->bigInteger(20));
        $this->alterColumn('goods', 'barcode1', $this->bigInteger(20));
        $this->alterColumn('goods', 'barcode2', $this->bigInteger(20));
        $this->alterColumn('tikuv_goods_doc', 'barcode', $this->bigInteger(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }


}
