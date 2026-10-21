<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create(['name' => 'email', 'value' => 'boss@cafe.test']);
        Setting::create(['name' => 'tax', 'value' => '0.12']);
    }

    private function makeUser(string $role, string $status = 'approved', string $email = null): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email ?? $role . uniqid() . '@cafe.test',
            'password' => 'password123',
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function test_registration_creates_pending_user_redirected_to_waiting_page(): void
    {
        $res = $this->post('/register', [
            'name' => 'New Guy',
            'email' => 'new@cafe.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $res->assertRedirect(route('account.pending'));
        $this->assertDatabaseHas('users', ['email' => 'new@cafe.test', 'status' => 'pending']);
    }

    public function test_admin_email_registration_is_auto_approved(): void
    {
        $this->post('/register', [
            'name' => 'Boss',
            'email' => 'boss@cafe.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'boss@cafe.test', 'role' => 'admin', 'status' => 'approved']);
    }

    public function test_pending_user_is_blocked_everywhere(): void
    {
        $pending = $this->makeUser('cashier', 'pending');

        $this->actingAs($pending)->get('/')->assertRedirect(route('account.pending'));
        $this->actingAs($pending)->get('/admin/inventory')->assertRedirect(route('account.pending'));
        $this->actingAs($pending)->get('/admin/dashboard')->assertRedirect(route('account.pending'));
        $this->actingAs($pending)->get('/account/pending')->assertOk()->assertSee('Waiting for Approval');
    }

    public function test_rejected_user_sees_rejected_message(): void
    {
        $rejected = $this->makeUser('cashier', 'rejected');
        $this->actingAs($rejected)->get('/account/pending')->assertOk()->assertSee('Account Not Approved');
    }

    public function test_cashier_access(): void
    {
        $cashier = $this->makeUser('cashier');

        $this->actingAs($cashier)->get('/')->assertOk();
        $this->actingAs($cashier)->get('/admin/inventory')->assertRedirect(route('home'));
        $this->actingAs($cashier)->get('/admin/dashboard')->assertRedirect(route('home'));
        $this->actingAs($cashier)->get('/admin/users')->assertRedirect(route('home'));
    }

    public function test_stock_clerk_access(): void
    {
        $clerk = $this->makeUser('stock_clerk');

        $this->actingAs($clerk)->get('/admin/inventory')->assertOk()->assertSee('Logout');
        $this->actingAs($clerk)->get('/')->assertRedirect(route('admin.inventory'));
        $this->actingAs($clerk)->get('/admin/dashboard')->assertRedirect(route('admin.inventory'));
    }

    public function test_admin_can_view_approve_reject_and_change_roles(): void
    {
        $admin = $this->makeUser('admin');
        $pending = $this->makeUser('cashier', 'pending');

        $this->actingAs($admin)->get('/admin/users')->assertOk()->assertSee($pending->email);

        $this->actingAs($admin)->post("/admin/users/{$pending->id}/approve", ['role' => 'stock_clerk'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $pending->id, 'role' => 'stock_clerk', 'status' => 'approved']);

        $this->actingAs($admin)->put("/admin/users/{$pending->id}/role", ['role' => 'cashier']);
        $this->assertDatabaseHas('users', ['id' => $pending->id, 'role' => 'cashier']);

        $this->actingAs($admin)->post("/admin/users/{$pending->id}/reject");
        $this->assertDatabaseHas('users', ['id' => $pending->id, 'status' => 'rejected']);

        // Invalid role is refused
        $this->actingAs($admin)->put("/admin/users/{$pending->id}/role", ['role' => 'superuser'])
            ->assertSessionHasErrors('role');
    }

    public function test_admin_cannot_lock_themselves_out(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->put("/admin/users/{$admin->id}/role", ['role' => 'cashier']);
        $this->actingAs($admin)->post("/admin/users/{$admin->id}/reject");
        $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin', 'status' => 'approved']);
    }

    public function test_login_redirects_by_role(): void
    {
        $clerk = $this->makeUser('stock_clerk', 'approved', 'clerk@cafe.test');
        $this->post('/login', ['email' => 'clerk@cafe.test', 'password' => 'password123'])
            ->assertRedirect(route('admin.inventory'));
    }
}
