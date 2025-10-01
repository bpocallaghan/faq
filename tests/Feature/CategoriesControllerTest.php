<?php

namespace Bpocallaghan\FAQ\Tests;

use Bpocallaghan\FAQ\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_categories_index()
    {
        FaqCategory::create(['name' => 'General']);
        FaqCategory::create(['name' => 'Technical']);

        $response = $this->get('/admin/faqs/categories');

        $response->assertStatus(200);
        $response->assertViewIs('faq::categories.index');
        $response->assertViewHas('items');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $response = $this->get('/admin/faqs/categories/create');

        $response->assertStatus(200);
        $response->assertViewIs('faq::categories.create_edit');
    }

    /** @test */
    public function it_can_store_a_new_category()
    {
        $data = [
            'name' => 'New Category',
        ];

        $response = $this->post('/admin/faqs/categories', $data);

        $this->assertDatabaseHas('faq_categories', [
            'name' => 'New Category',
        ]);
    }

    /** @test */
    public function it_validates_required_name_field()
    {
        $response = $this->post('/admin/faqs/categories', []);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_name_minimum_length()
    {
        $data = [
            'name' => 'Ab', // Less than 3 characters
        ];

        $response = $this->post('/admin/faqs/categories', $data);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_name_maximum_length()
    {
        $data = [
            'name' => str_repeat('A', 256), // More than 255 characters
        ];

        $response = $this->post('/admin/faqs/categories', $data);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_can_show_a_category()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $response = $this->get("/admin/faqs/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertViewIs('faq::categories.show');
        $response->assertViewHas('item', $category);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $response = $this->get("/admin/faqs/categories/{$category->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('faq::categories.create_edit');
        $response->assertViewHas('item', $category);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = FaqCategory::create(['name' => 'Original Name']);

        $updateData = [
            'name' => 'Updated Name',
        ];

        $response = $this->put("/admin/faqs/categories/{$category->id}", $updateData);

        $this->assertDatabaseHas('faq_categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function it_validates_required_name_field_when_updating()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $response = $this->put("/admin/faqs/categories/{$category->id}", []);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = FaqCategory::create(['name' => 'Delete Me']);

        $response = $this->delete("/admin/faqs/categories/{$category->id}");

        $this->assertSoftDeleted('faq_categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_can_delete_category_with_faqs()
    {
        $category = FaqCategory::create(['name' => 'General']);
        
        // Create FAQs for this category
        \Bpocallaghan\FAQ\Models\FAQ::create([
            'question' => 'Question 1',
            'answer' => 'Answer 1',
            'category_id' => $category->id,
        ]);

        $response = $this->delete("/admin/faqs/categories/{$category->id}");

        $this->assertSoftDeleted('faq_categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_handles_empty_categories_list()
    {
        $response = $this->get('/admin/faqs/categories');

        $response->assertStatus(200);
        $response->assertViewIs('faq::categories.index');
        $response->assertViewHas('items');
        
        $items = $response->viewData('items');
        $this->assertCount(0, $items);
    }
}
