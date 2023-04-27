<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_answer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%test}}`
 * - `{{%question}}`
 */
class m230425_050235_create_user_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_answer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'test_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'text' => $this->string(),
            'answer_id' => $this->integer(),
            'is_correct' => $this->boolean()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_answer-user_id}}',
            '{{%user_answer}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_answer-user_id}}',
            '{{%user_answer}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `test_id`
        $this->createIndex(
            '{{%idx-user_answer-test_id}}',
            '{{%user_answer}}',
            'test_id'
        );

        // add foreign key for table `{{%test}}`
        $this->addForeignKey(
            '{{%fk-user_answer-test_id}}',
            '{{%user_answer}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE'
        );

        // creates index for column `question_id`
        $this->createIndex(
            '{{%idx-user_answer-question_id}}',
            '{{%user_answer}}',
            'question_id'
        );

        // add foreign key for table `{{%question}}`
        $this->addForeignKey(
            '{{%fk-user_answer-question_id}}',
            '{{%user_answer}}',
            'question_id',
            '{{%question}}',
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
            '{{%fk-user_answer-user_id}}',
            '{{%user_answer}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_answer-user_id}}',
            '{{%user_answer}}'
        );

        // drops foreign key for table `{{%test}}`
        $this->dropForeignKey(
            '{{%fk-user_answer-test_id}}',
            '{{%user_answer}}'
        );

        // drops index for column `test_id`
        $this->dropIndex(
            '{{%idx-user_answer-test_id}}',
            '{{%user_answer}}'
        );

        // drops foreign key for table `{{%question}}`
        $this->dropForeignKey(
            '{{%fk-user_answer-question_id}}',
            '{{%user_answer}}'
        );

        // drops index for column `question_id`
        $this->dropIndex(
            '{{%idx-user_answer-question_id}}',
            '{{%user_answer}}'
        );

        $this->dropTable('{{%user_answer}}');
    }
}
