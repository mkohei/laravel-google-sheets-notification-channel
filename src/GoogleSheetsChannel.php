<?php

namespace Mkohei\LaravelGoogleSheetsNotificationChannel;

use Google\Service\Sheets;
use Illuminate\Notifications\Notification;
use Mkohei\LaravelGoogleSheetsNotificationChannel\Exceptions\CouldNotSendNotification;

class GoogleSheetsChannel
{
    protected Sheets $sheets;

    public function __construct(Sheets $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * Send a given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \Mkohei\LaravelTdNotificationChannel\Exceptions\CloudNotSendNotification
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toGoogleSheets')) {
            throw CouldNotSendNotification::undefinedMethod($notification);
        }

        /** @var GoogleSheetsMessage $message */
        if (! ($message = $notification->toGoogleSheets($notifiable)) instanceof GoogleSheetsMessage) {
            throw CouldNotSendNotification::invalidMessage($message);
        }

        $this->sheets->getClient()->setAccessToken($message->getAccessToken());

        $valueRange = new \Google\Service\Sheets\ValueRange();
        $valueRange->setValues([$message->getData()]);

        $options = [
            'valueInputOption' => 'RAW',
            'insertDataOption' => 'INSERT_ROWS',
        ];

        try {
            $response = $this->sheets->spreadsheets_values->append(
                $message->getSpreadsheetId(), 'A1', $valueRange, $options
            );
        } catch (\Exception $e) {
            throw CouldNotSendNotification::unexpectedException($e);
        }
    }
}
