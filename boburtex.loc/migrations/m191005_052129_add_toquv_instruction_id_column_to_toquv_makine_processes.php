<?php

use yii\db\Migration;

/**
 * Class m191005_052129_add_toquv_instruction_id_column_to_toquv_makine_processes
 */
class m191005_052129_add_toquv_instruction_id_column_to_toquv_makine_processes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine_processes}}', 'ti_id', $this->integer()->after('id'));

        // creates index for column `ti_id`
        $this->createIndex(
            '{{%idx-toquv_makine_processes-ti_id}}',
            '{{%toquv_makine_processes}}',
            'ti_id'
        );

        // add foreign key for table `{{%ti_id}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_processes-ti_id}}',
            '{{%toquv_makine_processes}}',
            'ti_id',
            '{{%toquv_instructions}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ti_id}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_processes-ti_id}}',
            '{{%toquv_makine_processes}}'
        );

        // drops index for column `ti_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_processes-ti_id}}',
            '{{%toquv_makine_processes}}'
        );

        $this->dropColumn('{{%toquv_makine_processes}}', 'ti_id');
    }

}
