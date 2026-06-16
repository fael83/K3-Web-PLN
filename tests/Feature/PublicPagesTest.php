<?php
namespace Tests\Feature;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_profil_page_loads(): void
    {
        $this->get('/profil')->assertStatus(200);
    }

    public function test_apd_page_loads(): void
    {
        $this->get('/apd')->assertStatus(200);
    }

    public function test_admin_redirects_when_unauthenticated(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }
}
