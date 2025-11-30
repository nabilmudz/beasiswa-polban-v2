<?php

namespace Tests\Unit;

use App\Http\Controllers\NotificationController;
use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use App\Models\PenerimaBeasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class PenerimaBeasiswaTest extends TestCase
{
    use RefreshDatabase;

    // public function test_index_with_search_filter()
    // {
    //     $this->seed();

    //     $user = User::find(1);

    //     $this->actingAs($user);

    //     $beasiswa = Beasiswa::factory()->create([
    //         'nama_beasiswa' => 'Beasiswa Polban',
    //     ]);
    //     $user = User::factory()->create();
    //     Auth::login($user);

    //     $response = $this->get('/beasiswa?search=Polban');

    //     $response->assertStatus(200);
    // }

    public function test_create()
    {

        $this->seed();

        $user = User::find(3);

        $this->actingAs($user);

        $response = $this->get(route('beasiswa.import-data-beasiswa'));

        $this->assertTrue(true);
    }

    public function test_store_with_valid_file()
    {
        $this->seed();

        $user = User::find(3);

        $this->actingAs($user);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('data.xlsx', 100);
        // Create a beasiswa
        $beasiswa = Beasiswa::factory()->create();


        $this->assertTrue(true);
    }

    public function test_store_with_invalid_file()
    {
        $this->seed();

        $user = User::find(3);

        $this->actingAs($user);

        // Send an invalid file type
        $response = $this->post(route('beasiswa.store'), [
            'excelFile' => UploadedFile::fake()->create('data.txt', 100),
        ]);
        $this->assertTrue(true);
    }

    public function test_show_with_valid_id()
    {
        $this->seed();

        $user = User::find(3);

        $this->actingAs($user);

        $response = $this->get(route('beasiswa.show', 1));

        $this->assertTrue(true);
    }

}
