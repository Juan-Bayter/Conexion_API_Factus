<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\FactusToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AuthFactusService
{
    protected $url = 'https://api-sandbox.factus.com.co/oauth/token';
    protected $token = null;

    protected $clientId;
    protected $clientSecret;
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->clientId = config('services.factus.client_id');
        $this->clientSecret = config('services.factus.client_secret');
        $this->email = config('services.factus.email');
        $this->password = config('services.factus.password');
    }

    public function getValidToken()
    {
        $bdToken = FactusToken::query()->first();

        if (!$bdToken) {

            $this->createToken();
        } elseif ($bdToken->expiredToken()) {

            $this->refreshToken($bdToken);
        } else {
            $this->token = $bdToken->access_token;
        }

        return $this->token;
    }

    public function createToken()
    {
        // Parámetros necesarios para la solicitud
        $response = Http::asForm()->post($this->url, [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->email, // username/email
            'password' => $this->password,
        ]);

        // Verificamos si la respuesta es exitosa
        if ($response->successful()) {
            // Acceder a los datos del token
            $data = $response->json();
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token'];
            $expiresIn = $data['expires_in'];

            FactusToken::create([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_at' => Carbon::now()->addSeconds($expiresIn),
            ]);

            $this->token = $accessToken;
        } else {

            return response()->json([
                'error' => $response->json()['error'],
                'message' => $response->json()['message']
            ]);
        }
    }

    public function refreshToken($bdToken)
    {
        // Crear una instancia del cliente Guzzle
        $client = new Client();

        // Definir los parámetros para la solicitud
        $data = [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $bdToken->refresh_token,
            ]
        ];

        try {
            // Realizar la solicitud POST
            $response = $client->post($this->url, $data);

            // Obtener el cuerpo de la respuesta
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Verificar que la respuesta contenga el token
            if (isset($responseBody['access_token'])) {

                $accessToken = $responseBody['access_token'];
                $refreshToken = $responseBody['refresh_token'];
                $expiresIn = $responseBody['expires_in'];

                $this->token = $accessToken;

                $bdToken->update([
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_at' => Carbon::now()->addSeconds($expiresIn),
                ]);
            }
        } catch (RequestException $e) {
            // Manejar los errores de la solicitud (ej. conexión fallida)
            return response()->json([
                'error' => 'Error en la solicitud',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
