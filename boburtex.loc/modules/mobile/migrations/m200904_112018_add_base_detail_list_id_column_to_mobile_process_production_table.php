<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_pattern_items}}`
 */
class m200904_112018_add_base_detail_list_id_column_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'base_detail_list_id', $this->integer());

        // creates index for column `base_detail_list_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-base_detail_list_id}}',
            '{{%mobile_process_production}}',
            'base_detail_list_id'
        );

        // add foreign key for table `{{%base_pattern_items}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-base_detail_list_id}}',
            '{{%mobile_process_production}}',
            'base_detail_list_id',
            '{{%base_pattern_items}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_pattern_items}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-base_detail_list_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `base_detail_list_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-base_detail_list_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'base_detail_list_id');
    }
}

