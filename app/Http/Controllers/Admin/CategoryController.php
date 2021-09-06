<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Response;

class CategoryController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * @param CategoryDataTable $categoryDataTable
     * @return mixed
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('admin.categories.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $parents = $this->categoryRepository->model()::whereNull('parent_id')->where('status', 1)->get()->pluck('name_en', 'id');

        return view('admin.categories.create', ['parents' => $parents->toArray()]);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);

        $this->categoryRepository->create($input);

        Flash::success('Category saved successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect(route('admin.categories.edit', $id));
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $parents = $this->categoryRepository->model()::whereNull('parent_id')->where('status', 1)->get()->pluck('name_en', 'id');

        return view('admin.categories.edit')->with('category', $category)->with('parents', $parents);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param $id
     * @param UpdateCategoryRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);
        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $this->categoryRepository->update($input, $id);

        Flash::success('Category updated successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success('Category deleted successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * @param  $request
     * @param array $input
     * @return array
     */
    public function saveIconImages($request, array $input): array
    {
        if ($request->has('icon_img')) {
            $res = Storage::disk('public_folder')->putFileAs('img', $request->icon_img, $request->icon_img->getClientOriginalName());
            $input['icon_img'] = $res;
        }
        if ($request->has('icon_img_active')) {
            $res = Storage::disk('public_folder')->putFileAs('img', $request->icon_img_active, $request->icon_img_active->getClientOriginalName());
            $input['icon_img_active'] = $res;
        }
        return $input;
    }
}
