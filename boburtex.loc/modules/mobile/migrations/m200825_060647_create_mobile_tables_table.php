<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_tables}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 */
class m200825_060647_create_mobile_tables_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_tables}}', [
            'id' => $this->primaryKey(),
            'mobile_process_id' => $this->integer(),
            'name' => $this->string(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-mobile_tables-mobile_process_id}}',
            '{{%mobile_tables}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-mobile_tables-mobile_process_id}}',
            '{{%mobile_tables}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-mobile_tables-mobile_process_id}}',
            '{{%mobile_tables}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-mobile_tables-mobile_process_id}}',
            '{{%mobile_tables}}'
        );

        $this->dropTable('{{%mobile_tables}}');
    }
}
