<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230425_045651_create_test_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(256)->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'state' => $this->string()->notNull()->defaultValue('new'),
            'expiration_date' => $this->timestamp(),
            'is_closed' => $this->boolean()->defaultValue(true),
            'availability' => $this->string()->notNull()->defaultValue('team_only'),
            'subject_id' => $this->integer(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-test-author_id}}',
            '{{%test}}',
            'author_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-test-author_id}}',
            '{{%test}}',
            'author_id',
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
            '{{%fk-test-author_id}}',
            '{{%test}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-test-author_id}}',
            '{{%test}}'
        );

        $this->dropTable('{{%test}}');
    }
}
