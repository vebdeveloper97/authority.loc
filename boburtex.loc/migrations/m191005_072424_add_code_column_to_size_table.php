<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%size}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%size_type}}`
 */
class m191005_072424_add_code_column_to_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%size}}', 'code', $this->string(20));

        // creates index for column `code`
        $this->createIndex(
            '{{%idx-size-code}}',
            '{{%size}}',
            'code'
        );
        // creates index for column `size_type_id`
        $this->createIndex(
            '{{%idx-size-size_type_id}}',
            '{{%size}}',
            'size_type_id'
        );

        // add foreign key for table `{{%size_type}}`
        $this->addForeignKey(
            '{{%fk-size-size_type_id}}',
            '{{%size}}',
            'size_type_id',
            '{{%size_type}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size_type}}`
        $this->dropForeignKey(
            '{{%fk-size-size_type_id}}',
            '{{%size}}'
        );

        // drops index for column `size_type_id`
        $this->dropIndex(
            '{{%idx-size-size_type_id}}',
            '{{%size}}'
        );
        // drops index for column `size_type_id`
        $this->dropIndex(
            '{{%idx-size-code}}',
            '{{%size}}'
        );

        $this->dropColumn('{{%size}}', 'code');
    }
}
