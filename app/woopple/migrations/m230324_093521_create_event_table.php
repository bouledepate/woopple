<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230324_093521_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string(255)->notNull(),
            'message' => $this->string(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'icon' => $this->json(),
            'buttons' => $this->json(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-event-user_id}}',
            '{{%event}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-event-user_id}}',
            '{{%event}}',
            'user_id',
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
            '{{%fk-event-user_id}}',
            '{{%event}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-event-user_id}}',
            '{{%event}}'
        );

        $this->dropTable('{{%event}}');
    }
}
