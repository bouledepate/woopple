<?php

namespace Woopple\Forms;

use Throwable;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use yii\base\Model;
use Woopple\Models\User\UserProfile;

class ProfileForm extends Model
{
    public ?int $profile = null;
    public ?string $education = null;
    public ?string $skills = null;
    public ?string $notes = null;

    public function rules(): array
    {
        return [
            ['profile', 'required'],
            [['education', 'skills', 'notes'], 'string'],
            [['education', 'skills', 'notes'], 'trim'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'education' => 'Образование',
            'skills' => 'Навыки',
            'notes' => 'Заметки'
        ];
    }

    /**
     * @throws Throwable
     */
    public function update(): bool
    {
        $profile = UserProfile::findOne(['id' => $this->profile]);
        $profile->setAttributes([
            'education' => $this->education,
            'skills' => $this->skills,
            'notes' => $this->notes
        ]);

        $changes = $this->formatChanges($profile);

        $result = $profile->update(false);

        if ($result) {
            $this->noteEvent($profile->user_id, $changes);
        }

        return $result;
    }

    protected function noteEvent(int $user, string $changes): void
    {
        Event::create(new EventData(
            user: $user,
            title: 'Обновление профиля',
            message: $changes,
            icon: new Icon(
                icon: 'fa fa-user',
                background: 'bg-success'
            )
        ));
    }

    protected function formatChanges(UserProfile $profile): string
    {
        $changes = [];
        $previous = $profile->getOldAttributes();
        $current = $profile->getAttributes();

        if ($current['education'] !== $previous['education']) {
            $changes[] = 'информация об образовании';
        }

        if ($current['skills'] !== $previous['skills']) {
            $changes[] = 'информация о навыках';
        }

        if ($current['notes'] !== $previous['notes']) {
            $changes[] = 'пользовательские заметки';
        }

        return "В профиле была обновлена следующая информация: " . implode(', ', $changes) . ".";
    }
}