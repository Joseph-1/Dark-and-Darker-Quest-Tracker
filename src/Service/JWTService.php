<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    // Allow to generate a token
    /**
     * Generation of JWT
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        // Allow to verify that $validity isn't negative and add a time where the token expired
        if ($validity > 0){
            $now = new DateTimeImmutable();
            $expiration = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }

        //  encode in base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // Clean the string to replace +, /, = for comply with the standard
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // Generate the signature
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true); // //!\\
        $base64Signature = base64_encode($signature);

        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // Create the token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $signature;

        return $jwt;
    }

    // Allow to verify if string is valid in terms of content
    public function isValid(string $token): bool
    {
        // Verify if our token corresponds to a particular regular expression
        // Return 1 if string corresponds else return 0
        return preg_match(
                '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
                $token
            ) === 1;

    }

    // Allow to retrieve Payload Token
    public function getPayload(string $token)
    {
        $array = explode('.', $token);

        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    // Allow to retrieve Header Token
    public function getHeader(string $token)
    {
        // Deconstruuct Token
        $array = explode('.', $token); // explode() permet qu'à chaque fois qu'il rencontre un '.' créer une ligne dans le tableau

        // Decode Header
        $header = json_decode(base64_decode($array[0]), true); // Va décoder la première ligne du tableau qui correspond au header et est en position [0]

        return $header;
    }

    // Allow to know if token has expired
    public function isExpired(string $token): bool
    {
        // Retrieve the Payload
        $payload = $this->getPayload($token);

        // Allow to know which day / hour
        $now = new DateTimeImmutable();

        // Test if expiration is lower to the Timestamp now
        return $payload['exp'] < $now->getTimestamp();
    }

    // Allow to verify the current token
    public function check(string $token, string $secret): bool
    {
        // Retrieve Header and Payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // Generate a verification Token
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}
