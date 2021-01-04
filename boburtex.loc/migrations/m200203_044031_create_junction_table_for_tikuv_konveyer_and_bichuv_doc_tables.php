<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_konveyer_bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_konveyer}}`
 * - `{{%bichuv_doc}}`
 */
class m200203_044031_create_junction_table_for_tikuv_konveyer_and_bichuv_doc_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_konveyer_bichuv_doc}}', [
            'indeks' => $this->integer(),
            'tikuv_konveyer_id' => $this->integer(),
            'bichuv_doc_id' => $this->integer(),
            'PRIMARY KEY(tikuv_konveyer_id, bichuv_doc_id)',
        ]);

        // creates index for column `tikuv_konveyer_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_doc-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}',
            'tikuv_konveyer_id'
        );

        // add foreign key for table `{{%tikuv_konveyer}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_doc-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}',
            'tikuv_konveyer_id',
            '{{%tikuv_konveyer}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_doc-bichuv_doc_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%bichuv_doc}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_doc-bichuv_doc_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_konveyer}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_doc-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}'
        );

        // drops index for column `tikuv_konveyer_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_doc-tikuv_konveyer_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}'
        );

        // drops foreign key for table `{{%bichuv_doc}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_doc-bichuv_doc_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_doc-bichuv_doc_id}}',
            '{{%tikuv_konveyer_bichuv_doc}}'
        );

        $this->dropTable('{{%tikuv_konveyer_bichuv_doc}}');
    }
}
