<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%foreign_key_mato_id_from_model_orders_items_material}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_row_materials}}`
 */
class m200718_172407_drop_foreign_key_mato_id_from_model_orders_items_material_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%toquv_row_materials}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // add foreign key for table `{{%toquv_row_materials}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}',
            'mato_id',
            '{{%toquv_raw_materials}}',
            'id'
        );
    }
}
