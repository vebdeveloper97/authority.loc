<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200825_105904_add_mobile_tables_id_column_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'mobile_tables_id', $this->integer());

        // creates index for column `mobile_tables_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-mobile_tables_id}}',
            '{{%mobile_process_production}}',
            'mobile_tables_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-mobile_tables_id}}',
            '{{%mobile_process_production}}',
            'mobile_tables_id',
            '{{%mobile_tables}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-mobile_tables_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `mobile_tables_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-mobile_tables_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'mobile_tables_id');
    }
}
