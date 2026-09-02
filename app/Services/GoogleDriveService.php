<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private Client $httpClient;
    private ?string $accessToken = null;

    public function __construct(?Client $client = null)
    {
        $this->httpClient = $client ?? new Client(['timeout' => 120]);
    }

    /**
     * Get or generate access token from Service Account or Refresh Token.
     */
    public function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        // Method 1: Service Account JSON
        $serviceAccountJson = config('services.google_drive.service_account_json');
        if ($serviceAccountJson) {
            $this->accessToken = $this->getAccessTokenFromServiceAccount($serviceAccountJson);
            return $this->accessToken;
        }

        // Method 2: OAuth2 Refresh Token
        $clientId = config('services.google_drive.client_id');
        $clientSecret = config('services.google_drive.client_secret');
        $refreshToken = config('services.google_drive.refresh_token');

        if ($clientId && $clientSecret && $refreshToken) {
            $this->accessToken = $this->getAccessTokenFromRefreshToken($clientId, $clientSecret, $refreshToken);
            return $this->accessToken;
        }

        throw new \RuntimeException('Google Drive authentication credentials are not configured. Please set GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON or (GOOGLE_DRIVE_CLIENT_ID, GOOGLE_DRIVE_CLIENT_SECRET, GOOGLE_DRIVE_REFRESH_TOKEN) in your .env.');
    }

    /**
     * Authenticate via Service Account JSON (JWT Bearer Token).
     */
    private function getAccessTokenFromServiceAccount(string $jsonOrPath): string
    {
        $jsonContent = $jsonOrPath;
        if (file_exists($jsonOrPath)) {
            $jsonContent = file_get_contents($jsonOrPath);
        } elseif (file_exists(base_path($jsonOrPath))) {
            $jsonContent = file_get_contents(base_path($jsonOrPath));
        }

        $credentials = json_decode($jsonContent, true);
        if (!$credentials || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new \InvalidArgumentException('Invalid Google Service Account JSON credentials.');
        }

        $now = time();
        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwtClaim = json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64Header = $this->base64UrlEncode($jwtHeader);
        $base64Claim = $this->base64UrlEncode($jwtClaim);
        $signatureInput = $base64Header . '.' . $base64Claim;

        $privateKey = $credentials['private_key'];
        $signature = '';
        $success = openssl_sign($signatureInput, $signature, $privateKey, 'sha256WithRSAEncryption');

        if (!$success) {
            throw new \RuntimeException('Failed to sign JWT with Google Service Account private key.');
        }

        $base64Signature = $this->base64UrlEncode($signature);
        $assertion = $signatureInput . '.' . $base64Signature;

        $response = $this->httpClient->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion,
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);
        if (empty($data['access_token'])) {
            throw new \RuntimeException('Failed to obtain Google access token: ' . $response->getBody());
        }

        return $data['access_token'];
    }

    /**
     * Authenticate via OAuth2 Refresh Token.
     */
    private function getAccessTokenFromRefreshToken(string $clientId, string $clientSecret, string $refreshToken): string
    {
        $response = $this->httpClient->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);
        if (empty($data['access_token'])) {
            throw new \RuntimeException('Failed to refresh Google access token: ' . $response->getBody());
        }

        return $data['access_token'];
    }

    /**
     * Upload a local file to Google Drive.
     *
     * @param string $filePath Full path to the local file
     * @param string $fileName Target filename on Google Drive
     * @param string|null $folderId Target folder ID on Google Drive (optional)
     * @return array Google Drive file metadata (id, name, webViewLink, etc.)
     */
    public function uploadFile(string $filePath, string $fileName, ?string $folderId = null): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File does not exist: {$filePath}");
        }

        $token = $this->getAccessToken();
        $folderId = $folderId ?? config('services.google_drive.folder_id');

        $mimeType = str_ends_with($fileName, '.sql') ? 'application/sql' : 'application/gzip';

        $metadata = [
            'name' => $fileName,
            'mimeType' => $mimeType,
        ];

        if ($folderId) {
            $metadata['parents'] = [$folderId];
        }

        $fileHandle = fopen($filePath, 'r');
        if (!$fileHandle) {
            throw new \RuntimeException("Could not open file for reading: {$filePath}");
        }

        $response = $this->httpClient->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
            'multipart' => [
                [
                    'name' => 'metadata',
                    'contents' => json_encode($metadata),
                    'headers' => [
                        'Content-Type' => 'application/json; charset=UTF-8',
                    ],
                ],
                [
                    'name' => 'file',
                    'contents' => $fileHandle,
                    'headers' => [
                        'Content-Type' => $mimeType,
                    ],
                ],
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        Log::info('Database backup uploaded to Google Drive', [
            'file_id' => $result['id'] ?? null,
            'name' => $fileName,
            'folder_id' => $folderId,
        ]);

        return $result;
    }

    /**
     * Delete backups older than specified number of days from Google Drive folder.
     */
    public function cleanupOldBackups(?string $folderId = null, int $retentionDays = 30): int
    {
        $folderId = $folderId ?? config('services.google_drive.folder_id');
        if (!$folderId) {
            return 0;
        }

        $token = $this->getAccessToken();
        $cutoffDate = date('Y-m-d\TH:i:s\Z', strtotime("-{$retentionDays} days"));

        $query = "'{$folderId}' in parents and trashed = false and createdTime < '{$cutoffDate}'";

        $response = $this->httpClient->get('https://www.googleapis.com/drive/v3/files', [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
            'query' => [
                'q' => $query,
                'fields' => 'files(id, name, createdTime)',
                'pageSize' => 50,
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);
        $deletedCount = 0;

        foreach ($data['files'] ?? [] as $file) {
            $fileId = $file['id'];
            $this->httpClient->delete("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);
            $deletedCount++;
            Log::info("Deleted expired Google Drive backup: {$file['name']} ({$fileId})");
        }

        return $deletedCount;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
