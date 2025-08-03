<?php

namespace yetopen\smssender;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\log\Logger;
use yii\mail\MessageInterface;

/**
 * BaseSmsSender serves as a base class that implements the basic functions required by [[SmsSenderInterface]].
 *
 * Concrete child classes should may focus on implementing the [[sendMessage()]] method.
 */
abstract class BaseSmsSender extends Component implements SmsSenderInterface
{
    /**
     * @event SmsSenderEvent an event raised right before send.
     * You may set [[SmsSenderEvent::isValid]] to be false to cancel the send.
     */
    const EVENT_BEFORE_SEND = 'beforeSend';
    /**
     * @event SmsSenderEvent an event raised right after send.
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * @var array|string Recipients numbers.
     */
    public $numbers;
    /**
     * @var string The content of the message.
     */
    public $content;
    /**
     * @var string|null (optional) The sender name.
     */
    public $sender;
    /**
     * @var string (optional) International prefix (e.g. +39).
     */
    public $prefix;
    /**
     * @var string|null (optional) Date-time in which the message will be scheduled to be sent.
     */
    public $deliveryTime;
    /**
     * @var bool whether to save sms messages as files under [[fileTransportPath]] instead of sending them
     * to the actual recipients. This is usually used during development for debugging purpose.
     * @see fileTransportPath
     */
    public $useFileTransport = false;
    /**
     * @var string the directory where the email messages are saved when [[useFileTransport]] is true.
     */
    public $fileTransportPath = '@runtime/sms';

    /**
     * Create a new [SmsSenderInterface] instance
     * @param array|string $numbers Recipient number.
     * @param string $content Message to send.
     * @param string|null $sender (optional) The sender name.
     * @param string $prefix (optional) International prefix (e.g. +39).
     * @param string|null $deliveryTime (optional) Date-time in which the message will be scheduled to be sent.
     * @return string $response The response from the API.
     */
    public function createMessage($numbers, $content, $sender=NULL, $prefix="+39",$deliveryTime=NULL)
    {
        if(!is_array($numbers)) {
            $numbers = [$numbers];
        }
        return new static([
            'numbers' => $numbers,
            'content' => $content,
            'sender' => $sender,
            'prefix' => $prefix,
            'deliveryTime' => $deliveryTime,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function send($tel, $message, $sender=NULL, $prefix="+39",$delivery_time=NULL)
    {
        $message = static::createMessage($tel, $message, $sender=NULL, $prefix="+39",$delivery_time=NULL);
        if (!$this->beforeSend($message)) {
            return false;
        }
        try {
            if ($this->useFileTransport) {
                $isSuccessful = $this->saveMessage($message);
            } else {
                $isSuccessful = $this->sendMessage($message);
            }
        } catch (Exception $exception) {
            $isSuccessful = false;
            Yii::error($exception, __METHOD__);
        }
        $this->afterSend($message, $isSuccessful);

        return $isSuccessful;
    }

    /**
     * Performs the actual sending of the SMS.
     * @param $message BaseSmsSender
     * @return bool
     */
    abstract public function sendMessage($message);

    /**
     * Saves the message as a file under [[fileTransportPath]].
     * @param BaseSmsSender $message
     * @return bool whether the message is saved successfully
     */
    protected function saveMessage($message)
    {
        $path = Yii::getAlias($this->fileTransportPath);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $file = $path . '/' . $this->generateMessageFileName();
        file_put_contents($file, $message->content);

        return true;
    }

    /**
     * @return string the file name for saving the message when [[useFileTransport]] is true.
     * @throws \Exception
     */
    public function generateMessageFileName()
    {
        $time = microtime(true);
        $timeInt = (int) $time;

        return date('Ymd-His-', $timeInt) . sprintf('%04d', (int) (($time - $timeInt) * 10000)) . '-' . sprintf('%04d', random_int(0, 10000)) . '.txt';
    }

    /**
     * This method is invoked right before an sms is sent.
     * You may override this method to do last-minute preparation for the message.
     * If you override this method, please make sure you call the parent implementation first.
     * @param BaseSmsSender $message
     * @return bool whether to continue sending an email.
     */
    public function beforeSend($message)
    {
        $event = new SmsSenderEvent(['message' => $message]);
        $this->trigger(self::EVENT_BEFORE_SEND, $event);

        return $event->isValid;
    }

    /**
     * This method is invoked right after an sms was send.
     * You may override this method to do some postprocessing or logging based on sms send status.
     * If you override this method, please make sure you call the parent implementation first.
     * @param SmsSenderInterface $message
     */
    public function afterSend($message)
    {
        $event = new SmsSenderEvent(['message' => $message]);
        $this->trigger(self::EVENT_AFTER_SEND, $event);
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     */
    protected function logError($message, $category)
    {
        $this->log(Logger::LEVEL_ERROR, $message, $category);
    }

    /**
     * Logs a debug message.
     * Trace messages are logged mainly for development purpose to see
     * the execution work flow of some code. This method will only log
     * a message when the application is in debug mode.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     * @param string $category the category of the message.
     */
    protected function debug($message, $category)
    {
        // Logging only if `YII_DEBUG` is `true` since it's a Yii2 standard
        if(YII_DEBUG) {
            $this->log(Logger::LEVEL_TRACE, $message, $category);
        }
    }

    /**
     * If logging is enable it logs a message with the given type and category.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure that will be handled by a [[Target|log target]].
     * @param int $level the level of the message. This must be one of the following:
     * `Logger::LEVEL_ERROR`, `Logger::LEVEL_WARNING`, `Logger::LEVEL_INFO`, `Logger::LEVEL_TRACE`,
     * `Logger::LEVEL_PROFILE_BEGIN`, `Logger::LEVEL_PROFILE_END`.
     * @param string $category the category of the message.
     */
    protected function log($level, $message, $category)
    {
        if(!$this->enableLogging) { // Logging is not enable, skipping
            return;
        }
        Yii::getLogger()->log($message, $level, $category);
    }
}
