<?php

namespace Tests\Feature;

use App\Models\DigitalProduct;
use App\Models\SuspensionAppeal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriticalAdminActionsValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin_platform',
            'email_verified_at' => now(),
        ]);

        $this->seller = User::factory()->create([
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);
    }

    public function test_non_admin_cannot_activate_suspended_user(): void
    {
        $this->seller->update([
            'suspended_at' => now(),
            'suspended_until' => now()->addDays(7),
            'suspend_reason' => 'Test pelanggaran',
        ]);

        $otherSeller = User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($otherSeller)
            ->post(route('platform-admin.users.activate', $this->seller->id));

        $response->assertForbidden();
    }

    public function test_admin_can_activate_suspended_user_with_form_request_validation(): void
    {
        $this->seller->update([
            'suspended_at' => now(),
            'suspended_until' => now()->addDays(7),
            'suspend_reason' => 'Test pelanggaran',
        ]);

        $this->assertTrue($this->seller->isSuspended());

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.activate', $this->seller->id), [
                'activate_reason' => 'User telah menyelesaikan klarifikasi dan diverifikasi ulang.',
            ]);

        $response->assertRedirect(route('platform-admin.users'));
        $response->assertSessionHas('success');

        $this->seller->refresh();
        $this->assertFalse($this->seller->isSuspended());
        $this->assertNull($this->seller->suspended_at);
        $this->assertNull($this->seller->suspended_until);
    }

    public function test_admin_cannot_activate_another_platform_admin(): void
    {
        $anotherAdmin = User::factory()->create(['role' => 'admin_platform']);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.activate', $anotherAdmin->id), [
                'activate_reason' => 'Alasan aktivasi',
            ]);

        $response->assertRedirect(route('platform-admin.users'));
        $response->assertSessionHas('error');
    }

    public function test_activate_user_request_rejects_excessively_long_reason(): void
    {
        $this->seller->update([
            'suspended_at' => now(),
            'suspended_until' => now()->addDays(7),
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.activate', $this->seller->id), [
                'activate_reason' => str_repeat('A', 1005),
            ]);

        $response->assertSessionHasErrors(['activate_reason']);
    }

    public function test_non_admin_cannot_restore_product(): void
    {
        $product = DigitalProduct::create([
            'user_id' => $this->seller->id,
            'title' => 'E-Book Premium Guide',
            'description' => 'Deskripsi panduan',
            'price' => 50000,
            'pricing_type' => 'fixed',
            'platform_type' => 'other',
            'button_text' => 'Beli Sekarang',
            'deliverable_type' => 'other',
            'is_active' => false,
            'takedown_reason' => 'Pelanggaran hak cipta',
            'takedown_at' => now(),
        ]);

        $response = $this->actingAs($this->seller)
            ->post(route('platform-admin.products.restore', $product->id));

        $response->assertForbidden();
    }

    public function test_admin_can_restore_product_with_form_request_validation(): void
    {
        $product = DigitalProduct::create([
            'user_id' => $this->seller->id,
            'title' => 'E-Book Premium Guide',
            'description' => 'Deskripsi panduan',
            'price' => 50000,
            'pricing_type' => 'fixed',
            'platform_type' => 'other',
            'button_text' => 'Beli Sekarang',
            'deliverable_type' => 'other',
            'is_active' => false,
            'takedown_reason' => 'Pelanggaran hak cipta',
            'takedown_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.products.index'))
            ->post(route('platform-admin.products.restore', $product->id), [
                'restore_reason' => 'Seller telah membuktikan lisensi sah untuk aset digital ini.',
            ]);

        $response->assertRedirect(route('platform-admin.products.index'));
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertTrue((bool)$product->is_active);
        $this->assertNull($product->takedown_reason);
        $this->assertNull($product->takedown_at);
    }

    public function test_restore_product_request_rejects_excessively_long_reason(): void
    {
        $product = DigitalProduct::create([
            'user_id' => $this->seller->id,
            'title' => 'E-Book Premium Guide',
            'description' => 'Deskripsi panduan',
            'price' => 50000,
            'pricing_type' => 'fixed',
            'platform_type' => 'other',
            'button_text' => 'Beli Sekarang',
            'deliverable_type' => 'other',
            'is_active' => false,
            'takedown_reason' => 'Pelanggaran hak cipta',
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.products.index'))
            ->post(route('platform-admin.products.restore', $product->id), [
                'restore_reason' => str_repeat('B', 1005),
            ]);

        $response->assertSessionHasErrors(['restore_reason']);
    }

    public function test_non_admin_cannot_approve_appeal(): void
    {
        $appeal = SuspensionAppeal::create([
            'user_id' => $this->seller->id,
            'appeal_reason' => 'Saya memohon pemulihan akun saya.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->seller)
            ->post(route('platform-admin.users.appeals.approve', $appeal->id));

        $response->assertForbidden();
    }

    public function test_admin_can_approve_appeal_with_form_request_validation(): void
    {
        $this->seller->update([
            'suspended_at' => now(),
            'suspended_until' => now()->addDays(30),
            'suspend_reason' => 'Pelanggaran ketentuan',
        ]);

        $appeal = SuspensionAppeal::create([
            'user_id' => $this->seller->id,
            'appeal_reason' => 'Saya memohon pemulihan akun saya.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.appeals.approve', $appeal->id), [
                'admin_notes' => 'Banding diterima setelah evaluasi bukti kooperatif.',
            ]);

        $response->assertRedirect(route('platform-admin.users'));
        $response->assertSessionHas('success');

        $appeal->refresh();
        $this->assertEquals('approved', $appeal->status);
        $this->assertEquals('Banding diterima setelah evaluasi bukti kooperatif.', $appeal->admin_notes);
        $this->assertNotNull($appeal->resolved_at);

        $this->seller->refresh();
        $this->assertFalse($this->seller->isSuspended());
    }

    public function test_approve_appeal_request_rejects_excessively_long_notes(): void
    {
        $appeal = SuspensionAppeal::create([
            'user_id' => $this->seller->id,
            'appeal_reason' => 'Banding saya.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.appeals.approve', $appeal->id), [
                'admin_notes' => str_repeat('C', 1005),
            ]);

        $response->assertSessionHasErrors(['admin_notes']);
    }

    public function test_cannot_approve_already_processed_appeal(): void
    {
        $appeal = SuspensionAppeal::create([
            'user_id' => $this->seller->id,
            'appeal_reason' => 'Banding saya.',
            'status' => 'rejected',
            'admin_notes' => 'Ditolak sebelumnya.',
            'resolved_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('platform-admin.users'))
            ->post(route('platform-admin.users.appeals.approve', $appeal->id), [
                'admin_notes' => 'Coba approve lagi',
            ]);

        $response->assertRedirect(route('platform-admin.users'));
        $response->assertSessionHas('error');
    }
}
