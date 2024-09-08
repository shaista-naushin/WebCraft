<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Component;
use Illuminate\Support\Facades\Validator;

class BlocksControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $user->role = 'admin';
        $this->actingAs($user);
    }

    public function testCreateValidationFails()
    {
        $this->withoutMiddleware();

        $data = ['name' => '', 'unique_id' => '']; // Required fields are empty
        $validator = Validator::make($data, [
            'name' => 'required',
            'unique_id' => 'required'
        ]);

        $this->assertTrue($validator->fails());
    }

    public function testCreateValidationPasses()
    {
        $this->withoutMiddleware();

        $data = ['name' => 'Sample Block', 'unique_id' => 123];
        $validator = Validator::make($data, [
            'name' => 'required',
            'unique_id' => 'required'
        ]);

        $this->assertFalse($validator->fails());
    }

    public function testCreateComponentSuccessfully()
    {
        $response = $this->call('POST', '/admin/blocks/create', [
            'name' => 'Sample Block',
            'unique_id' => 123,
            'component_js' => 'console.log("hello")',
            'settings_js' => 'console.log("settings")'
        ]);

        $response->assertRedirect('/admin/blocks/list');
    }

    public function testInstallValidationFails()
    {
        $this->withoutMiddleware();

        $data = ['component' => null]; // Required file is missing
        $validator = Validator::make($data, ['component' => 'required|file']);

        $this->assertTrue($validator->fails());
    }

    public function testUpdateComponentSuccessfully()
    {
        $component = new Component();
        $component->user_id = auth()->id();
        $component->name = 'Sample component';
        $component->unique_id = 234;
        $component->save();

        $response = $this->call('POST', '/admin/blocks/edit/' . $component->id, [
            'name' => 'Updated Block',
            'unique_id' => 123
        ]);

        $response->assertRedirect('/admin/blocks/list');
    }

    public function testDestroyComponentSuccessfully()
    {
        $component = new Component();
        $component->user_id = auth()->id();
        $component->name = 'Sample component';
        $component->unique_id = 234;
        $component->save();

        $response = $this->call('GET', '/admin/blocks/delete/' . $component->id);
        $response->assertRedirect();
    }
}
