<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the database seeder can be run successfully.
     */
    public function test_database_seeder_runs_successfully(): void
    {
        // Run the seeder
        Artisan::call('db:seed');
        
        // Assert the test user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
        
        // Ensure username was populated (from our previous fix)
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user->username);
    }

    /**
     * Test that the database seeder is idempotent (can be run multiple times).
     */
    public function test_database_seeder_is_idempotent(): void
    {
        // Run the seeder for the first time
        Artisan::call('db:seed');
        
        // Count users
        $initialCount = User::count();
        
        // Run it again - should not throw DuplicateEntry exception
        Artisan::call('db:seed');
        
        // Count should remain the same (no duplicate users)
        $this->assertEquals($initialCount, User::count());
    }
}
