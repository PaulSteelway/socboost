<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestCreateLanguage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;

/**
 * Class LanguagesController
 * @package App\Http\Controllers\Admin
 */
class LanguagesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.langs.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.langs.create');
    }

    /**
     * @param RequestCreateLanguage $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(RequestCreateLanguage $request)
    {
        $createLang = Language::create($request->all());

        if (!$createLang) {
            return back()->with('error', __('Unable to create language'))->withInput();
        }

        return back()->with('success', __('Language has been created'));
    }

    /**
     * @param Language $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Language $lang)
    {
        return view('admin.langs.edit', [
            'lang' => $lang
        ]);
    }

    /**
     * @param Request $request
     * @param Language $lang
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Language $lang)
    {
        if ($lang->default) {
            return back()->with('error', __('It is forbidden to change the default language'))->withInput();
        }

        $update = $lang->update($request->all());

        if ($request->default) {
            Language::where('default', 1)->update([
                'default' => 0,
            ]);
            $lang->default = 1;
            $lang->save();
        }

        if (!$update) {
            return back()->with('error', __('Unable to update language'))->withInput();
        }

        return back()->with('success', 'Language has been updated');
    }

    /**
     * @param $lang
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function destroy($lang)
    {
        $lang = Language::find($lang);

        if ($lang->default) {
            return back()->with('error', __('It is forbidden to change the default language\''))->withInput();
        }

        if ($lang->delete()) {
            return redirect()->route('admin.langs.index')->with('success', __('Language has been deleted'));
        }

        return redirect()->route('admin.langs.index')->with('error', __('Unable to delete language'));
    }
}
