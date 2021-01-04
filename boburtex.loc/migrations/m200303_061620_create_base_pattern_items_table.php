<?php

use yii\db\Migration;

/**
 * Handles the creation of table base_pattern_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_detail_type}}`
 * - `{{%base_detail_list}}`
 * - `{{%base_patterns}}`
 */
class m200303_061620_create_base_pattern_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_pattern_items}}', [
            'id' => $this->primaryKey(),
            'bichuv_detail_type_id' => $this->integer(),
            'base_detail_list_id' => $this->integer(),
            'base_pattern_id' => $this->integer(),
            'pattern_item_type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_detail_type_id`
        $this->createIndex(
            '{{%idx-base_pattern_items-bichuv_detail_type_id}}',
            '{{%base_pattern_items}}',
            'bichuv_detail_type_id'
        );

        // add foreign key for table `{{%bichuv_detail_type}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_items-bichuv_detail_type_id}}',
            '{{%base_pattern_items}}',
            'bichuv_detail_type_id',
            '{{%bichuv_detail_types}}',
            'id'
        );

        // creates index for column `base_detail_list_id`
        $this->createIndex(
            '{{%idx-base_pattern_items-base_detail_list_id}}',
            '{{%base_pattern_items}}',
            'base_detail_list_id'
        );

        // add foreign key for table `{{%bichuv_detail_list}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_items-base_detail_list_id}}',
            '{{%base_pattern_items}}',
            'base_detail_list_id',
            '{{%base_detail_lists}}',
            'id'
        );

        // creates index for column `base_pattern_id`
        $this->createIndex(
            '{{%idx-base_pattern_items-base_pattern_id}}',
            '{{%base_pattern_items}}',
            'base_pattern_id'
        );

        // add foreign key for table `{{%base_patterns}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_items-base_pattern_id}}',
            '{{%base_pattern_items}}',
            'base_pattern_id',
            '{{%base_patterns}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_detail_types}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_items-bichuv_detail_type_id}}',
            '{{%base_pattern_items}}'
        );

        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-base_pattern_items-bichuv_detail_type_id}}',
            '{{%base_pattern_items}}'
        );

        // drops foreign key for table `{{%base_detail_lists}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_items-base_detail_list_id}}',
            '{{%base_pattern_items}}'
        );

        // drops index for column `base_detail_list_id`
        $this->dropIndex(
            '{{%idx-base_pattern_items-base_detail_list_id}}',
            '{{%base_pattern_items}}'
        );

        // drops foreign key for table `{{%base_patterns}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_items-base_pattern_id}}',
            '{{%base_pattern_items}}'
        );

        // drops index for column `base_pattern_id`
        $this->dropIndex(
            '{{%idx-base_pattern_items-base_pattern_id}}',
            '{{%base_pattern_items}}'
        );

        $this->dropTable('{{%base_pattern_items}}');
    }
}
