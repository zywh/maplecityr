<?php
namespace Auth0\Tests;

use Auth0\SDK\Auth0Api;

class BlacklistsTest extends ApiTests {

    public function testBlacklistAndGet() {
        $env = $this->getEnv();
        $token = $this->getToken($env, [
            'tokens' => [
                'actions' => ['blacklist']
            ]
        ]);

        $this->domain = $env['DOMAIN'];

        $api = new Auth0Api($token, $env['DOMAIN']);

        $aud = $env["GLOBAL_CLIENT_ID"];
        $jti = 'somerandomJTI' . rand();

        $api->blacklists->blacklist($aud, $jti);

        $all = $api->blacklists->getAll($aud);

        $found = false;
        foreach ($all as $value) {
            if ($value["aud"] === $aud && $value["jti"] === $jti) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'Blacklisted token not found');
    }
}