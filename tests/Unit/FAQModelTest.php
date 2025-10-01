<?php

namespace Bpocallaghan\FAQ\Tests;

use Bpocallaghan\FAQ\Models\FAQ;
use Bpocallaghan\FAQ\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FAQModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);
        
        $faq = FAQ::create([
            'question' => 'What is this?',
            'answer' => 'This is a test answer.',
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(FAQ::class, $faq);
        $this->assertEquals('What is this?', $faq->question);
        $this->assertEquals('This is a test answer.', $faq->answer);
        $this->assertEquals($category->id, $faq->category_id);
    }

    /** @test */
    public function it_has_validation_rules()
    {
        $rules = FAQ::$rules;

        $this->assertArrayHasKey('question', $rules);
        $this->assertArrayHasKey('answer', $rules);
        $this->assertArrayHasKey('category_id', $rules);
        $this->assertStringContainsString('required', $rules['question']);
        $this->assertStringContainsString('required', $rules['answer']);
        $this->assertStringContainsString('required', $rules['category_id']);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $faq->delete();

        $this->assertSoftDeleted('faqs', ['id' => $faq->id]);
    }

    /** @test */
    public function it_can_restore_soft_deleted_faq()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $faq->delete();
        $faq->restore();

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Test question',
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(FaqCategory::class, $faq->category);
        $this->assertEquals($category->id, $faq->category->id);
    }

    /** @test */
    public function it_can_set_list_order()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
            'list_order' => 5,
        ]);

        $this->assertEquals(5, $faq->list_order);
    }

    /** @test */
    public function it_generates_answer_summary()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => '<p>This is a very long answer that should be truncated when generating the summary.</p>',
            'category_id' => $category->id,
        ]);

        $this->assertStringContainsString('This is a very long answer that should be truncated when generating...', $faq->answer_summary);
    }

    /** @test */
    public function it_tracks_view_counts()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $this->assertEquals(0, $faq->total_read);
        $this->assertEquals(0, $faq->helpful_yes);
        $this->assertEquals(0, $faq->helpful_no);
    }

    /** @test */
    public function it_can_increment_view_counts()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $faq->increment('total_read');
        $faq->increment('helpful_yes');
        $faq->increment('helpful_no');

        $this->assertEquals(1, $faq->fresh()->total_read);
        $this->assertEquals(1, $faq->fresh()->helpful_yes);
        $this->assertEquals(1, $faq->fresh()->helpful_no);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $faq = new FAQ();
        $this->assertEquals('faqs', $faq->getTable());
    }

    /** @test */
    public function it_guards_id_field()
    {
        $category = FaqCategory::create(['name' => 'General']);
        $faq = FAQ::create([
            'id' => 999,
            'question' => 'Test question',
            'answer' => 'Test answer',
            'category_id' => $category->id,
        ]);

        $this->assertNotEquals(999, $faq->id);
    }
}
