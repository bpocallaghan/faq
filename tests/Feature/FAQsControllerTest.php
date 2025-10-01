<?php

namespace Bpocallaghan\FAQ\Tests;

use Bpocallaghan\FAQ\Models\FAQ;
use Bpocallaghan\FAQ\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FAQsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_faqs_index()
    {
        $category = FaqCategory::create(['name' => 'General']);
        FAQ::create([
            'question' => 'What is this?',
            'answer' => 'This is a test answer.',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/admin/faqs');

        $response->assertStatus(200);
        $response->assertViewIs('faq::index');
        $response->assertViewHas('items');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        FaqCategory::create(['name' => 'General']);
        FaqCategory::create(['name' => 'Technical']);

        $response = $this->get('/admin/faqs/create');

        $response->assertStatus(200);
        $response->assertViewIs('faq::create_edit');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function it_can_store_a_new_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $data = [
            'question' => 'How does this work?',
            'answer' => 'It works by doing this and that.',
            'category_id' => $category->id,
        ];

        $response = $this->post('/admin/faqs', $data);

        $this->assertDatabaseHas('faqs', [
            'question' => 'How does this work?',
            'answer' => 'It works by doing this and that.',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->post('/admin/faqs', []);

        $response->assertSessionHasErrors(['question', 'answer', 'category_id']);
    }

    /** @test */
    public function it_validates_question_minimum_length()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $data = [
            'question' => 'Wh', // Less than 3 characters
            'answer' => 'Valid answer',
            'category_id' => $category->id,
        ];

        $response = $this->post('/admin/faqs', $data);

        $response->assertSessionHasErrors(['question']);
    }

    /** @test */
    public function it_validates_answer_minimum_length()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $data = [
            'question' => 'Valid question',
            'answer' => 'No', // Less than 5 characters
            'category_id' => $category->id,
        ];

        $response = $this->post('/admin/faqs', $data);

        $response->assertSessionHasErrors(['answer']);
    }

    /** @test */
    public function it_validates_category_exists()
    {
        $data = [
            'question' => 'Valid question',
            'answer' => 'Valid answer',
            'category_id' => 999, // Non-existent category
        ];

        $response = $this->post('/admin/faqs', $data);

        $response->assertSessionHasErrors(['category_id']);
    }

    /** @test */
    public function it_can_show_a_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $response = $this->get("/admin/faqs/{$faq->id}");

        $response->assertStatus(200);
        $response->assertViewIs('faq::show');
        $response->assertViewHas('item', $faq);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $response = $this->get("/admin/faqs/{$faq->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('faq::edit');
        $response->assertViewHas('item', $faq);
        $response->assertViewHas('categories');
    }

    /** @test */
    public function it_can_update_a_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Original question',
            'answer' => 'Original answer',
            'category_id' => $category->id,
        ]);

        $updateData = [
            'question' => 'Updated question',
            'answer' => 'Updated answer',
            'category_id' => $category->id,
        ];

        $response = $this->put("/admin/faqs/{$faq->id}", $updateData);

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Updated question',
            'answer' => 'Updated answer',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_updating()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $response = $this->put("/admin/faqs/{$faq->id}", []);

        $response->assertSessionHasErrors(['question', 'answer', 'category_id']);
    }

    /** @test */
    public function it_can_delete_a_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Delete me',
            'answer' => 'This will be deleted',
            'category_id' => $category->id,
        ]);

        $response = $this->delete("/admin/faqs/{$faq->id}");

        $this->assertSoftDeleted('faqs', ['id' => $faq->id]);
    }

    /** @test */
    public function it_loads_categories_with_faqs()
    {
        $category = FaqCategory::create(['name' => 'General']);
        FAQ::create([
            'question' => 'Question 1',
            'answer' => 'Answer 1',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/admin/faqs');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $this->assertTrue($items->first()->relationLoaded('category'));
    }
}
