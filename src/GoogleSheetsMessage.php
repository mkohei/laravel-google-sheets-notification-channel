<?php

namespace Mkohei\LaravelGoogleSheetsNotificationChannel;

class GoogleSheetsMessage
{
    protected string|array|null $accessToken = null;
    protected ?string $spreadsheetId = null;
    protected array $data = [];

    public static function create(array $data = []): static
    {
        return new static($data);
    }

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function accessToken(string|array $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getAccessToken(): string|array|null
    {
        return $this->accessToken;
    }

    public function spreadsheetId(string $spreadsheetId): static
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    public function getSpreadsheetId(): ?string
    {
        return $this->spreadsheetId;
    }

    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'accessToken'   => $this->accessToken,
            'spreadsheetId' => $this->spreadsheetId,
            'data'          => $this->data,
        ];
    }
}
