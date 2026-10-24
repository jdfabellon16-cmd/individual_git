<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Gives this controller the ok(), success() and error() response helpers
    use ApiResponses;

    // User Registration: creates the account and logs the user in immediately
    public function register(RegisterUserRequest $request)
    {
        // Create the user. The password is hashed (bcrypt) before it is stored.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a JWT for the new user using the "api" guard
        $token = Auth::guard('api')->login($user);

        if (!is_string($token)) {
            return $this->error('Unable to generate authentication token', 500);
        }

        return $this->ok('Registered successfully', $this->formatToken($token));
    }

    // Login: checks the credentials and returns a token when they are correct
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // attempt() returns false when the email or password is wrong
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->ok('Login successful', $this->formatToken($token));
    }

    // Verifying/reading an existing token: returns the user that owns the Bearer token
    public function me()
    {
        return $this->ok('User retrieved', Auth::guard('api')->user());
    }

    /**
     * This maps the raw token string to access_token, defines its authentication
     * standard as a bearer token, and dynamically calculates its lifespan (expires_in)
     * by fetching the API's configured Time-To-Live (TTL) in minutes and multiplying it
     * by 60 to convert it into seconds. This structured format complies with standard
     * OAuth 2.0 specifications, allowing frontend applications or mobile clients to
     * easily read the token, know how to attach it to future API request headers, and
     * track exactly when it will expire.
     */
    protected function formatToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
                'expires_in'   => config('jwt.ttl') * 60,
            ];
    }

    // Revoking Authentication Tokens (invalidating/blacklisting):
    // refresh() blacklists the current token and returns a brand-new one
    public function refresh()
    {
        return $this->ok('Token refreshed', $this->formatToken(Auth::guard('api')->viaRemember()));
    }

    // Logout: blacklists the current token so it can never be used again
    public function logout()
    {
        Auth::guard('api')->logout();

        return $this->ok('Successfully logged out', null);
    }
}