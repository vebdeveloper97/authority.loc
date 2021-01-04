<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%roll_move_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_documents}}`
 * - `{{%roll_info}}`
 */
class m191218_115141_create_roll_move_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roll_move_info}}', [
            'id' => $this->primaryKey(),
            'toquv_documents_id' => $this->integer(),
            'roll_info_id' => $this->integer(),
            'entity_type' => $this->smallInteger(),
            'quantity' => $this->float(),
            'unit_id' => $this->integer()->defaultValue(2),
            'code' => $this->string(30),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'reg_date' => $this->timeStamp(),
        ]);

        // creates index for column `toquv_documents_id`
        $this->createIndex(
            '{{%idx-roll_move_info-toquv_documents_id}}',
            '{{%roll_move_info}}',
            'toquv_documents_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-roll_move_info-toquv_documents_id}}',
            '{{%roll_move_info}}',
            'toquv_documents_id',
            '{{%toquv_documents}}',
            'id',
            'CASCADE'
        );

        // creates index for column `roll_info_id`
        $this->createIndex(
            '{{%idx-roll_move_info-roll_info_id}}',
            '{{%roll_move_info}}',
            'roll_info_id'
        );

        // add foreign key for table `{{%roll_info}}`
        $this->addForeignKey(
            '{{%fk-roll_move_info-roll_info_id}}',
            '{{%roll_move_info}}',
            'roll_info_id',
            '{{%roll_info}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_documents}}`
        $this->dropForeignKey(
            '{{%fk-roll_move_info-toquv_documents_id}}',
            '{{%roll_move_info}}'
        );

        // drops index for column `toquv_documents_id`
        $this->dropIndex(
            '{{%idx-roll_move_info-toquv_documents_id}}',
            '{{%roll_move_info}}'
        );

        // drops foreign key for table `{{%roll_info}}`
        $this->dropForeignKey(
            '{{%fk-roll_move_info-roll_info_id}}',
            '{{%roll_move_info}}'
        );

        // drops index for column `roll_info_id`
        $this->dropIndex(
            '{{%idx-roll_move_info-roll_info_id}}',
            '{{%roll_move_info}}'
        );

        $this->dropTable('{{%roll_move_info}}');
    }
}
