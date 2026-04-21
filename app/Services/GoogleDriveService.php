<?php

namespace App\Services;

use App\Models\GoogleSetting;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $setting = GoogleSetting::first();
        if (!$setting || !$setting->refresh_token) {
            throw new \Exception('Google Drive not connected.');
        }

        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setAccessToken($setting->access_token);

        if ($setting->expires_at && $setting->expires_at->isPast()) {
            $this->client->fetchAccessTokenWithRefreshToken($setting->refresh_token);
            $newTokens = $this->client->getAccessToken();
            $setting->access_token = $newTokens['access_token'];
            $setting->expires_at = now()->addSeconds($newTokens['expires_in']);
            $setting->save();
        }

        $this->service = new Drive($this->client);
    }

    public function upload($filePath, $fileName, $folderId = null)
    {
        $setting = GoogleSetting::first();
        $parentFolder = $folderId ?? $setting->root_folder_id;

        $fileMetadata = new Drive\DriveFile([
            'name' => $fileName,
            'parents' => [$parentFolder]
        ]);

        $content = file_get_contents($filePath);

        $file = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink'
        ]);

        return $file;
    }
}
