<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%department}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230401_064541_create_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(256),
            'lead' => $this->integer(),
        ]);

        // creates index for column `lead`
        $this->createIndex(
            '{{%idx-department-lead}}',
            '{{%department}}',
            'lead'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-department-lead}}',
            '{{%department}}',
            'lead',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-department-lead}}',
            '{{%department}}'
        );

        // drops index for column `lead`
        $this->dropIndex(
            '{{%idx-department-lead}}',
            '{{%department}}'
        );

        $this->dropTable('{{%department}}');
    }
}
