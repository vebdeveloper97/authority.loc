<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_combine_card_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%size}}`
 */
class m200928_175756_create_tikuv_combine_card_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_combine_card_info}}', [
            'id' => $this->primaryKey(),
            'size_id' => $this->integer(),
            'nastel_no' => $this->string(50),
            'parent' => $this->integer(),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-tikuv_combine_card_info-nastel_no}}',
            '{{%tikuv_combine_card_info}}',
            'nastel_no'
        );
        // creates index for column `parent`
        $this->createIndex(
            '{{%idx-tikuv_combine_card_info-parent}}',
            '{{%tikuv_combine_card_info}}',
            'parent'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-tikuv_combine_card_info-size_id}}',
            '{{%tikuv_combine_card_info}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-tikuv_combine_card_info-size_id}}',
            '{{%tikuv_combine_card_info}}',
            'size_id',
            '{{%size}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_combine_card_info-size_id}}',
            '{{%tikuv_combine_card_info}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-tikuv_combine_card_info-size_id}}',
            '{{%tikuv_combine_card_info}}'
        );

        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-tikuv_combine_card_info-nastel_no}}',
            '{{%tikuv_combine_card_info}}'
        );

        // drops index for column `parent`
        $this->dropIndex(
            '{{%idx-tikuv_combine_card_info-parent}}',
            '{{%tikuv_combine_card_info}}'
        );

        $this->dropTable('{{%tikuv_combine_card_info}}');
    }
}
