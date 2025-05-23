<?php

namespace Tests\Feature\Admin;

use App\Models\User;

class AdminUserManagementTest extends AdminTestCase
{
    /**
     * Test that admin can view users index page.
     *
     * @return void
     */
    public function test_admin_can_view_users_index()
    {
        // إنشاء بعض المستخدمين
        $users = User::factory()->count(3)->create();
        $this->loginAsAdmin();
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        foreach ($users as $user) {
            $response->assertSee($user->email);
        }
    }
    
    /**
     * Test that admin can view user detail page.
     *
     * @return void
     */
    public function test_admin_can_view_user_detail()
    {
        $user = User::factory()->create();
        $this->loginAsAdmin();
        $response = $this->get(route('admin.users.show', $user->id));
        $response->assertStatus(200);
        $response->assertSee($user->email);
        $response->assertSee($user->name);
    }
    
    /**
     * Test that admin can view edit user form.
     *
     * @return void
     */
    public function test_admin_can_view_edit_user_form()
    {
        $user = User::factory()->create();
        $this->loginAsAdmin();
        $response = $this->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);
        $response->assertSee($user->email);
        $response->assertSee($user->name);
    }
    
    /**
     * Test that admin can update a user.
     *
     * @return void
     */
    public function test_admin_can_update_user()
    {
        $user = User::factory()->create([
            'name' => 'الاسم القديم',
            'email' => 'old@example.com',
            'role' => 'customer',
            'status' => 'active',
        ]);
        $this->loginAsAdmin();
        $newData = [
            'name' => 'اسم جديد',
            'email' => 'new@example.com',
            'role' => 'customer',
            'status' => 'inactive',
        ];
        $response = $this->put(route('admin.users.update', $user->id), $newData);
        $response->assertStatus(302);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'اسم جديد',
            'email' => 'new@example.com',
            'role' => 'customer',
            'status' => 'inactive',
        ]);
    }
    
    /**
     * Test that admin can create a new user.
     *
     * @return void
     */
    public function test_admin_can_create_user()
    {
        $this->loginAsAdmin();
        $userData = [
            'name' => 'مستخدم جديد',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ];
        $response = $this->post(route('admin.users.store'), $userData);
        $response->assertStatus(302);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'مستخدم جديد',
            'role' => 'customer',
        ]);
    }
    
    /**
     * Test that admin can delete a user.
     *
     * @return void
     */
    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();
        $this->loginAsAdmin();
        $response = $this->delete(route('admin.users.destroy', $user->id));
        $response->assertStatus(302);
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
    
    /**
     * Test that admin can toggle user status (activate/deactivate).
     *
     * @return void
     */
    public function test_admin_can_toggle_user_status()
    {
        $user = User::factory()->create(['status' => 'active']);
        $this->loginAsAdmin();
        $response = $this->patch(route('admin.users.toggle-status', $user->id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive',
        ]);
        // Toggle again
        $response = $this->patch(route('admin.users.toggle-status', $user->id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test that admin can filter users by role.
     *
     * @return void
     */
    public function test_admin_can_filter_users_by_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $this->loginAsAdmin();
        $response = $this->get(route('admin.users.index', ['role' => 'admin']));
        $response->assertStatus(200);
        $response->assertSee($admin->email);
        $response->assertDontSee($customer->email);
    }

    /**
     * Test that admin can search for users.
     *
     * @return void
     */
    public function test_admin_can_search_for_users()
    {
        $user1 = User::factory()->create(['name' => 'سامي البحثي']);
        $user2 = User::factory()->create(['name' => 'غير مطابق']);
        $this->loginAsAdmin();
        $response = $this->get(route('admin.users.index', ['search' => 'سامي']));
        $response->assertStatus(200);
        $response->assertSee('سامي البحثي');
        $response->assertDontSee('غير مطابق');
    }
}