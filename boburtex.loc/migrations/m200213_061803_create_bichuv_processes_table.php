<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_processes}}`.
 */
class m200213_061803_create_bichuv_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_processes}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addColumn('bichuv_nastel_processes','bichuv_process_id', $this->integer());

        // creates index for column `bichuv_process_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_process_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_process_id'
        );

        // add foreign key for table `{{%bichuv_nastel_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_process_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_process_id',
            '{{%bichuv_processes}}',
            'id'
        );
        $this->upsert('{{%toquv_rm_defects}}',['name'=>'Likra uzish'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_process_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_process_id}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `bichuv_process_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_process_id}}',
            '{{%bichuv_nastel_processes}}'
        );
        $this->dropColumn('bichuv_nastel_processes','bichuv_process_id');
        $this->dropTable('{{%bichuv_processes}}');
        $this->delete('{{%toquv_rm_defects}}',['name'=>'Likra uzish']);
    }
}
