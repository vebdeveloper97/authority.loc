<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_toquv_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_mato_info}}`
 */
class m200905_091921_add_wms_mato_info_id_column_to_model_orders_items_toquv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_toquv_acs}}', 'wms_mato_info_id', $this->integer());

        // creates index for column `wms_mato_info_id`
        $this->createIndex(
            '{{%idx-model_orders_items_toquv_acs-wms_mato_info_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'wms_mato_info_id'
        );

        // add foreign key for table `{{%wms_mato_info}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_toquv_acs-wms_mato_info_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'wms_mato_info_id',
            '{{%wms_mato_info}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_mato_info}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_toquv_acs-wms_mato_info_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        // drops index for column `wms_mato_info_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_toquv_acs-wms_mato_info_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        $this->dropColumn('{{%model_orders_items_toquv_acs}}', 'wms_mato_info_id');
    }
}
