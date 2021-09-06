<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version November 26, 2019, 9:07 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
 */
class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'parent_id',
        'name_ru',
        'name_en',
        'status',
        'order',
        'url',
        'type',
        'details_ru',
        'details_en'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Category::class;
    }

    /**
     * @return array
     */
    public static function getHeaderCategories()
    {
        $parents = [];
        $childs = [];

        $categories = Category::where('status', 1)->orderBy('order', 'asc')->get();
        if ($categories->isNotEmpty()) {
            foreach ($categories->toArray() as $category) {
                if (empty($category['parent_id'])) {
                    $parents[] = $category;
                } else {
                    $childs[$category['parent_id']][] = $category;
                }
            }

            foreach ($parents as $key => $parent) {
                if (!empty($childs[$parent['id']])) {
                    $parents[$key]['subcategories'] = $childs[$parent['id']];
                }
            }
        }

        return $parents;
    }

    /**
     * @return array
     */
    public static function getCategoryTreeDropdown()
    {
        $sortedCategories = [];
        $parents = [];
        $childs = [];

        $categories = Category::where('status', 1)->orderBy('order', 'asc')->get();
        if ($categories->isNotEmpty()) {
            foreach ($categories->toArray() as $category) {
                if (empty($category['parent_id'])) {
                    $parents[] = $category;
                } else {
                    $childs[$category['parent_id']][$category['id']] = $category['name_en'];
                }
            }

            foreach ($parents as $parent) {
                if (!empty($childs[$parent['id']])) {
                    $sortedCategories[$parent['name_en']] = $childs[$parent['id']];
                }
            }
        }

        return $sortedCategories;
    }

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
}
