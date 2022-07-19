<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_gii_modules}}`.
 */
class m211012_130518_create_admin_gii_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_gii_modules}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'add_info' => $this->text(),
            'token' => $this->string(),
            'type' => $this->tinyInteger(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->upsert('{{%admin_gii_modules}}', ['name' => 'admin', 'status' => 1], true);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_gii_modules}}');
    }
}
