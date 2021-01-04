<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%recipe_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%template_formula}}`
 * - `{{%item}}`
 */
class m200528_090500_add_some_column_to_recipe_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%recipe_items}}', 'item_id');
        $this->addColumn('{{%recipe_items}}', 'template_formula_id', $this->integer());
        $this->addColumn('{{%recipe_items}}', 'amount', $this->decimal(20,3));
        $this->addColumn('{{%recipe_items}}', 'item_id', $this->integer());

        // creates index for column `template_formula_id`
        $this->createIndex(
            '{{%idx-recipe_items-template_formula_id}}',
            '{{%recipe_items}}',
            'template_formula_id'
        );

        // add foreign key for table `{{%template_formula}}`
        $this->addForeignKey(
            '{{%fk-recipe_items-template_formula_id}}',
            '{{%recipe_items}}',
            'template_formula_id',
            '{{%template_formula}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `item_id`
        $this->createIndex(
            '{{%idx-recipe_items-item_id}}',
            '{{%recipe_items}}',
            'item_id'
        );

        // add foreign key for table `{{%item}}`
        $this->addForeignKey(
            '{{%fk-recipe_items-item_id}}',
            '{{%recipe_items}}',
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
            '{{%fk-recipe_items-template_formula_id}}',
            '{{%recipe_items}}'
        );

        // drops index for column `template_formula_id`
        $this->dropIndex(
            '{{%idx-recipe_items-template_formula_id}}',
            '{{%recipe_items}}'
        );

        // drops foreign key for table `{{%item}}`
        $this->dropForeignKey(
            '{{%fk-recipe_items-item_id}}',
            '{{%recipe_items}}'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            '{{%idx-recipe_items-item_id}}',
            '{{%recipe_items}}'
        );

        $this->dropColumn('{{%recipe_items}}', 'template_formula_id');
        $this->dropColumn('{{%recipe_items}}', 'amount');
        $this->dropColumn('{{%recipe_items}}', 'item_id');
        $this->addColumn('{{%recipe_items}}', 'item_id', $this->integer());
    }
}
