<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%size_col_rel_size}}`.
 */
class m191021_054008_create_size_col_rel_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%size_col_rel_size}}', [
            'id'    => $this->primaryKey(),
            'sc_id' => $this->integer(),
            'size_id' => $this->integer(),
            'type' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `sc_id`
        $this->createIndex(
            '{{%idx-size_col_rel_size-sc_id}}',
            '{{%size_col_rel_size}}',
            'sc_id'
        );

        // add foreign key for table `{{%size_collections}}`
        $this->addForeignKey(
            '{{%fk-size_col_rel_size-sc_id}}',
            '{{%size_col_rel_size}}',
            'sc_id',
            '{{%size_collections}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-size_col_rel_size-size_id}}',
            '{{%size_col_rel_size}}',
            'size_id'
        );

        // add foreign key for table `{{%size_collections}}`
        $this->addForeignKey(
            '{{%fk-size_col_rel_size-size_id}}',
            '{{%size_col_rel_size}}',
            'size_id',
            '{{%size}}',
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
        // drops foreign key for table `{{%size_col_rel_size}}`
        $this->dropForeignKey(
            '{{%fk-size_col_rel_size-sc_id}}',
            '{{%size_col_rel_size}}'
        );

        // drops index for column `sc_id`
        $this->dropIndex(
            '{{%idx-size_col_rel_size-sc_id}}',
            '{{%size_col_rel_size}}'
        );

        // drops foreign key for table `{{%size_col_rel_size}}`
        $this->dropForeignKey(
            '{{%fk-size_col_rel_size-size_id}}',
            '{{%size_col_rel_size}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-size_col_rel_size-size_id}}',
            '{{%size_col_rel_size}}'
        );

        $this->dropTable('{{%size_col_rel_size}}');
    }
}
