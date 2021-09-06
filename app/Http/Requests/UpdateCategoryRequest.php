<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Category::$rules;

        $id = $this->route('category');

        $parentId = $this->request->get('parent_id');

        $rules['name_ru'] = [
            'required',
            'string',
            'max:191',
            Rule::unique('categories')->where(function ($query) use ($parentId) {
                if (empty($parentId)) {
                    return $query->whereNull('parent_id');
                } else {
                    return $query->where('parent_id', $parentId);
                }
            })->ignore($id)
        ];
        $rules['name_en'] = [
            'required',
            'string',
            'max:191',
            Rule::unique('categories')->where(function ($query) use ($parentId) {
                if (empty($parentId)) {
                    return $query->whereNull('parent_id');
                } else {
                    return $query->where('parent_id', $parentId);
                }
            })->ignore($id)
        ];
        $rules['url'] = 'required|string|max:191|unique:categories,url,' . $id;
        $rules['icon_img'] = 'mimes:jpeg,png,jpg,gif,svg,ico';


        if (in_array($id, [1, 2, 3, 4, 5, 6, 7])) {
            unset($rules['parent_id'], $rules['url']);
        }

        return $rules;
    }
}
