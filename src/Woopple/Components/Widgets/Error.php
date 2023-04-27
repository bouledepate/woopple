<?php

namespace Woopple\Components\Widgets;

class Error extends Widget
{
    public int $httpCode;
    public string $title;
    public string $message;
    public \Throwable $exception;

    protected string $httpStatusStyle;
    protected string $prefix = '_description';

    public function run()
    {
        return $this->render('error', $this->getViewParams());
    }

    protected function getViewParams(): array
    {
        $this->title = $this->getErrorTitle();
        $this->message = $this->getErrorMessage();
        $this->httpStatusStyle = $this->defineStyle();

        return [
            'title' => $this->title,
            'message' => $this->message,
            'style' => $this->httpStatusStyle,
            'status' => $this->httpCode
        ];
    }

    protected function getErrorTitle(): string
    {
        return in_array($this->httpCode, [403, 404, 500]) ? \Yii::t('error', $this->httpCode) : $this->title;
    }

    protected function getErrorMessage(): string
    {
        if (in_array($this->httpCode, [403, 404])) {
            $key = $this->httpCode . $this->prefix;
            $message = \Yii::t('error', $key);
        } else {
            $message = $this->message;
        }
        return $message;
    }

    protected function defineStyle(): string
    {
        return $this->httpCode == 500 ? 'text-danger' : 'text-warning';
    }
}