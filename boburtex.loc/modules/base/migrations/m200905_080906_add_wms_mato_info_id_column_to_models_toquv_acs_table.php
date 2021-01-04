<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_toquv_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_mato_info}}`
 */
class m200905_080906_add_wms_mato_info_id_column_to_models_toquv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_toquv_acs}}', 'wms_mato_info_id', $this->integer());

        // creates index for column `wms_mato_info_id`
        $this->createIndex(
            '{{%idx-models_toquv_acs-wms_mato_info_id}}',
            '{{%models_toquv_acs}}',
            'wms_mato_info_id'
        );

        // add foreign key for table `{{%wms_mato_info}}`
        $this->addForeignKey(
            '{{%fk-models_toquv_acs-wms_mato_info_id}}',
            '{{%models_toquv_acs}}',
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
            '{{%fk-models_toquv_acs-wms_mato_info_id}}',
            '{{%models_toquv_acs}}'
        );

        // drops index for column `wms_mato_info_id`
        $this->dropIndex(
            '{{%idx-models_toquv_acs-wms_mato_info_id}}',
            '{{%models_toquv_acs}}'
        );

        $this->dropColumn('{{%models_toquv_acs}}', 'wms_mato_info_id');
    }
}
