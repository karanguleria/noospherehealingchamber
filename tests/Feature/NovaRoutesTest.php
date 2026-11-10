<?php
namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;
class NovaRoutesTest extends TestCase
{
    public function test_nova_change_password_page(): void
    {
        $admin = User::where('type_id',3)->firstOrFail();
        $r = $this->actingAs($admin)->get('/nova/change-password');
        dump($r->status(), $r->exception?->getMessage());
    }
    public function test_nova_profile_page(): void
    {
        $admin = User::where('type_id',3)->firstOrFail();
        $r = $this->actingAs($admin)->get('/nova/profile');
        dump($r->status(), $r->exception?->getMessage());
    }
    public function test_legacy_change_password_redirect(): void
    {
        $admin = User::where('type_id',3)->firstOrFail();
        $r = $this->actingAs($admin)->get('/change-password');
        dump($r->status(), $r->headers->get('location'));
    }
}
