<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_norm_standart}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_standart}}`
 * - `{{%sort_name}}`
 * - `{{%mobile_process}}`
 */
class m200925_120926_create_base_norm_standart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_norm_standart}}', [
            'id' => $this->primaryKey(),
            'base_standart_id' => $this->integer(),
            'sort_id' => $this->integer(),
            'mobile_process_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `base_standart_id`
        $this->createIndex(
            '{{%idx-base_norm_standart-base_standart_id}}',
            '{{%base_norm_standart}}',
            'base_standart_id'
        );

        // add foreign key for table `{{%base_standart}}`
        $this->addForeignKey(
            '{{%fk-base_norm_standart-base_standart_id}}',
            '{{%base_norm_standart}}',
            'base_standart_id',
            '{{%base_standart}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `sort_id`
        $this->createIndex(
            '{{%idx-base_norm_standart-sort_id}}',
            '{{%base_norm_standart}}',
            'sort_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-base_norm_standart-sort_id}}',
            '{{%base_norm_standart}}',
            'sort_id',
            '{{%sort_name}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-base_norm_standart-mobile_process_id}}',
            '{{%base_norm_standart}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-base_norm_standart-mobile_process_id}}',
            '{{%base_norm_standart}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_standart}}`
        $this->dropForeignKey(
            '{{%fk-base_norm_standart-base_standart_id}}',
            '{{%base_norm_standart}}'
        );

        // drops index for column `base_standart_id`
        $this->dropIndex(
            '{{%idx-base_norm_standart-base_standart_id}}',
            '{{%base_norm_standart}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-base_norm_standart-sort_id}}',
            '{{%base_norm_standart}}'
        );

        // drops index for column `sort_id`
        $this->dropIndex(
            '{{%idx-base_norm_standart-sort_id}}',
            '{{%base_norm_standart}}'
        );

        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-base_norm_standart-mobile_process_id}}',
            '{{%base_norm_standart}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-base_norm_standart-mobile_process_id}}',
            '{{%base_norm_standart}}'
        );

        $this->dropTable('{{%base_norm_standart}}');
    }
}
