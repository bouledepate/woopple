<?php
/**
 * @var array $events
 */
?>
<div class="timeline timeline-inverse">
    <?php foreach ($events as $day => $dailyEvents): ?>
        <div class="time-label">
            <span class="bg-gradient-purple color-palette"><?= $day ?></span>
        </div>

        <?php /** @var \Woopple\Models\Event\Event $event */
        foreach ($dailyEvents as $event): ?>
            <div>
                <i class="<?= $event->icon['icon'] ?> <?= $event->icon['background'] ?>"></i>
                <div class="timeline-item">
                    <span class="time"><i class="far fa-clock"></i> <?= $event->date->format('H:i') ?></span>
                    <h3 class="timeline-header"><?= $event->title ?></h3>
                    <?php if (!empty($event->message)): ?>
                        <div class="timeline-body"><?= $event->message ?></div>
                    <?php endif; ?>
                    <?php if (!empty($event->buttons)): ?>
                        <div class="timeline-footer">
                            <?php foreach ($event->buttons as $button): ?>
                                <a href="<?= $button['link'] ?>"
                                   class="<?= $button['style'] ?>"><?= $button['title'] ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php if (empty($events)): ?>
        <div>
            <i class="far fa-circle bg-gradient-danger"></i>
            <div class="timeline-item">
                <span class="time"><i class="far fa-clock"></i> <?= date('H:i', time()) ?></span>
                <h3 class="timeline-header">Сегодня <?= date('d.m.Y', time()) ?>. Ничего не произошло. </h3>
            </div>
        </div>
    <?php endif; ?>
    <div>
        <i class="far fa-clock bg-gray"></i>
    </div>
</div>