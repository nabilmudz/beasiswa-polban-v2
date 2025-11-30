<?php
namespace App\Services;

use Kreait\Firebase\Auth as FirebaseAuth;

class FirebaseAuthService
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function verifyIdToken($idToken)
    {
        return $this->firebaseAuth->verifyIdToken($idToken);
    }

    public function getUserByUid($uid)
    {
        return $this->firebaseAuth->getUser($uid);
    }

    public function createUserWithEmailAndPassword($email, $password)
    {
        return $this->firebaseAuth->createUserWithEmailAndPassword($email, $password);
    }
}
