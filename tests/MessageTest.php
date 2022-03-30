<?php

use Mkohei\LaravelGoogleSheetsNotificationChannel\GoogleSheetsMessage;

beforeEach(function () {
    $this->message = new GoogleSheetsMessage();
});

it('accepts data when constructing a message', function () {
    $message = new GoogleSheetsMessage(['foo' => 'bar']);
    $this->assertEquals(['foo' => 'bar'], $message->getData());
});

it('provides a create method', function () {
    $message = GoogleSheetsMessage::create(['foo' => 'bar']);
    $this->assertEquals(['foo' => 'bar'], $message->getData());
});

it('can set a access token', function () {
    $this->message->accessToken('access-token');
    $this->assertEquals('access-token', $this->message->getAccessToken());
});

it('can set a spreadsheet id', function () {
    $this->message->spreadsheetId('id');
    $this->assertEquals('id', $this->message->getSpreadsheetId());
});

it("can set a treasure data's data", function () {
    $this->message->data(['foo' => 'bar']);
    $this->assertEquals(['foo' => 'bar'], $this->message->getData());
});
