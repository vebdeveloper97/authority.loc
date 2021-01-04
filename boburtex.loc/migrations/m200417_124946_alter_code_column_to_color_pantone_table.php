<?php

use yii\db\Migration;

/**
 * Class m200417_124946_alter_code_column_to_color_pantone_table
 */
class m200417_124946_alter_code_column_to_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "UPDATE `color_pantone` SET `code` = REPLACE(code, ' TSX', '') WHERE color_panton_type_id = 1";
        $this->execute($sql);
        $sql = "UPDATE `color_pantone` SET `code` = REPLACE(code, ' TPG', '') WHERE color_panton_type_id = 2";
        $this->execute($sql);
        $this->addColumn('base_pattern_rel_attachment', 'type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200413_093406_alter_column_to_color_pantone_table cannot be reverted.\n";
        $this->dropColumn('base_pattern_rel_attachment','type');
        return true;
    }
}
