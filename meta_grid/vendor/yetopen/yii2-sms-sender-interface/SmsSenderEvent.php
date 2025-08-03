<?php

namespace yetopen\smssender;

use yii\base\Event;

/**
 * SmsSenderEvent represents the event parameter used for events triggered by [[BaseSmsSender]].
 *
 * By setting the [[isValid]] property, one may control whether to continue running the action.
 */
class SmsSenderEvent extends Event
{
    /**
     * @var SmsSenderInterface the mail message being send.
     */
    public $message;
    /**
     * @var bool if message was sent successfully.
     */
    public $isSuccessful;
    /**
     * @var bool whether to continue sending an email. Event handlers of
     * [[\yii\mail\BaseSmsSender::EVENT_BEFORE_SEND]] may set this property to decide whether
     * to continue send or not.
     */
    public $isValid = true;
}
