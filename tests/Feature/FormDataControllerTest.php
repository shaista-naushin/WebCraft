<?php

namespace Tests\Feature;

use App\Models\FormData;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class FormDataControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_form_data()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create pages and form data
        $pages = Page::factory()->count(2)->create(['user_id' => $user->id]);
        $formData = FormData::factory()->count(3)->create(['page_id' => $pages->first()->id]);

        // Hit the route
        $response = $this->get(route('form-data.index'));

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('admin.form-data.list');
        $response->assertViewHas('formData', function ($data) use ($formData) {
            return $data->contains($formData->first());
        });
    }

    public function test_get_all_form_data_empty_for_other_users()
    {
        // Create two users
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        // Create pages and form data for the other user
        $pages = Page::factory()->count(2)->create(['user_id' => $otherUser->id]);
        $formData = FormData::factory()->count(3)->create(['page_id' => $pages->first()->id]);

        // Hit the route
        $response = $this->get(route('form-data.index'));

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('admin.form-data.list');
        $response->assertViewHas('formData', function ($data) {
            return count($data) === 0;
        });
    }

    public function test_destroy_form_data()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a page and form data
        $page = Page::factory()->create(['user_id' => $user->id]);
        $formData = FormData::factory()->create(['page_id' => $page->id]);

        // Hit the delete route
        $response = $this->get(route('form-data.destroy', $formData->id));

        // Assertions
        $response->assertRedirect();
        $response->assertSessionHas('success_msg', 'Data deleted successfully');
        $this->assertDatabaseMissing('form_data', ['id' => $formData->id]);
    }

    public function test_destroy_form_data_not_found()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Hit the delete route with a non-existent ID
        $response = $this->get(route('form-data.destroy', 999));

        // Assertions
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Data not found']);
    }
}
