<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipe_items}}`.
 */
class m200514_111357_create_recipe_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_items}}', [
            'id' => $this->bigPrimaryKey(),
            'recipe_id' => $this->bigInteger(),
            'recipe_item_group_id' => $this->bigInteger(),
            'item_id' => $this->integer(),
            'item_sku' => $this->string(20),
            'item_weight' => $this->float(),
            'item-formula' => $this->string(100),
            'uchot_id' => $this->integer()->comment("qaysi prixoddan ishlatilyapdi"),
            'amount_calculated' => $this->float()->comment("formula orqali hisoblangan"),
            'amount_fact' => $this->float()->comment("master qazonga qarab ozgartirgan bolsa"),
            'unit_id' => $this->integer(),
            'add_info' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%recipe_items}}');
    }
}
