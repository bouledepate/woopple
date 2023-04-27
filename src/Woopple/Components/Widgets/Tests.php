<?php

namespace Woopple\Components\Widgets;

use yii\data\ActiveDataProvider;

class Tests extends Widget
{
    public ActiveDataProvider $dataProvider;
    private array $_data;
    private array $progression;

    public function init()
    {
        parent::init();
        $this->_data = $this->dataProvider->getModels();
        $this->calculateProgression();
    }

    private function calculateProgression()
    {
        $respondents = $this->getRespondents();
    }

    private function getRespondents(): array
    {
        return [];
    }
}