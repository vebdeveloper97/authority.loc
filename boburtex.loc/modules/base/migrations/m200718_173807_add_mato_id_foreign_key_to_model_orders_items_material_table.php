<?php

use yii\db\Migration;

/**
 * Class m200718_173807_add_mato_id_foreign_key_to_model_orders_items_material_table
 */
class m200718_173807_add_mato_id_foreign_key_to_model_orders_items_material_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}',
            'mato_id',
            '{{%wms_mato_info}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200718_173807_add_mato_id_foreign_key_to_model_orders_items_material_table cannot be reverted.\n";

        return false;
    }
    */
}
