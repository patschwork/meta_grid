<?php

namespace yetopen\smssender;

/**
 * MailerInterface is the interface that should be implemented by mailer classes.
 *
 * A mailer should mainly support creating and sending [[MessageInterface|mail messages]]. It should
 * also support composition of the message body through the view rendering mechanism. For example,
 *
 * ```php
 * Yii::$app->smssender->send(['3333333333'], 'Your text message', 'Sender Name', '+39', );
 * ```
 */
interface SmsSenderInterface
{
    /**
     * @return int
     */
    public function getMinTextLength();

    /**
     * @return int
     */
    public function getMaxTextLength();

    /**
     * Send the message given the phone number and message.
     *
     * @param array|string $number Recipients phone numbers, without prefix (e.g. +39).
     *
     * @param string $message Message to send.
     *
     * @param string|NULL $sender (optional) The sender name.
     *
     * @param string $prefix (optional) International prefix (e.g. +39).
     *
     * @param string|NULL $delivery_time (optional) Date-time in which the message will be scheduled to be sent.
     *
     * @return string $response The response from the API.
     */
    public function send($number, $message, $sender = NULL, $prefix = NULL, $delivery_time = NULL);
}