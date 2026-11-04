<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountSessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_page_masks_raw_session_id(): void
    {
        $user = User::factory()->create();

        // Create 2 sessions for this user
        $rawSessionId1 = 'raw_session_id_device_alpha_12345';
        $rawSessionId2 = 'raw_session_id_device_beta_67890';

        DB::table('sessions')->insert([
            [
                'id' => $rawSessionId1,
                'user_id' => $user->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload' => 'payload1',
                'last_activity' => time(),
            ],
            [
                'id' => $rawSessionId2,
                'user_id' => $user->id,
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0)',
                'payload' => 'payload2',
                'last_activity' => time() - 3600,
            ],
        ]);

        $response = $this->actingAs($user)->get(route('admin.account'));

        $response->assertStatus(200);
        // Ensure the raw database session key of the secondary device is NOT rendered in plaintext in the DOM
        $response->assertDontSee($rawSessionId2);
    }

    public function test_user_can_revoke_other_session_using_secure_hash(): void
    {
        $user = User::factory()->create();

        $currentSessionId = 'current_session_token_111';
        $otherSessionId = 'secondary_device_session_222';

        DB::table('sessions')->insert([
            [
                'id' => $currentSessionId,
                'user_id' => $user->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64)',
                'payload' => 'payload1',
                'last_activity' => time(),
            ],
            [
                'id' => $otherSessionId,
                'user_id' => $user->id,
                'ip_address' => '192.168.1.50',
                'user_agent' => 'Mozilla/5.0 (Android 11; Mobile)',
                'payload' => 'payload2',
                'last_activity' => time() - 1000,
            ],
        ]);

        $secureHash = hash_hmac('sha256', $otherSessionId, (string) config('app.key'));

        $response = $this->actingAs($user)
            ->withSession(['_token' => 'test_token'])
            ->delete(route('admin.account.session.revoke', $secureHash));

        $response->assertRedirect(route('admin.account'));
        $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId]);
        $this->assertDatabaseHas('sessions', ['id' => $currentSessionId]);
    }

    public function test_user_can_revoke_all_other_sessions(): void
    {
        $user = User::factory()->create();

        $otherSessionId1 = 'secondary_device_session_444';
        $otherSessionId2 = 'secondary_device_session_555';

        DB::table('sessions')->insert([
            [
                'id' => $otherSessionId1,
                'user_id' => $user->id,
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mobile',
                'payload' => 'payload2',
                'last_activity' => time() - 500,
            ],
            [
                'id' => $otherSessionId2,
                'user_id' => $user->id,
                'ip_address' => '192.168.1.2',
                'user_agent' => 'Tablet',
                'payload' => 'payload3',
                'last_activity' => time() - 1000,
            ],
        ]);

        $this->assertDatabaseCount('sessions', 2);

        $response = $this->actingAs($user)->delete(route('admin.account.sessions.revoke-all'));

        $response->assertRedirect(route('admin.account'));
        $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId1]);
        $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId2]);
    }
}
