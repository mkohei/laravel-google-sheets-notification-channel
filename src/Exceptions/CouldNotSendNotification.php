<?php

namespace Mkohei\LaravelGoogleSheetsNotificationChannel\Exceptions;

use Exception;
use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown if a notification instance does not implement a toGoogleSheets() method, but is
     * attempting to be delivered via the Google Sheets notification channel.
     *
     * @param mixed $notification
     * @return static
     */
    public static function undefinedMethod($notification)
    {
        return new static(
            'Notification of class: '.get_class($notification)
            .' must define a `toGoogleSheets()` method in order to send via the Google Sheets Channel'
        );
    }

    /**
     * Thrown if a notification instance's toGoogleSheets() method returns a value other than
     * an instance of \Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsMessage.
     *
     * @param mixed $actual
     * @return static
     */
    public static function invalidMessage($actual)
    {
        return new static(
            'Expected a message instance of type '.GoogleSheetsMessage::class
            .' - Actually received '
            .(
                is_object($actual)
                ? 'instance of: '.get_class($actual)
                : gettype($actual)
            )
        );
    }

    /**
     * Thrown if an unexpected exception was encountered whilst attempting to deliver the
     * notification.
     *
     * @param \Exception $exception
     * @return static
     */
    public static function unexpectedException(Exception $exception)
    {
        return new static(
            'Failed to send Google Sheets message, unexpected exception encountered: `'.$exception->getMessage().'`',
            0,
            $exception
        );
    }
}
