<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WhmcsController extends Controller
{
    public function index()
    {
        try {
            $apiCredentials = [
                'base_url' => env('WHMCS_BASE_URL'),
                'api_identifier' => env('WHMCS_IDENTIFIER'),
                'api_secret' => env('WHMCS_SECRET_KEY'),
            ];
            $headers =  ['Content-Type' => 'application/json'];

            $client = new Client();
            // Send a GET request to create a record
            $response = $client->get($apiCredentials['base_url'] . 'api-reference/getclients', [
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
            $response = $client->post($apiCredentials['base_url'] . 'api-reference/addclient', [
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
            // Set post values
            $authData = array(
                'identifier' => $apiCredentials['api_identifier'],
                'secret' => $apiCredentials['api_secret'],
                'grant_type' => 'password', // Use 'password' as the grant type for this method
                'username' => $apiCredentials['username'], // Replace with your WHMCS username
                'password' => $apiCredentials['password'], // Replace with your WHMCS password
            );
            // Create a Guzzle HTTP client
            $client = new Client();

            // Send the authentication request
            $response = $client->post($apiCredentials['base_url'] . 'oauth2/token', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'form_params' => $authData,
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
                // Parse the response to extract the access token
                $responseData = json_decode($response->getBody(), true);

                if (isset($responseData['access_token'])) {
                    $accessToken = $responseData['access_token'];

                    return response()->json($accessToken);
                } else {
                    return response()->json(['error' => 'Login error'], 401);
                }
            } else {
                return response()->json(['error' => 'Login error with status code'], $response->getStatusCode());
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
