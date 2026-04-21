<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GoogleSetting;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;
use Google\Service\Drive;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(url('/google/callback'))
            ->scopes([Drive::DRIVE_FILE, Drive::DRIVE_METADATA])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')
                ->redirectUrl(url('/google/callback'))
                ->user();

            $setting = GoogleSetting::firstOrNew([]);
            $setting->access_token = $user->token;
            $setting->refresh_token = $user->refreshToken ?? $setting->refresh_token;
            $setting->expires_at = now()->addSeconds($user->expiresIn);
            $setting->save();

            // Search or Create Root Folder
            $this->ensureRootFolder($setting);

            return redirect()->route('dashboard')->with('success', 'Berhasil terhubung dengan Google Drive.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal terhubung dengan Google Drive: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $setting = GoogleSetting::first();
        if ($setting) {
            // Kita hanya menghapus token, tapi menjaga root_folder_id agar jika connect lagi tetap pakai folder yang sama
            $setting->update([
                'access_token' => null,
                'refresh_token' => null,
                'expires_at' => null
            ]);
        }
        return redirect()->route('dashboard')->with('success', 'Koneksi Google Drive diputuskan.');
    }

    private function ensureRootFolder($setting)
    {
        $client = new Client();
        $client->setAccessToken($setting->access_token);
        
        // Refresh token if expired
        if ($setting->expires_at && $setting->expires_at->isPast()) {
            if ($setting->refresh_token) {
                $client->fetchAccessTokenWithRefreshToken($setting->refresh_token);
                $newTokens = $client->getAccessToken();
                $setting->access_token = $newTokens['access_token'];
                $setting->expires_at = now()->addSeconds($newTokens['expires_in']);
                $setting->save();
            }
        }

        $service = new Drive($client);
        $folderName = 'Arsip Digital ZI MAN 2 Gresik';

        // 1. Cek di database dulu
        if ($setting->root_folder_id) {
            try {
                $service->files->get($setting->root_folder_id);
                return; // Folder masih ada dan valid
            } catch (\Exception $e) {
                // Folder mungkin dihapus di Drive, lanjut cari berdasarkan nama
            }
        }

        // 2. Cari berdasarkan nama di Drive
        $query = "name = '$folderName' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
        $results = $service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)',
            'pageSize' => 1
        ]);

        if (count($results->getFiles()) > 0) {
            $folder = $results->getFiles()[0];
            $setting->root_folder_id = $folder->id;
            $setting->save();
        } else {
            // 3. Buat baru jika benar-benar tidak ada
            $fileMetadata = new Drive\DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);
            $folder = $service->files->create($fileMetadata, ['fields' => 'id']);
            $setting->root_folder_id = $folder->id;
            $setting->save();
        }
    }

}
