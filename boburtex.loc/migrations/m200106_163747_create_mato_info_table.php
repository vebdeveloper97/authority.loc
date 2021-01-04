<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mato_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_raw_materials}}`
 * - `{{%toquv_pus_fine}}`
 * - `{{%toquv_rm_order}}`
 * - `{{%toquv_instruction_rm}}`
 */
class m200106_163747_create_mato_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mato_info}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(),
            'pus_fine_id' => $this->integer(),
            'thread_length' => $this->string(50),
            'finish_en' => $this->string(50),
            'finish_gramaj' => $this->string(50),
            'type_weaving' => $this->smallInteger(6)->defaultValue(1),
            'toquv_rm_order_id' => $this->integer(),
            'toquv_instruction_rm_id' => $this->integer(),
            'toquv_instruction_id' => $this->integer(),
            'musteri_id' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-mato_info-entity_id}}',
            '{{%mato_info}}',
            'entity_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-mato_info-entity_id}}',
            '{{%mato_info}}',
            'entity_id',
            '{{%toquv_raw_materials}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `pus_fine_id`
        $this->createIndex(
            '{{%idx-mato_info-pus_fine_id}}',
            '{{%mato_info}}',
            'pus_fine_id'
        );

        // add foreign key for table `{{%toquv_pus_fine}}`
        $this->addForeignKey(
            '{{%fk-mato_info-pus_fine_id}}',
            '{{%mato_info}}',
            'pus_fine_id',
            '{{%toquv_pus_fine}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `toquv_rm_order_id`
        $this->createIndex(
            '{{%idx-mato_info-toquv_rm_order_id}}',
            '{{%mato_info}}',
            'toquv_rm_order_id'
        );

        // add foreign key for table `{{%toquv_rm_order}}`
        $this->addForeignKey(
            '{{%fk-mato_info-toquv_rm_order_id}}',
            '{{%mato_info}}',
            'toquv_rm_order_id',
            '{{%toquv_rm_order}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `toquv_instruction_rm_id`
        $this->createIndex(
            '{{%idx-mato_info-toquv_instruction_rm_id}}',
            '{{%mato_info}}',
            'toquv_instruction_rm_id'
        );

        // add foreign key for table `{{%toquv_instruction_rm}}`
        $this->addForeignKey(
            '{{%fk-mato_info-toquv_instruction_rm_id}}',
            '{{%mato_info}}',
            'toquv_instruction_rm_id',
            '{{%toquv_instruction_rm}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-mato_info-entity_id}}',
            '{{%mato_info}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-mato_info-entity_id}}',
            '{{%mato_info}}'
        );

        // drops foreign key for table `{{%toquv_pus_fine}}`
        $this->dropForeignKey(
            '{{%fk-mato_info-pus_fine_id}}',
            '{{%mato_info}}'
        );

        // drops index for column `pus_fine_id`
        $this->dropIndex(
            '{{%idx-mato_info-pus_fine_id}}',
            '{{%mato_info}}'
        );

        // drops foreign key for table `{{%toquv_rm_order}}`
        $this->dropForeignKey(
            '{{%fk-mato_info-toquv_rm_order_id}}',
            '{{%mato_info}}'
        );

        // drops index for column `toquv_rm_order_id`
        $this->dropIndex(
            '{{%idx-mato_info-toquv_rm_order_id}}',
            '{{%mato_info}}'
        );

        // drops foreign key for table `{{%toquv_instruction_rm}}`
        $this->dropForeignKey(
            '{{%fk-mato_info-toquv_instruction_rm_id}}',
            '{{%mato_info}}'
        );

        // drops index for column `toquv_instruction_rm_id`
        $this->dropIndex(
            '{{%idx-mato_info-toquv_instruction_rm_id}}',
            '{{%mato_info}}'
        );

        $this->dropTable('{{%mato_info}}');
    }
}
