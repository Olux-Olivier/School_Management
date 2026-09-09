<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SchoolYearAdministrationTest extends TestCase
{
    public function test_all_school_year_routes_are_in_administration(): void
    {
        foreach (['index', 'create', 'store', 'show', 'edit', 'update', 'toggle-status'] as $action) {
            $route = app('router')->getRoutes()->getByName('annees.'.$action);
            $this->assertNotNull($route);
            $this->assertStringStartsWith('administration/annees-scolaires', $route->uri());
            $this->assertContains('auth:admin', $route->gatherMiddleware());
            $this->assertContains('admin', $route->gatherMiddleware());
        }
    }

    public function test_school_session_does_not_grant_administration_access(): void
    {
        $this->actingAs(new User(['id' => 1, 'type' => 'Admin']), 'web');
        $this->get(route('annees.create'))->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_school_year_management(): void
    {
        $this->actingAs(new User(['id' => 1, 'type' => 'Agent']), 'admin');
        $this->get(route('annees.create'))->assertForbidden();
    }

    public function test_admin_can_open_school_year_creation(): void
    {
        $this->actingAs(new User(['id' => 1, 'nom' => 'Admin', 'type' => 'Admin']), 'admin');
        $this->get(route('annees.create'))->assertOk()->assertSee('Synergie Admin');
    }
}
