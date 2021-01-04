<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_comment}}`.
 */
class m200719_201433_create_model_orders_comment_table extends Migration
{
    const TABLE_NAME = '{{%model_orders_comment}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        $this->addColumn(self::TABLE_NAME,'root', $this->integer());
        $this->addColumn(self::TABLE_NAME,'lft', $this->integer()/*->notNull()*/);
        $this->addColumn(self::TABLE_NAME,'rgt', $this->integer()/*->notNull()*/);
        $this->addColumn(self::TABLE_NAME,'lvl', $this->smallInteger(5)/*->notNull()*/);
        $this->addColumn(self::TABLE_NAME,'icon', $this->string(255));
        $this->addColumn(self::TABLE_NAME,'icon_type', $this->smallInteger(1)/*->notNull()*/->defaultValue(1));
        $this->addColumn(self::TABLE_NAME,'active', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'selected', $this->boolean()/*->notNull()*/->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'disabled', $this->boolean()/*->notNull()*/->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'readonly', $this->boolean()/*->notNull()*/->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'visible', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'collapsed', $this->boolean()/*->notNull()*/->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'movable_u', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_d', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_l', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_r', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable', $this->boolean()/*->notNull()*/->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable_all', $this->boolean()/*->notNull()*/->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'child_allowed', $this->boolean()/*->notNull()*/->defaultValue(true));

        $this->createIndex('tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('tree_NK5', self::TABLE_NAME, 'active');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('tree_NK1', self::TABLE_NAME);
        $this->dropIndex('tree_NK2', self::TABLE_NAME);
        $this->dropIndex('tree_NK3', self::TABLE_NAME);
        $this->dropIndex('tree_NK4', self::TABLE_NAME);
        $this->dropIndex('tree_NK5', self::TABLE_NAME);

        $this->dropColumn(self::TABLE_NAME, 'child_allowed');
        $this->dropColumn(self::TABLE_NAME,'root');
        $this->dropColumn(self::TABLE_NAME,'lft');
        $this->dropColumn(self::TABLE_NAME,'rgt');
        $this->dropColumn(self::TABLE_NAME,'lvl');
        $this->dropColumn(self::TABLE_NAME,'icon');
        $this->dropColumn(self::TABLE_NAME,'icon_type');
        $this->dropColumn(self::TABLE_NAME,'active');
        $this->dropColumn(self::TABLE_NAME,'selected');
        $this->dropColumn(self::TABLE_NAME,'disabled');
        $this->dropColumn(self::TABLE_NAME,'readonly');
        $this->dropColumn(self::TABLE_NAME,'visible');
        $this->dropColumn(self::TABLE_NAME,'collapsed');
        $this->dropColumn(self::TABLE_NAME,'movable_u');
        $this->dropColumn(self::TABLE_NAME,'movable_d');
        $this->dropColumn(self::TABLE_NAME,'movable_l');
        $this->dropColumn(self::TABLE_NAME,'movable_r');
        $this->dropColumn(self::TABLE_NAME,'removable');
        $this->dropColumn(self::TABLE_NAME,'removable_all');

        $this->dropTable(self::TABLE_NAME);
    }
}
