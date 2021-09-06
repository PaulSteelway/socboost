<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\NewsLang;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $news = NewsLang::where('lang_id', Language::getDefault()->id)->get();

        return view('admin.news.index', [
            'allNews' => $news
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $slug = str_slug($request->input('title_' . Language::getDefault()->code));

        if ($parentNews = News::create(['slug' => $slug])) {
            foreach (getLanguagesArray() as $lang) {
                NewsLang::create([
                    'title' => $request->input('title_' . $lang['code']),
                    'teaser' => $request->input('teaser_' . $lang['code']),
                    'text' => $request->input('text_' . $lang['code']),
                    'lang_id' => $request->input('lang_id_' . $lang['code']),
                    'news_id' => $parentNews->id,
                ]);
            }

            if ($request->hasFile('img')) {
                $parentNews->addImg($request->file('img'));
            }
            return redirect()->route('admin.news.index')->with('success', __('News has been created'));
        }
        return back()->with('error', __('Unable to create news'))->withInput();
    }

    /**
     * @param News $news
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function edit(News $news)
    {
        $newsLangs = NewsLang::where('news_id', $news->id)->get();
        $newsKeys = collect([
            'lang_id',
            'lang_code',
            'lang_name',
            'title',
            'teaser',
            'text',
            'created_at'
        ]);

        foreach (getLanguagesArray() as $lang) {
            if ($newsValues = $newsLangs->where('lang_id', $lang['id'])->first()) {
                $newsValuesCollect = collect([$lang['id'], $lang['code'], $lang['name'], $newsValues->title, $newsValues->teaser, $newsValues->text, $newsValues->created_at]);
            } else {
                $newsValuesCollect = collect([$lang['id'], $lang['code'], $lang['name'], '', '', '', '']);
            }
            $data[] = $newsKeys->combine($newsValuesCollect);
        }
        $data = collect($data);

        return view('admin/news/edit', [
            'newsData' => $data,
            'news' => $news,
            'img' => '/news_img/' . $news->img,
        ]);
    }

    /**
     * @param Request $request
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, News $news)
    {
        $slug = str_slug($request->input('title_' . Language::getDefault()->code));

        if ($news->update(['slug' => $slug])) {
            $translations = NewsLang::where('news_id', $news->id)->get();

            foreach (getLanguagesArray() as $lang) {
                if ($translation = $translations->where('lang_id', $lang['id'])->first()) {
                    $translation->update([
                        'title' => $request->input('title_' . $lang['code']),
                        'teaser' => $request->input('teaser_' . $lang['code']),
                        'text' => $request->input('text_' . $lang['code']),
                    ]);
                } else {
                    NewsLang::create([
                        'title' => $request->input('title_' . $lang['code']),
                        'teaser' => $request->input('teaser_' . $lang['code']),
                        'text' => $request->input('text_' . $lang['code']),
                        'lang_id' => $request->input('lang_id_' . $lang['code']),
                        'news_id' => $news->id,
                    ]);
                }
            }

            if ($request->hasFile('img')) {
                $news->addImg($request->file('img'));
            }
            return redirect()->route('admin.news.index')->with('success', __('News has been updated'));
        }
        return back()->with('error', __('Unable to update news'))->withInput();
    }

    /**
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $news)
    {
        if ($news->delete()) {
            return redirect()->route('admin.news.index')->with('success', __('News has been deleted'));
        }
        return back()->with('error', __('Unable to delete news'));
    }
}
