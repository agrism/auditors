<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GoogleDriveAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-drive:auth 
                            {--code= : Authorization code from Google}
                            {--redirect-uri=https://developers.google.com/oauthplayground : Redirect URI configured in Google Cloud Console}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OAuth URL and exchange authorization code for Google Drive Refresh Token';

    public function handle(): int
    {
        $clientId = config('services.google_drive.client_id') ?: env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = config('services.google_drive.client_secret') ?: env('GOOGLE_DRIVE_CLIENT_SECRET');
        $redirectUri = $this->option('redirect-uri');

        if (empty($clientId) || empty($clientSecret)) {
            $this->error('GOOGLE_DRIVE_CLIENT_ID un GOOGLE_DRIVE_CLIENT_SECRET nav iestatīti .env failā!');
            $clientId = $this->ask('Lūdzu ievadiet GOOGLE_DRIVE_CLIENT_ID');
            $clientSecret = $this->ask('Lūdzu ievadiet GOOGLE_DRIVE_CLIENT_SECRET');

            if (empty($clientId) || empty($clientSecret)) {
                $this->error('Nepieciešams gan Client ID, gan Client Secret.');
                return Command::FAILURE;
            }
        }

        $code = $this->option('code');

        if (empty($code)) {
            $scope = urlencode('https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file');
            $encodedRedirectUri = urlencode($redirectUri);
            $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?client_id={$clientId}&redirect_uri={$encodedRedirectUri}&response_type=code&scope={$scope}&access_type=offline&prompt=consent";

            $this->info('================================================================');
            $this->info('        GOOGLE DRIVE OAUTH2 REFRESH TOKEN ĢENERATORS           ');
            $this->info('================================================================');
            $this->newLine();
            $this->line('1. Pārliecinieties, ka Google Cloud Console sadaļā "OAuth 2.0 Client IDs" kā Authorized redirect URI ir pievienots:');
            $this->comment("   {$redirectUri}");
            $this->newLine();
            $this->line('2. Atveriet šo autorizācijas saiti savā pārlūkā:');
            $this->comment("   {$authUrl}");
            $this->newLine();
            $this->line('3. Ielogojieties ar Google kontu, apstipriniet piekļuvi un nokopējiet autorizācijas kodu (code).');
            $this->newLine();

            $code = $this->ask('Lūdzu ievadiet saņemto autorizācijas kodu (Authorization code)');

            if (empty($code)) {
                $this->error('Autorizācijas kods netika ievadīts.');
                return Command::FAILURE;
            }
        }

        $this->info('Pieprasa jaunu Refresh Token no Google...');

        try {
            $client = new Client(['timeout' => 30]);
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => trim($code),
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if (empty($data['refresh_token'])) {
                $this->warn('Google neatgrieza jaunu refresh_token. Iespējams, prompts nebija "consent" vai autorizācija jau ir aktīva.');
                if (!empty($data['access_token'])) {
                    $this->info('Saņemts derīgs access_token.');
                }
                $this->line(json_encode($data, JSON_PRETTY_PRINT));
                return Command::FAILURE;
            }

            $refreshToken = $data['refresh_token'];

            $this->newLine();
            $this->info('================================================================');
            $this->info('✓ JAUNAIS REFRESH TOKEN IR VEIKSMĪGI SAŅEMTS!');
            $this->info('================================================================');
            $this->newLine();
            $this->line("GOOGLE_DRIVE_REFRESH_TOKEN=\"{$refreshToken}\"");
            $this->newLine();

            if ($this->confirm('Vai vēlaties automātiski atjaunināt GOOGLE_DRIVE_REFRESH_TOKEN vietējā .env failā?', true)) {
                $envPath = base_path('.env');
                if (file_exists($envPath)) {
                    $envContent = file_get_contents($envPath);
                    if (str_contains($envContent, 'GOOGLE_DRIVE_REFRESH_TOKEN=')) {
                        $envContent = preg_replace('/GOOGLE_DRIVE_REFRESH_TOKEN=.*/', "GOOGLE_DRIVE_REFRESH_TOKEN=\"{$refreshToken}\"", $envContent);
                    } else {
                        $envContent .= "\nGOOGLE_DRIVE_REFRESH_TOKEN=\"{$refreshToken}\"\n";
                    }
                    file_put_contents($envPath, $envContent);
                    $this->info('✓ .env fails ir atjaunināts.');
                }
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Kļūda saņemot Refresh Token: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
