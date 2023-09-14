<?php

namespace App\Service;

class JWTService
{
    public function generateJWT(array $header, array $payload, string $secret, int $validity = 3600):string
    {
        if ($validity > 0) {   
            $now = new \DateTimeImmutable();
            $esp = $now->getTimestamp() + $validity;
    
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $esp;
        }  



        // Encode base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        //Signature
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header. '.'. $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        //Token

        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

 
        return $jwt;
    }

    // Check JWT
    public function isValid(string $token):bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    // Token Payload
    public function getPayload(string $token): array
    {
        // On démonte le token
        $array = explode('.', $token);

        // On décode le Payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    // Token Header

    public function getHeader(string $token): array
    {
        // On démonte le token
        $array = explode('.', $token);

        // On décode le Header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    // Check Expiration Time
    public function isExpired(string $token):bool
    {
        $payload = $this->getPayload($token);
        $now = new \DateTimeImmutable();
        return $payload['exp'] < $now->getTimestamp();

    }

    // Check Signature
    public function isSignatureValid(string $token, $secret):bool
    {
        // On récupère le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // On régénère un token
        $verifToken = $this->generateJWT($header, $payload, $secret, 0);
     
        return $token === $verifToken;

    }

    

}