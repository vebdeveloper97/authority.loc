<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_method_size_items_childs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_method_size_items}}`
 * - `{{%base_method_seam}}`
 */
class m200923_054807_create_base_method_size_items_childs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_method_size_items_childs}}', [
            'id' => $this->primaryKey(),
            'base_method_size_items_id' => $this->integer(),
            'base_method_seam_id' => $this->integer(),
            'time' => $this->float(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `base_method_size_items_id`
        $this->createIndex(
            '{{%idx-base_method_size_items_childs-base_method_size_items_id}}',
            '{{%base_method_size_items_childs}}',
            'base_method_size_items_id'
        );

        // add foreign key for table `{{%base_method_size_items}}`
        $this->addForeignKey(
            '{{%fk-base_method_size_items_childs-base_method_size_items_id}}',
            '{{%base_method_size_items_childs}}',
            'base_method_size_items_id',
            '{{%base_method_size_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `base_method_seam_id`
        $this->createIndex(
            '{{%idx-base_method_size_items_childs-base_method_seam_id}}',
            '{{%base_method_size_items_childs}}',
            'base_method_seam_id'
        );

        // add foreign key for table `{{%base_method_seam}}`
        $this->addForeignKey(
            '{{%fk-base_method_size_items_childs-base_method_seam_id}}',
            '{{%base_method_size_items_childs}}',
            'base_method_seam_id',
            '{{%base_method_seam}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_method_size_items}}`
        $this->dropForeignKey(
            '{{%fk-base_method_size_items_childs-base_method_size_items_id}}',
            '{{%base_method_size_items_childs}}'
        );

        // drops index for column `base_method_size_items_id`
        $this->dropIndex(
            '{{%idx-base_method_size_items_childs-base_method_size_items_id}}',
            '{{%base_method_size_items_childs}}'
        );

        // drops foreign key for table `{{%base_method_seam}}`
        $this->dropForeignKey(
            '{{%fk-base_method_size_items_childs-base_method_seam_id}}',
            '{{%base_method_size_items_childs}}'
        );

        // drops index for column `base_method_seam_id`
        $this->dropIndex(
            '{{%idx-base_method_size_items_childs-base_method_seam_id}}',
            '{{%base_method_size_items_childs}}'
        );

        $this->dropTable('{{%base_method_size_items_childs}}');
    }
}
