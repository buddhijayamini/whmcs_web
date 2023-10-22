<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WhmcsController extends Controller
{
    public function index()
    {
        try {
            $apiCredentials = [
                'base_url' => env('WHMCS_BASE_URL'),
                'username' => env('WHMCS_USERNAME'),
                'password' => env('WHMCS_PASSWORD'),
                'action' => 'GetClients',
                'search' => 'example.com',
                'responsetype' => 'json'
            ];

            $headers =  [
            'Content-Type' => 'application/json',
            'username' => env('WHMCS_USERNAME'),
            'password' => env('WHMCS_PASSWORD'),
            'action' => 'GetClients',
            'search' => 'example.com',
            'responsetype' => 'json'
        ];

            $client = new Client();
            // Send a GET request to create a record
            $response = $client->get($apiCredentials['base_url'] . 'includes/api.php', [
                'headers' => $headers
            ]);

            if ($response->getStatusCode() === 200) {
                // Record created successfully
                return response()->json($response);
            } else {
                // Handle error
                return response()->json('Error getting record');
            }
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage());
        }
    }

    public function create()
    {
        return view('addClient');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $apiCredentials = [
                'base_url' => env('WHMCS_BASE_URL'),
                'api_identifier' => env('WHMCS_IDENTIFIER'),
                'api_secret' => env('WHMCS_SECRET_KEY'),
                'api_secret' => env('WHMCS_SECRET_KEY'),
                'username' => env('WHMCS_USERNAME'),
                'password' => env('WHMCS_PASSWORD'),
            ];

            $token = $this->whmcsConn();

            if (!$token) {
                return response()->json(['error' => 'Authentication error'], 401);
            }

            $headers =  [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ];

            $postData = [
                'action' => 'AddClient',
                'username' => $request['username'],
                'password' => $request['password'],
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'address1' => $request['address1'],
                'city' => $request['city'],
                'state' => $request['state'],
                'postcode' => $request['postcode'],
                'country' => $request['country'],
                'phonenumber' => $request['phonenumber'],
                'password2' => $request['password2'],
                'clientip' => $request['clientip'],
                'responsetype' => "json",
            ];

            $client = new Client();

            // Send a POST request to create a record
            $response = $client->post($apiCredentials['base_url'] . 'includes/api.php', [
                'headers' => $headers,
                'json' => $postData,
            ]);


            if ($response->getStatusCode() === 201) {
                // Record created successfully
                $responseData = json_decode($response->getBody(), true);
                // Process the response data as needed
                return response()->json($responseData);
            } else {
                // Handle error
                return response()->json(['error' => 'Error creating client'], $response->getStatusCode());
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    private function whmcsConn()
    {
        // WHMCS API credentials
        $apiCredentials = [
            'base_url' => env('WHMCS_BASE_URL'),
            'api_identifier' => env('WHMCS_IDENTIFIER'),
            'api_secret' => env('WHMCS_SECRET_KEY'),
            'username' => env('WHMCS_USERNAME'),
            'password' => env('WHMCS_PASSWORD'),
        ];

        try {

            // Create a Guzzle HTTP client
            $client = new Client();

            // Authentication request data
            $authData = [
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $apiCredentials['username'],
                    'password' => $apiCredentials['password'],
                ],
                'auth' => [$apiCredentials['api_identifier'], $apiCredentials['api_secret']],
            ];

            // Send the authentication request
            $response = $client->post($apiCredentials['base_url'], $authData);

            // Check the response status code
            if ($response->getStatusCode() == 200) {
                $responseBody = $response->getBody()->getContents();
                $accessToken = json_decode($responseBody, true)['access_token'];

                // You now have the access token for further API requests
                return response()->json($accessToken);
            } else {
                return response()->json(['error' => 'Authentication request failed with status code: ' . $response->getStatusCode()]);
            }
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
