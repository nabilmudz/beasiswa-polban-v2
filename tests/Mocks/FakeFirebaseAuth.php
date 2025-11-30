<?php

namespace Tests\Mocks;

use Kreait\Firebase\Auth;

class FakeFirebaseAuth
{
    public function verifyIdToken(string $idToken)
    {
        // Return a simplified mock object
        return (object)[
            'claims' => (object)[
                'get' => fn($claim) => 'google-uid'
            ],
            'uid' => 'google-uid',
        ];
    }

    public function getUser(string $uid)
    {
        return (object)[
            'email' => 'googleuser@example.com'
        ];
    }
}
