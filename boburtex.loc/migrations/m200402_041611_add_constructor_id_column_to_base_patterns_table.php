<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_patterns}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%constructor}}`
 * - `{{%designer}}`
 */
class m200402_041611_add_constructor_id_column_to_base_patterns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%base_patterns}}', 'constructor_id', $this->bigInteger(20));
        $this->addColumn('{{%base_patterns}}', 'designer_id', $this->bigInteger(20));

        // creates index for column `constructor_id`
        $this->createIndex(
            '{{%idx-base_patterns-constructor_id}}',
            '{{%base_patterns}}',
            'constructor_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-constructor_id}}',
            '{{%base_patterns}}',
            'constructor_id',
            '{{%users}}',
            'id'
        );

        // creates index for column `designer_id`
        $this->createIndex(
            '{{%idx-base_patterns-designer_id}}',
            '{{%base_patterns}}',
            'designer_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-base_patterns-designer_id}}',
            '{{%base_patterns}}',
            'designer_id',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-constructor_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `constructor_id`
        $this->dropIndex(
            '{{%idx-base_patterns-constructor_id}}',
            '{{%base_patterns}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-base_patterns-designer_id}}',
            '{{%base_patterns}}'
        );

        // drops index for column `designer_id`
        $this->dropIndex(
            '{{%idx-base_patterns-designer_id}}',
            '{{%base_patterns}}'
        );

        $this->dropColumn('{{%base_patterns}}', 'constructor_id');
        $this->dropColumn('{{%base_patterns}}', 'designer_id');
    }
}
