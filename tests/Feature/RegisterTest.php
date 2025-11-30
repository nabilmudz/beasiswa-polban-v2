<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FirebaseAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Mockery;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
     public function it_should_fail_if_email_is_not_polban()
     {
         $response = $this->postJson('/register', [
             'email' => 'user@gmail.com',
             'password' => 'password123',
             'password_confirmation' => 'password123',
         ]);

         $response->assertStatus(422);
         $response->assertJsonValidationErrors(['email']);
         $response->assertJson(['errors' => ['email' => ['Gunakan email polban!']]]);
     }

     /** @test */
     public function it_should_fail_if_password_confirmation_is_incorrect()
     {
         $response = $this->postJson('/register', [
             'email' => 'user@polban.ac.id',
             'password' => 'password123',
             'password_confirmation' => 'wrongpassword',
         ]);

         $response->assertStatus(409);
         $response->assertJson(['error' => 'Konfirmasi password salah']);
     }

    /** @test */
    public function it_registers_user_with_email_and_password_method()
    {
        // Prepare mock data
        $email = 'example6@polban.ac.id';
        $password = 'password123';

        // Mock FirebaseAuthService
        $firebaseAuth = Mockery::mock(FirebaseAuthService::class);

        // Mock createUserWithEmailAndPassword method
        $firebaseAuth->shouldReceive('createUserWithEmailAndPassword')
            ->with($email, $password)
            ->andReturn((object) ['email' => $email]); // Return fake user object

        $firebaseAuth->shouldReceive('sendEmailVerificationLink')
            ->with($email)
            ->andReturn(true);

        // Bind the mock instance to the container
        $this->app->instance(FirebaseAuthService::class, $firebaseAuth);

        // Simulate POST request to register endpoint
        $response = $this->postJson('/register', [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        // Assert response
        $response->assertStatus(302) // Redirect after successful registration
            ->assertRedirect(route('auth.register-information', ['id' => 1]));

        // Assert user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_returns_error_if_email_already_exists()
    {
        // Mock FirebaseAuthService
        $firebaseAuth = Mockery::mock(FirebaseAuthService::class);

        // Simulate FirebaseEmailExists exception
        $firebaseAuth->shouldReceive('createUserWithEmailAndPassword')
            ->with('example@polban.ac.id', 'password123')
            ->andThrow(new FirebaseEmailExists('Email already exists'));

        $this->app->instance(FirebaseAuthService::class, $firebaseAuth);

        // Attempt to register with an existing email
        $response = $this->postJson('/register', [
            'email' => 'example@polban.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert correct error response
        $response->assertStatus(409)
            ->assertJson(['error' => 'Email already exists in Firebase']);
    }

    /** @test */
    public function it_inserts_mahasiswa_data_successfully()
    {
        // Seed the database with necessary data
        $this->seed();

        // Create a user
        $user = User::factory()->create([
            'id' => 11,
            'nama_depan' => 'Old First Name',
            'nama_belakang' => 'Old Last Name',
            'jenis_kelamin' => 'Pria',
        ]);

        // Define request data
        $data = [
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'jenis_kelamin' => 'Pria',
            'nim' => '123456782',
            'semester' => 3,
            'tgl_lahir' => '2001-01-01',
            'prodi_id' => 1, // Ensure this exists in 'prodi' table or use a factory to create it
            'no_hp' => '081234567890',
            'angkatan' => 2021,
        ];

        // Make the POST request
        $response = $this->post(route('mahasiswa.insert', ['id' => $user->id]), $data);

        // Assert the response redirects to the login page
        $response->assertRedirect('/login');

        // Assert that the Mahasiswa record exists in the database
        $this->assertDatabaseHas('mahasiswa', [
            'user_id' => $user->id,
            'nim' => '123456782',
            'semester' => 3,
            'tgl_lahir' => '2001-01-01',
            'prodi_id' => 1,
            'no_hp' => '081234567890',
            'angkatan' => 2021,
        ]);

        // Assert that the User record has been updated
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'jenis_kelamin' => 'Pria',
        ]);
    }
}
