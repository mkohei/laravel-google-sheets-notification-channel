<?php

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\Resource\SpreadsheetsValues;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsChannel;
use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsMessage;
use Mkohei\LaravelGoogleSheetsNotificationChannel\Exceptions\CouldNotSendNotification;
use Mockery as m;
use Mockery\MockInterface;

beforeEach(function () {
    $this->sheets = m::mock(Sheets::class);
    $this->channel = new GoogleSheetsChannel($this->sheets);
});

afterEach(function () {
    m::close();
});

it('can send a notification', function () {
    $this->sheets->spreadsheets_values = m::mock(SpreadsheetsValues::class, function (MockInterface $mock) {
        $mock->shouldReceive('append')->once()->andReturn(new AppendValuesResponse());
    });
    $this->sheets->shouldReceive('getClient')->once()->andReturn(m::mock(Client::class, function(MockInterface $mock) {
        $mock->shouldReceive('setAccessToken')->once()->with('access-token');
    }));
    $this->channel = new GoogleSheetsChannel($this->sheets);

    $notification = new class extends Notification {
        public function toGoogleSheets($notifiable) {
            return (new GoogleSheetsMessage([]))
                ->accessToken('access-token')
                ->spreadsheetId('id');
        }
    };

    $this->channel->send(new TestNotifiable(), $notification);
});

it('throws exception when passed notification has not toGoogleSheets()', function () {
    $notification = new class extends Notification{};
    $this->channel->send(new TestNotifiable(), $notification);
})->throws(CouldNotSendNotification::class);

it('throws exception when toGoogleSheets() return no message', function () {
    $notification = new class extends Notification {
        public function toGoogleSheets($notifiable) {
            return 'wrong-message';
        }
    };
    $this->channel->send(new TestNotifiable(), $notification);
})->throws(CouldNotSendNotification::class);

it('throws exception when exception is thrown in sending', function () {
    $this->sheets->spreadsheets_values = m::mock(SpreadsheetsValues::class, function (MockInterface $mock) {
        $mock->shouldReceive('append')->once()->andThrow(new \Exception());
    });
    $this->sheets->shouldReceive('getClient')->once()->andReturn(m::mock(Client::class, function(MockInterface $mock) {
        $mock->shouldReceive('setAccessToken')->once()->with('access-token');
    }));
    $this->channel = new GoogleSheetsChannel($this->sheets);

    $notification = new class extends Notification {
        public function toGoogleSheets($notifiable) {
            return (new GoogleSheetsMessage([]))
                ->accessToken('access-token')
                ->spreadsheetId('id');
        }
    };

    $this->channel->send(new TestNotifiable(), $notification);
})->throws(CouldNotSendNotification::class);

class TestNotifiable
{
    use Notifiable;
}
