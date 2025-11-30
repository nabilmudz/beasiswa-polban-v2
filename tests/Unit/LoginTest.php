<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Reviewer;
class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var Mockery\MockInterface */
    protected $firebaseAuthMock;


    public function test_successful_login_as_mahasiswa()
    {
        $user = User::factory()->create(['email' => 'test@polban.ac.id']);
        $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);

        $this->firebaseAuthMock
            ->shouldReceive('signInWithEmailAndPassword')
            ->with('test@polban.ac.id', 'password')
            ->andReturn((object) ['firebaseUserId' => 'firebase-uid']);

        $this->firebaseAuthMock
            ->shouldReceive('getUser')
            ->with('firebase-uid')
            ->andReturn((object) ['emailVerified' => true]);

        $response = $this->post('/login', [
            'email' => 'test@polban.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/beasiswa');
        $this->assertAuthenticatedAs($user);
        $this->assertEquals(session('auth.role'), 'mahasiswa');
    }

    public function test_successful_login_as_reviewer()
    {
        $user = User::factory()->create(['email' => 'test@polban.ac.id']);
        $reviewer = Reviewer::factory()->create(['user_id' => $user->id]);

        $this->firebaseAuthMock
            ->shouldReceive('signInWithEmailAndPassword')
            ->with('test@polban.ac.id', 'password')
            ->andReturn((object) ['firebaseUserId' => 'firebase-uid']);

        $this->firebaseAuthMock
            ->shouldReceive('getUser')
            ->with('firebase-uid')
            ->andReturn((object) ['emailVerified' => true]);

        $response = $this->post('/login', [
            'email' => 'test@polban.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertEquals(session('auth.role'), 'reviewer');
    }

    public function test_login_fails_with_invalid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Gunakan email polban!']);
    }

    public function test_login_fails_with_unverified_email()
    {
        $user = User::factory()->create(['email' => 'test@polban.ac.id']);

        $this->firebaseAuthMock
            ->shouldReceive('signInWithEmailAndPassword')
            ->with('test@polban.ac.id', 'password')
            ->andReturn((object) ['firebaseUserId' => 'firebase-uid']);

        $this->firebaseAuthMock
            ->shouldReceive('getUser')
            ->with('firebase-uid')
            ->andReturn((object) ['emailVerified' => false]);

        $response = $this->post('/login', [
            'email' => 'test@polban.ac.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Please verify your email before logging in.']);
    }

    public function test_login_fails_with_invalid_password()
    {
        $this->firebaseAuthMock
            ->shouldReceive('signInWithEmailAndPassword')
            ->with('test@polban.ac.id', 'wrong-password')
            ->andThrow(new InvalidPassword());

        $response = $this->post('/login', [
            'email' => 'test@polban.ac.id',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Invalid email or password.']);
    }

    public function test_login_fails_with_email_not_found()
    {
        $this->firebaseAuthMock
            ->shouldReceive('signInWithEmailAndPassword')
            ->with('notfound@polban.ac.id', 'password')
            ->andThrow(new EmailNotFound());

        $response = $this->post('/login', [
            'email' => 'notfound@polban.ac.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Email not found.']);
    }
}
