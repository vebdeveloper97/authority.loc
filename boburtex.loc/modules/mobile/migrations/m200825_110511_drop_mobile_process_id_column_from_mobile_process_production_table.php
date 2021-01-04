<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 */
class m200825_110511_drop_mobile_process_id_column_from_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'mobile_process_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%mobile_process_production}}', 'mobile_process_id', $this->integer());

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );
    }
}
