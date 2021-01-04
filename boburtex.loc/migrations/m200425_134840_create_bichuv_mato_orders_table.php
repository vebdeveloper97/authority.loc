<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_mato_orders}}`.
 */
class m200425_134840_create_bichuv_mato_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_mato_orders}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(25),
            'reg_date' => $this->dateTime(),
            'musteri_id' => $this->integer(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'bichuv_doc_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_mato_orders}}');
    }
}
