<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchCategory = $request->get('category');
        $search = $request->get('search');
        if (app()->getLocale() == 'en') {
            $categories = FaqCategory::select(['id', 'order', 'name_en AS name', 'name_en', 'icon']);
            $data = FaqCategory::select(['id', 'order', 'name_en AS name'])
                ->with(['faqs' => function ($query) use ($search) {
                    $query->select(['id', 'category_id', 'order', 'question_en AS question', 'answer_en AS answer'])
                        ->orderBy('order', 'asc');
                    if ($search) {
                        $query->where('question_en', 'like', "%{$search}%");
                    }
                }]);
        } else {
            $categories = FaqCategory::select(['id', 'order', 'name_ru AS name', 'name_en', 'icon']);
            $data = FaqCategory::select(['id', 'order', 'name_ru AS name'])
                ->with(['faqs' => function ($query) use ($search) {
                    $query->select(['id', 'category_id', 'order', 'question_ru AS question', 'answer_ru AS answer'])
                        ->orderBy('order', 'asc');
                    if ($search) {
                        $query->where('question_ru', 'like', "%{$search}%");
                    }
                }]);
        }

        $categories = $categories->orderBy('order', 'asc')
            ->get()
            ->toArray();

        if (empty($search)) {
            if (empty($searchCategory)) {
                $data = $data->where('order', 1);
            } else {
                $data = $data->where('name_en', $searchCategory);
            }
        }
        $data = $data->orderBy('order', 'asc')
            ->get()
            ->toArray();

        return view('customer.FaqController')
            ->with('categories', $categories)
            ->with('data', $data)
            ->with('searchCategory', $searchCategory)
            ->with('search', $search);
    }
}
