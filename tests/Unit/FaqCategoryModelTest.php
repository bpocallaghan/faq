<?php

namespace Bpocallaghan\FAQ\Tests;

use Bpocallaghan\FAQ\Models\FAQ;
use Bpocallaghan\FAQ\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FaqCategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category()
    {
        $category = FaqCategory::create([
            'name' => 'General Questions',
        ]);

        $this->assertInstanceOf(FaqCategory::class, $category);
        $this->assertEquals('General Questions', $category->name);
    }

    /** @test */
    public function it_has_validation_rules()
    {
        $rules = FaqCategory::$rules;

        $this->assertArrayHasKey('name', $rules);
        $this->assertStringContainsString('required', $rules['name']);
        $this->assertStringContainsString('min:3', $rules['name']);
        $this->assertStringContainsString('max:255', $rules['name']);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $category->delete();

        $this->assertSoftDeleted('faq_categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_can_restore_soft_deleted_category()
    {
        $category = FaqCategory::create(['name' => 'General']);

        $category->delete();
        $category->restore();

        $this->assertDatabaseHas('faq_categories', [
            'id' => $category->id,
            'name' => 'General',
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_has_many_faqs()
    {
        $category = FaqCategory::create(['name' => 'General']);
        
        $faq1 = FAQ::create([
            'question' => 'Question 1',
            'answer' => 'Answer 1',
            'category_id' => $category->id,
        ]);

        $faq2 = FAQ::create([
            'question' => 'Question 2',
            'answer' => 'Answer 2',
            'category_id' => $category->id,
        ]);

        $this->assertCount(2, $category->faqs);
        $this->assertTrue($category->faqs->contains($faq1));
        $this->assertTrue($category->faqs->contains($faq2));
    }

    /** @test */
    public function it_orders_faqs_by_list_order()
    {
        $category = FaqCategory::create(['name' => 'General']);
        
        $faq1 = FAQ::create([
            'question' => 'Question 1',
            'answer' => 'Answer 1',
            'category_id' => $category->id,
            'list_order' => 2,
        ]);

        $faq2 = FAQ::create([
            'question' => 'Question 2',
            'answer' => 'Answer 2',
            'category_id' => $category->id,
            'list_order' => 1,
        ]);

        $orderedFaqs = $category->faqs;
        
        $this->assertEquals($faq2->id, $orderedFaqs->first()->id);
        $this->assertEquals($faq1->id, $orderedFaqs->last()->id);
    }

    /** @test */
    public function it_can_get_all_categories_as_list()
    {
        FaqCategory::create(['name' => 'Category A']);
        FaqCategory::create(['name' => 'Category B']);
        FaqCategory::create(['name' => 'Category C']);

        $list = FaqCategory::getAllList();

        $this->assertIsArray($list);
        $this->assertCount(3, $list);
        $this->assertArrayHasKey(1, $list); // Assuming IDs start from 1
        $this->assertStringContainsString('Category A', $list[1]);
    }

    /** @test */
    public function it_orders_categories_by_name_in_get_all_list()
    {
        FaqCategory::create(['name' => 'Category C']);
        FaqCategory::create(['name' => 'Category A']);
        FaqCategory::create(['name' => 'Category B']);

        $list = FaqCategory::getAllList();
        $values = array_values($list);

        $this->assertStringContainsString('Category A', $values[0]);
        $this->assertStringContainsString('Category B', $values[1]);
        $this->assertStringContainsString('Category C', $values[2]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $category = new FaqCategory();
        $this->assertEquals('faq_categories', $category->getTable());
    }

    /** @test */
    public function it_guards_id_field()
    {
        $category = FaqCategory::create([
            'id' => 999,
            'name' => 'Test Category',
        ]);

        $this->assertNotEquals(999, $category->id);
    }

    /** @test */
    public function it_can_have_slug_generated()
    {
        $category = FaqCategory::create(['name' => 'General Questions']);
        
        // Assuming the model uses HasSlug trait
        $this->assertNotNull($category->slug);
        $this->assertStringContainsString('general-questions', $category->slug);
    }
}
