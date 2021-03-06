<?php

namespace Tests\Feature\Categories;

use Facades\Tests\Setup\CategoryFactory;
use Facades\Tests\Setup\AdvertisementFactory;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    /** @test */
    function it_returns_a_collection_of_categories()
    {
        $categories = CategoryFactory::createParent(2);

        $response = $this->getJson(route('categories.index'));

        $response->assertJsonCount(2, 'data');

        $categories->each(function ($category) use ($response){
            $response->assertJsonFragment(['slug' => $category->slug]);
        });
    }

    /** @test */
    function it_returns_parents_only()
    {
         CategoryFactory::withChildren(2)->createParent();

         $this->getJson(route('categories.index'))
            ->assertJsonCount(1);
    }

    /** @test */
    function it_returns_categories_ordered_by_latest()
    {
        $categories = CategoryFactory::createParent(3);

        $this->getJson(route('categories.index'))
            ->assertSeeInOrder([
                $categories[2]->slug,
                $categories[1]->slug,
                $categories[0]->slug
            ]);
    }
}
