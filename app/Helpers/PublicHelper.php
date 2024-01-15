<?php

namespace App\Helpers;

use App\Exceptions\AuthenticationException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;

class PublicHelper 
{
    // get jwt info from header
    public function GetRawJWT()
    {

        // dd($_SERVER);
        // check if header exists
        if(empty($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new Exception('authorization header not found');
        }

        // check if bearer token exists
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new Exception('token not found');
        }

        // extract token
        $jwt = $matches[1];
        if (!$jwt) {
            throw new Exception('could not extract token');
        }

        return $jwt;
    }

    public function DecodeRawJWT($jwt)
    {
        // use secret key to decode token
        $secretKey  = env('JWT_KEY');
        try {
            $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
            $now = new DateTimeImmutable();
        } catch(Exception $e) {
            throw new Exception('unauthorized');
        }

        return $token;
    }

    public function GetAndDecodeJWT()
    {
        $jwt = $this->GetRawJWT();
        $token = $this->DecodeRawJWT($jwt);

        return $token;
    }
}