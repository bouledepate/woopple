<?php

namespace Woopple\Models\Event;

use Exception;
use Woopple\Models\User\User;
use yii\db\ActiveRecord;
use yii\db\JsonExpression;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property string|\DateTimeImmutable $date,
 * @property JsonExpression|string $icon
 * @property JsonExpression|string $buttons
 */
class Event extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['user_id', 'title', 'icon'], 'required'],
            [['title', 'message'], 'trim'],
            [['title', 'message'], 'string'],
            ['user_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class],
            [['icon', 'buttons'], 'safe']
        ];
    }

    /** @throws Exception */
    public function afterFind()
    {
        $this->date = new \DateTimeImmutable($this->date, new \DateTimeZone('UTC'));
        parent::afterFind();
    }

    public static function create(EventData $data): self|false
    {
        $event = new self();
        $event->prepareIcon($data->icon);
        $event->prepareButtons($data->buttons);
        $event->setAttributes([
            'user_id' => $data->user,
            'title' => $data->title,
            'message' => $data->message
        ]);

        if ($event->validate() && $event->save()) {
            return $event;
        }

        return false;
    }

    protected function prepareIcon(Icon $icon): void
    {
        $this->icon = new JsonExpression([
            'icon' => $icon->icon,
            'background' => $icon->background
        ]);
    }

    /**
     * @param Button[] $buttons
     */
    protected function prepareButtons(array $buttons): void
    {
        $data = array_map(function (Button $button) {
            return [
                'title' => $button->title,
                'link' => $button->link,
                'style' => $button->style
            ];
        }, $buttons);

        $this->buttons = new JsonExpression($data);
    }
}