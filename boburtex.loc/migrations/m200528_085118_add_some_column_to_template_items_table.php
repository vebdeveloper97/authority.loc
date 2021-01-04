<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%template_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%template_formula}}`
 * - `{{%item}}`
 */
class m200528_085118_add_some_column_to_template_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%template_items}}', 'item_id');
        $this->dropColumn('{{%template_items}}', 'koeff');
        $this->dropColumn('{{%template_items}}', 'amount');
        $this->addColumn('{{%template_items}}', 'template_formula_id', $this->integer());
        $this->addColumn('{{%template_items}}', 'amount', $this->decimal(20,3));
        $this->addColumn('{{%template_items}}', 'item_id', $this->integer());

        // creates index for column `template_formula_id`
        $this->createIndex(
            '{{%idx-template_items-template_formula_id}}',
            '{{%template_items}}',
            'template_formula_id'
        );

        // add foreign key for table `{{%template_formula}}`
        $this->addForeignKey(
            '{{%fk-template_items-template_formula_id}}',
            '{{%template_items}}',
            'template_formula_id',
            '{{%template_formula}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `item_id`
        $this->createIndex(
            '{{%idx-template_items-item_id}}',
            '{{%template_items}}',
            'item_id'
        );

        // add foreign key for table `{{%item}}`
        $this->addForeignKey(
            '{{%fk-template_items-item_id}}',
            '{{%template_items}}',
            'item_id',
            '{{%wh_items}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%template_formula}}`
        $this->dropForeignKey(
            '{{%fk-template_items-template_formula_id}}',
            '{{%template_items}}'
        );

        // drops index for column `template_formula_id`
        $this->dropIndex(
            '{{%idx-template_items-template_formula_id}}',
            '{{%template_items}}'
        );

        // drops foreign key for table `{{%item}}`
        $this->dropForeignKey(
            '{{%fk-template_items-item_id}}',
            '{{%template_items}}'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            '{{%idx-template_items-item_id}}',
            '{{%template_items}}'
        );

        $this->dropColumn('{{%template_items}}', 'template_formula_id');
        $this->dropColumn('{{%template_items}}', 'amount');
        $this->dropColumn('{{%template_items}}', 'item_id');
        $this->addColumn('{{%template_items}}', 'amount', $this->decimal(20,3));
        $this->addColumn('{{%template_items}}', 'item_id', $this->integer());
        $this->addColumn('{{%template_items}}', 'koeff', $this->float());
    }
}
