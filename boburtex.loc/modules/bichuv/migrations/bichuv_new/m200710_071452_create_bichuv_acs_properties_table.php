<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_acs_properties}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_acs}}`
 * - `{{%bichuv_acs_property_list}}`
 */
class m200710_071452_create_bichuv_acs_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_acs_properties}}', [
            'id' => $this->primaryKey(),
            'bichuv_acs_id' => $this->integer(),
            'bichuv_acs_property_list_id' => $this->integer(),
            'value' => $this->char(255),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `bichuv_acs_id`
        $this->createIndex(
            '{{%idx-bichuv_acs_properties-bichuv_acs_id}}',
            '{{%bichuv_acs_properties}}',
            'bichuv_acs_id'
        );

        // add foreign key for table `{{%bichuv_acs}}`
        $this->addForeignKey(
            '{{%fk-bichuv_acs_properties-bichuv_acs_id}}',
            '{{%bichuv_acs_properties}}',
            'bichuv_acs_id',
            '{{%bichuv_acs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_acs_property_list_id`
        $this->createIndex(
            '{{%idx-bichuv_acs_properties-bichuv_acs_property_list_id}}',
            '{{%bichuv_acs_properties}}',
            'bichuv_acs_property_list_id'
        );

        // add foreign key for table `{{%bichuv_acs_property_list}}`
        $this->addForeignKey(
            '{{%fk-bichuv_acs_properties-bichuv_acs_property_list_id}}',
            '{{%bichuv_acs_properties}}',
            'bichuv_acs_property_list_id',
            '{{%bichuv_acs_property_list}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_acs}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_acs_properties-bichuv_acs_id}}',
            '{{%bichuv_acs_properties}}'
        );

        // drops index for column `bichuv_acs_id`
        $this->dropIndex(
            '{{%idx-bichuv_acs_properties-bichuv_acs_id}}',
            '{{%bichuv_acs_properties}}'
        );

        // drops foreign key for table `{{%bichuv_acs_property_list}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_acs_properties-bichuv_acs_property_list_id}}',
            '{{%bichuv_acs_properties}}'
        );

        // drops index for column `bichuv_acs_property_list_id`
        $this->dropIndex(
            '{{%idx-bichuv_acs_properties-bichuv_acs_property_list_id}}',
            '{{%bichuv_acs_properties}}'
        );

        $this->dropTable('{{%bichuv_acs_properties}}');
    }
}
