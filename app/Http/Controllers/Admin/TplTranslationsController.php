<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Models\TplDefaultLang;
use App\Models\TplTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class TplTranslationsController
 * @package App\Http\Controllers\Admin
 */
class TplTranslationsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $texts = new TplDefaultLang();
        $category = 'customer';

        if (!empty($request->category)) {
            $texts = $texts->where('category', $request->category);
            $category = $request->category;
        }
        $texts = $texts->orderBy('text')->get();

        return view('admin.langs.translations.index', [
            'texts' => $texts,
            'category' => $category
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.langs.translations.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $text = $request->input('text_' . Language::getDefault()->code);
        $defaultId = Language::getDefault()->id;
        $category = $request->input('category');
        $checkExists = TplDefaultLang::where('text', $text)
            ->where('lang_id', $defaultId)
            ->where('category', $category)
            ->count();

        if ($checkExists > 0) {
            return back()->with('error', __('Such translation already exists'))->withInput();
        }

        $defaultLang = TplDefaultLang::create([
            'text' => $text,
            'lang_id' => $defaultId,
            'category' => $category,
        ]);

        if (!$defaultLang) {
            return back()->with('error', __('Unable to create translation'))->withInput();
        }

        foreach (Language::all() as $lang) {
            if ($lang->default) {
                continue;
            }

            TplTranslation::create([
                'text' => $request->input('text_' . $lang->code),
                'lang_id' => $request->input('lang_id_' . $lang->code),
                'default_id' => $defaultLang->id,
            ]);
        }
        return back()->with('success', __('Translation has been created'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $txtLangs = TplTranslation::where('default_id', $id)->get();
        $txtKeys = collect([
            'lang_id',
            'lang_code',
            'lang_name',
            'text'
        ]);
        $txtDefault = TplDefaultLang::find($id);
        $category = $txtDefault->category;
        $txtDefault = collect([
            Language::getDefault()->id,
            Language::getDefault()->code,
            Language::getDefault()->name,
            $txtDefault->text
        ]);
        $data[] = $txtKeys->combine($txtDefault);

        foreach (Language::orderBy('default', 'desc')->get() as $lang) {
            if ($lang->default) {
                continue;
            }

            if ($txtValue = $txtLangs->where('lang_id', $lang->id)->first()) {
                $txtValuesCollect = collect([$lang->id, $lang->code, $lang->name, $txtValue->text]);
            } else {
                $txtValuesCollect = collect([$lang->id, $lang->code, $lang->name, '']);
            }
            $data[] = $txtKeys->combine($txtValuesCollect);
        }
        $data = collect($data);

        return view('admin.langs.translations.edit', [
            'data' => $data,
            'id' => $id,
            'category' => $category,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $txtDefault = TplDefaultLang::find($id);

        if ($txtDefault->update([
            'text' => $request->input('text_' . Language::getDefault()->code),
            'lang_id' => Language::getDefault()->id,
            'category' => $request->input('category'),
        ])) {
            $translations = TplTranslation::where('default_id', $id)->get();

            foreach (Language::all() as $lang) {
                if ($lang->default) {
                    continue;
                }

                if ($translation = $translations->where('lang_id', $lang->id)->first()) {
                    $translation->update([
                        'text' => $request->input('text_' . $lang->code),
                    ]);
                } else {
                    TplTranslation::create([
                        'text' => $request->input('text_' . $lang->code),
                        'lang_id' => $request->input('lang_id_' . $lang->code),
                        'default_id' => $id,
                    ]);
                }
            }
            return back()->with('success', __('Translation ahs been updated'));
        }
        return back()->with('error', __('Unable to edit translation'))->withInput();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $tplDefaultLangDel = TplDefaultLang::find($id);

        if ($tplDefaultLangDel->delete()) {
            return back()->with('success', 'Translation has been deleted');
        }
        return back()->with('error', __('Unable to delete translation'));
    }
}
