<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_item_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wh_item_types}}`
 */
class m200421_080101_create_wh_item_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_item_category}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50),
            'name' => $this->string(),
            'type_id' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `type_id`
        $this->createIndex(
            '{{%idx-wh_item_category-type_id}}',
            '{{%wh_item_category}}',
            'type_id'
        );

        // add foreign key for table `{{%wh_item_types}}`
        $this->addForeignKey(
            '{{%fk-wh_item_category-type_id}}',
            '{{%wh_item_category}}',
            'type_id',
            '{{%wh_item_types}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wh_item_types}}`
        $this->dropForeignKey(
            '{{%fk-wh_item_category-type_id}}',
            '{{%wh_item_category}}'
        );

        // drops index for column `type_id`
        $this->dropIndex(
            '{{%idx-wh_item_category-type_id}}',
            '{{%wh_item_category}}'
        );

        $this->dropTable('{{%wh_item_category}}');
    }
}
