# Google Sheets notifications channel for Laravel

[![tests](https://github.com/mkohei/laravel-google-sheets-notification-channel/actions/workflows/tests.yml/badge.svg)](https://github.com/mkohei/laravel-google-sheets-notification-channel/actions/workflows/tests.yml)
[![StyleCI](https://github.styleci.io/repos/475269632/shield?branch=main)](https://github.styleci.io/repos/475269632?branch=main)

This package makes it easy to send [Google Sheets](https://www.google.com/sheets/about/) using the Laravel notification system and the [Google Sheets API](https://developers.google.com/sheets/api).

## Contents

- [Contents](#contents)
- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [License](#license)

## Installation

You can install the package via composer:

```
composer require mkohei/laravel-google-sheets-notification-channel
```

Set your Google App Client ID and Client Secret to `config/services.php`:

```php
'google' => [
    'sheets' => [
        'client_id' => env('INTEGRATIONS_GOOGLE_SHEETS_CLIENT_ID'),
        'client_secret' => env('INTEGRATIONS_GOOGLE_SHEETS_CLIENT_SECRET'),
    ],
],
```

Add a service provider to `config/app.php`:

```php
/*
 * Package Service Providers...
 */
Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsServiceProvider::class,
```

## Usage

Now you can use the channel in your via() method inside the notification:

```php
use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsChannel;
use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    public function via($notifiable)
    {
        return [GoogleSheetsChannel::class];
    }

    public function toGoogleSheets($notifiable)
    {
        return TreasureDataMessage::create()
            ->data(['value1', 'value2'])
            ->accessToken('your-access-token')
            ->spreadsheetId('id');
    }
}
```

## Testing

```shell
composer test
```

## License

[MIT License](LICENSE).
