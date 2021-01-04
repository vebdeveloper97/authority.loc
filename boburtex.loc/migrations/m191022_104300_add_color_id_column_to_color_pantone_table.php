<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%color_pantone}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m191022_104300_add_color_id_column_to_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%color_pantone}}', 'color_id', $this->integer());

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-color_pantone-color_id}}',
            '{{%color_pantone}}',
            'color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-color_pantone-color_id}}',
            '{{%color_pantone}}',
            'color_id',
            '{{%color}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-color_pantone-color_id}}',
            '{{%color_pantone}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-color_pantone-color_id}}',
            '{{%color_pantone}}'
        );

        $this->dropColumn('{{%color_pantone}}', 'color_id');
    }
}
