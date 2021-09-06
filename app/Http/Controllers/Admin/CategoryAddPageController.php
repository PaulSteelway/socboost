<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryAddPageDataTable;
use App\Http\Requests\CreateCategoryAddPageRequest;
use App\Http\Requests\UpdateCategoryAddPageRequest;
use App\Repositories\CategoryAddPageRepository;
use App\Repositories\CategoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;

class CategoryAddPageController extends AppBaseController
{
    /** @var  CategoryAddPageRepository */
    private $categoryAddPageRepository;

    public function __construct(CategoryAddPageRepository $categoryAddPageRepo)
    {
        $this->categoryAddPageRepository = $categoryAddPageRepo;
    }

    /**
     * Display a listing of the CategoryAddPage.
     *
     * @param CategoryAddPageDataTable $categoryAddPageDataTable
     * @return mixed
     */
    public function index(CategoryAddPageDataTable $categoryAddPageDataTable)
    {
        return $categoryAddPageDataTable->render('admin.category_add_pages.index');
    }

    /**
     * Show the form for creating a new CategoryAddPage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = CategoryRepository::getCategoryTreeDropdown();

        return view('admin.category_add_pages.create')->with('categories', $categories);
    }

    /**
     * Store a newly created CategoryAddPage in storage.
     *
     * @param CreateCategoryAddPageRequest $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateCategoryAddPageRequest $request)
    {
        $input = $request->all();

        $this->categoryAddPageRepository->create($input);

        Flash::success('Category Add Page saved successfully.');

        return redirect(route('admin.categoryAddPages.index'));
    }

    /**
     * Display the specified CategoryAddPage.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        return redirect(route('admin.categoryAddPages.edit', $id));

//        $categoryAddPage = $this->categoryAddPageRepository->find($id);
//
//        if (empty($categoryAddPage)) {
//            Flash::error('Category Add Page not found');
//
//            return redirect(route('admin.categoryAddPages.index'));
//        }
//
//        return view('admin.category_add_pages.show')->with('categoryAddPage', $categoryAddPage);
    }

    /**
     * Show the form for editing the specified CategoryAddPage.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $categoryAddPage = $this->categoryAddPageRepository->find($id);

        if (empty($categoryAddPage)) {
            Flash::error('Category Add Page not found');

            return redirect(route('admin.categoryAddPages.index'));
        }

        $categories = CategoryRepository::getCategoryTreeDropdown();

        return view('admin.category_add_pages.edit')->with('categoryAddPage', $categoryAddPage)->with('categories', $categories);
    }

    /**
     * Update the specified CategoryAddPage in storage.
     *
     * @param $id
     * @param UpdateCategoryAddPageRequest $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateCategoryAddPageRequest $request)
    {
        $categoryAddPage = $this->categoryAddPageRepository->find($id);

        if (empty($categoryAddPage)) {
            Flash::error('Category Add Page not found');

            return redirect(route('admin.categoryAddPages.index'));
        }

        $categoryAddPage = $this->categoryAddPageRepository->update($request->all(), $id);

        Flash::success('Category Add Page updated successfully.');

        return redirect(route('admin.categoryAddPages.index'));
    }

    /**
     * Remove the specified CategoryAddPage from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        $categoryAddPage = $this->categoryAddPageRepository->find($id);

        if (empty($categoryAddPage)) {
            Flash::error('Category Add Page not found');

            return redirect(route('admin.categoryAddPages.index'));
        }

        $this->categoryAddPageRepository->delete($id);

        Flash::success('Category Add Page deleted successfully.');

        return redirect(route('admin.categoryAddPages.index'));
    }
}
