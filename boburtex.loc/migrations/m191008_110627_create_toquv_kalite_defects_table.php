<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_kalite_defects}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_kalite}}`
 * - `{{%toquv_rm_defects}}`
 */
class m191008_110627_create_toquv_kalite_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_kalite_defects}}', [
            'id' => $this->primaryKey(),
            'toquv_kalite_id' => $this->integer(),
            'toquv_rm_defects_id' => $this->integer(),
            'quantity' => $this->decimal(20,2),
        ]);

        // creates index for column `toquv_kalite_id`
        $this->createIndex(
            '{{%idx-toquv_kalite_defects-toquv_kalite_id}}',
            '{{%toquv_kalite_defects}}',
            'toquv_kalite_id'
        );

        // add foreign key for table `{{%toquv_kalite}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite_defects-toquv_kalite_id}}',
            '{{%toquv_kalite_defects}}',
            'toquv_kalite_id',
            '{{%toquv_kalite}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `toquv_rm_defects_id`
        $this->createIndex(
            '{{%idx-toquv_kalite_defects-toquv_rm_defects_id}}',
            '{{%toquv_kalite_defects}}',
            'toquv_rm_defects_id'
        );

        // add foreign key for table `{{%toquv_rm_defects}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite_defects-toquv_rm_defects_id}}',
            '{{%toquv_kalite_defects}}',
            'toquv_rm_defects_id',
            '{{%toquv_rm_defects}}',
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
        // drops foreign key for table `{{%toquv_kalite}}`
        $this->dropForeignKey(
            '{{%fk-toquv_kalite_defects-toquv_kalite_id}}',
            '{{%toquv_kalite_defects}}'
        );

        // drops index for column `toquv_kalite_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite_defects-toquv_kalite_id}}',
            '{{%toquv_kalite_defects}}'
        );

        // drops foreign key for table `{{%toquv_rm_defects}}`
        $this->dropForeignKey(
            '{{%fk-toquv_kalite_defects-toquv_rm_defects_id}}',
            '{{%toquv_kalite_defects}}'
        );

        // drops index for column `toquv_rm_defects_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite_defects-toquv_rm_defects_id}}',
            '{{%toquv_kalite_defects}}'
        );

        $this->dropTable('{{%toquv_kalite_defects}}');
    }
}
