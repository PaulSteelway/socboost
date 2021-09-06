<?php

namespace App\Http\Managers;


use App\Models\Category;
use App\Models\CategoryAddPage;

class CategoryManager
{
    /**
     * @param Category $category
     * @param int|null $copy
     * @return array
     */
    public static function getCategoryWithMetaTagsAccordinglyUrl(Category $category, $copy)
    {
        $result = $category->toArray();
        if (!empty($copy)) {
            /** @var CategoryAddPage $tags */
            $tags = $category->category_add_pages()->where('id', $copy)->first();
            if (!empty($tags)) {
                $result['title'] = $tags->title;
                $result['title_ru'] = $tags->title_ru;
                $result['meta_title'] = $tags->meta_title;
                $result['meta_title_ru'] = $tags->meta_title_ru;
                $result['meta_keywords'] = $tags->meta_keywords;
                $result['meta_keywords_ru'] = $tags->meta_keywords_ru;
                $result['meta_description'] = $tags->meta_description;
                $result['meta_description_ru'] = $tags->meta_description_ru;
            }
        }
        return $result;
    }
}