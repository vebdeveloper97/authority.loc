<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_makine_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_makine}}`
 */
class m200319_075636_create_toquv_makine_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_makine_info}}', [
            'id' => $this->primaryKey(),
            'toquv_makine_id' => $this->integer(),
            'toquv_instruction_rm_id' => $this->integer(),
            'musteri' => $this->string(70),
            'doc_number' => $this->string(30),
            'mato' => $this->string(150),
            'info' => $this->string(30),
            'order_quantity' => $this->decimal(20,3),
            'quantity' => $this->decimal(20,3),
            'difference' => $this->float(),
            'remain' => $this->float(),
            'roll' => $this->float(),
            'count' => $this->float(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_makine_id`
        $this->createIndex(
            '{{%idx-toquv_makine_info-toquv_makine_id}}',
            '{{%toquv_makine_info}}',
            'toquv_makine_id'
        );

        // add foreign key for table `{{%toquv_makine}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_info-toquv_makine_id}}',
            '{{%toquv_makine_info}}',
            'toquv_makine_id',
            '{{%toquv_makine}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_makine}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_info-toquv_makine_id}}',
            '{{%toquv_makine_info}}'
        );

        // drops index for column `toquv_makine_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_info-toquv_makine_id}}',
            '{{%toquv_makine_info}}'
        );

        $this->dropTable('{{%toquv_makine_info}}');
    }
}
