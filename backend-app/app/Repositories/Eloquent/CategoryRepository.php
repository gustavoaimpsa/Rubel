<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CategoryRepositoryContract;
use App\Models\Category;
use App\Models\Post;

class CategoryRepository implements CategoryRepositoryContract
{
    const CATEGORY_ID_OF_UNCATEGORIZED = 1;

    public $category;

    public function __construct(Category $category,
                                Post $post)
    {
        $this->category = $category;
        $this->post = $post;
    }

    /**
     * Create a new category.
     *
     * @param object $request
     *
     * @return array
     */
    public function create($request)
    {
        $category = $this->category;

        $category->name = $request->name;

        $category->save();

        return ['id' => $category->id]; // TODO: Add status code
    }

    /**
     * Edit a post.
     *
     * @param int    $id
     * @param object $request
     *
     * @return array
     */
    public function edit($request, int $id)
    {
        $category = $this->category->findOrFail($id);

        $category->name = $request->name;

        $category->save();

        return ['id' => $category->id];
    }

    /**
     * Delete a post.
     *
     * @return array
     */
    public function delete(int $id)
    {
        $category = $this->category->findOrFail($id);
        $posts = $category->posts;

        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $post->update([
                    'category_id' => (int) self::CATEGORY_ID_OF_UNCATEGORIZED,
                ]);
            }
        }

        if ($id !== (int) self::CATEGORY_ID_OF_UNCATEGORIZED) {
            $category = $category->delete();
        }

        return [];
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function getCategories()
    {
        $categories = $this->category->get();

        return $categories;
    }
}