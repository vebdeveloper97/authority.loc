<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_naqsh}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_detail_lists}}`
 */
class m200918_134959_add_base_detail_lists_id_column_to_models_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_naqsh}}', 'base_details_list_id', $this->integer());

        // creates index for column `base_details_list_id`
        $this->createIndex(
            '{{%idx-models_naqsh-base_details_list_id}}',
            '{{%models_naqsh}}',
            'base_details_list_id'
        );

        // add foreign key for table `{{%base_detail_lists}}`
        $this->addForeignKey(
            '{{%fk-models_naqsh-base_details_list_id}}',
            '{{%models_naqsh}}',
            'base_details_list_id',
            '{{%base_detail_lists}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_detail_lists}}`
        $this->dropForeignKey(
            '{{%fk-models_naqsh-base_details_list_id}}',
            '{{%models_naqsh}}'
        );

        // drops index for column `base_details_list_id`
        $this->dropIndex(
            '{{%idx-models_naqsh-base_details_list_id}}',
            '{{%models_naqsh}}'
        );

        $this->dropColumn('{{%models_naqsh}}', 'base_details_list_id');
    }
}
