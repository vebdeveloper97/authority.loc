<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%recipes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%boyama_turi}}`
 */
class m200528_081735_add_boyama_turi_id_column_to_recipes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%recipes}}', 'boyama_turi_id', $this->integer());

        // creates index for column `boyama_turi_id`
        $this->createIndex(
            '{{%idx-recipes-boyama_turi_id}}',
            '{{%recipes}}',
            'boyama_turi_id'
        );

        // add foreign key for table `{{%boyama_turi}}`
        $this->addForeignKey(
            '{{%fk-recipes-boyama_turi_id}}',
            '{{%recipes}}',
            'boyama_turi_id',
            '{{%boyama_turi}}',
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
            '{{%fk-recipes-boyama_turi_id}}',
            '{{%recipes}}'
        );

        // drops index for column `boyama_turi_id`
        $this->dropIndex(
            '{{%idx-recipes-boyama_turi_id}}',
            '{{%recipes}}'
        );

        $this->dropColumn('{{%recipes}}', 'boyama_turi_id');
    }
}
