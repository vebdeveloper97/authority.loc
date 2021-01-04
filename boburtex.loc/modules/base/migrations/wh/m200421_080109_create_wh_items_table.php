<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wh_item_category}}`
 * - `{{%wh_item_types}}`
 * - `{{%unit}}`
 * - `{{%wh_item_country}}`
 */
class m200421_080109_create_wh_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_items}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50),
            'name' => $this->string(),
            'category_id' => $this->integer(),
            'type_id' => $this->integer(),
            'unit_id' => $this->integer(),
            'barcode' => $this->string(50),
            'country_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-wh_items-category_id}}',
            '{{%wh_items}}',
            'category_id'
        );

        // add foreign key for table `{{%wh_item_category}}`
        $this->addForeignKey(
            '{{%fk-wh_items-category_id}}',
            '{{%wh_items}}',
            'category_id',
            '{{%wh_item_category}}',
            'id'
        );

        // creates index for column `type_id`
        $this->createIndex(
            '{{%idx-wh_items-type_id}}',
            '{{%wh_items}}',
            'type_id'
        );

        // add foreign key for table `{{%wh_item_types}}`
        $this->addForeignKey(
            '{{%fk-wh_items-type_id}}',
            '{{%wh_items}}',
            'type_id',
            '{{%wh_item_types}}',
            'id'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-wh_items-unit_id}}',
            '{{%wh_items}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-wh_items-unit_id}}',
            '{{%wh_items}}',
            'unit_id',
            '{{%unit}}',
            'id'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-wh_items-country_id}}',
            '{{%wh_items}}',
            'country_id'
        );

        // add foreign key for table `{{%wh_item_country}}`
        $this->addForeignKey(
            '{{%fk-wh_items-country_id}}',
            '{{%wh_items}}',
            'country_id',
            '{{%wh_item_country}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wh_item_category}}`
        $this->dropForeignKey(
            '{{%fk-wh_items-category_id}}',
            '{{%wh_items}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-wh_items-category_id}}',
            '{{%wh_items}}'
        );

        // drops foreign key for table `{{%wh_item_types}}`
        $this->dropForeignKey(
            '{{%fk-wh_items-type_id}}',
            '{{%wh_items}}'
        );

        // drops index for column `type_id`
        $this->dropIndex(
            '{{%idx-wh_items-type_id}}',
            '{{%wh_items}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-wh_items-unit_id}}',
            '{{%wh_items}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-wh_items-unit_id}}',
            '{{%wh_items}}'
        );

        // drops foreign key for table `{{%wh_item_country}}`
        $this->dropForeignKey(
            '{{%fk-wh_items-country_id}}',
            '{{%wh_items}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-wh_items-country_id}}',
            '{{%wh_items}}'
        );

        $this->dropTable('{{%wh_items}}');
    }
}
