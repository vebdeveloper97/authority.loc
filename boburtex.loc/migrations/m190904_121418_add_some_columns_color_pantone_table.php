<?php

use yii\db\Migration;

/**
 * Class m190904_121418_add_some_columns_color_pantone_table
 */
class m190904_121418_add_some_columns_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('color_pantone', 'color_tone_id');
        $this->dropColumn('color_pantone', 'pantone_code');
        $this->dropColumn('color_pantone', 'color_hex');

        $this->addColumn('color_pantone', 'name', $this->string(50));
        $this->addColumn('color_pantone', 'code', $this->string(25));
        $this->addColumn('color_pantone', 'r', $this->smallInteger());
        $this->addColumn('color_pantone', 'g', $this->smallInteger());
        $this->addColumn('color_pantone', 'b', $this->smallInteger());
        $this->addColumn('color_pantone', 'color_panton_type_id', $this->integer());

        //color_panton_type_id
        $this->createIndex(
            'idx-color_pantone-color_panton_type_id',
            'color_pantone',
            'color_panton_type_id'
        );

        $this->addForeignKey(
            'fk-color_pantone-color_panton_type_id',
            'color_pantone',
            'color_panton_type_id',
            'color_panton_type',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //color_panton_type_id
        $this->dropForeignKey(
            'fk-color_pantone-color_panton_type_id',
            'color_pantone'
        );
        $this->dropColumn('color_pantone', 'color_panton_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_121418_add_some_columns_color_pantone_table cannot be reverted.\n";

        return false;
    }
    */
}
