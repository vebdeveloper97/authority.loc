<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%templates}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%boyama_turi}}`
 * - `{{%created_by}}`
 */
class m200528_083558_add_some_column_to_templates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->addPrimaryKey('id', '{{%templates}}', 'id');
        $this->alterColumn('{{%templates}}', 'id', $this->primaryKey());
        $this->addColumn('{{%templates}}', 'boyama_turi_id', $this->integer());
        $this->addColumn('{{%templates}}', 'flotte', $this->decimal(10,3)->defaultValue(6));
        $this->addColumn('{{%templates}}', 'created_by', $this->bigInteger());
        $this->addColumn('{{%templates}}', 'created_at', $this->integer());
        $this->addColumn('{{%templates}}', 'status', $this->tinyInteger());

        // creates index for column `boyama_turi_id`
        $this->createIndex(
            '{{%idx-templates-boyama_turi_id}}',
            '{{%templates}}',
            'boyama_turi_id'
        );

        // add foreign key for table `{{%boyama_turi}}`
        $this->addForeignKey(
            '{{%fk-templates-boyama_turi_id}}',
            '{{%templates}}',
            'boyama_turi_id',
            '{{%boyama_turi}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-templates-created_by}}',
            '{{%templates}}',
            'created_by'
        );

        // add foreign key for table `{{%created_by}}`
        $this->addForeignKey(
            '{{%fk-templates-created_by}}',
            '{{%templates}}',
            'created_by',
            '{{%users}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%boyama_turi}}`
        $this->dropForeignKey(
            '{{%fk-templates-boyama_turi_id}}',
            '{{%templates}}'
        );

        // drops index for column `boyama_turi_id`
        $this->dropIndex(
            '{{%idx-templates-boyama_turi_id}}',
            '{{%templates}}'
        );

        // drops foreign key for table `{{%created_by}}`
        $this->dropForeignKey(
            '{{%fk-templates-created_by}}',
            '{{%templates}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-templates-created_by}}',
            '{{%templates}}'
        );

        $this->dropColumn('{{%templates}}', 'boyama_turi_id');
        $this->dropColumn('{{%templates}}', 'flotte');
        $this->dropColumn('{{%templates}}', 'created_by');
        $this->dropColumn('{{%templates}}', 'created_at');
        $this->dropColumn('{{%templates}}', 'status');
        $this->alterColumn('{{%templates}}', 'id', $this->integer());
        $this->dropPrimaryKey("id", "{{%templates}}");
    }
}
