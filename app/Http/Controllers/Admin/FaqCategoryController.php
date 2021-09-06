<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqCategoryDataTable;
use App\Http\Requests\CreateFaqCategoryRequest;
use App\Http\Requests\UpdateFaqCategoryRequest;
use App\Models\FaqCategory;
use App\Repositories\FaqCategoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Intervention\Image\Facades\Image;

class FaqCategoryController extends AppBaseController
{
    /** @var  FaqCategoryRepository */
    private $faqCategoryRepository;

    public function __construct(FaqCategoryRepository $faqCategoryRepo)
    {
        $this->faqCategoryRepository = $faqCategoryRepo;
    }

    /**
     * Display a listing of the FaqCategory.
     *
     * @param FaqCategoryDataTable $faqCategoryDataTable
     *
     * @return mixed
     */
    public function index(FaqCategoryDataTable $faqCategoryDataTable)
    {
        return $faqCategoryDataTable->render('admin.faq_categories.index');
    }

    /**
     * Show the form for creating a new FaqCategory.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.faq_categories.create');
    }

    /**
     * Store a newly created FaqCategory in storage.
     *
     * @param CreateFaqCategoryRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateFaqCategoryRequest $request)
    {
        $input = $request->all();

        $input = $this->saveIconImages($request, $input);

        $this->faqCategoryRepository->create($input);

        Flash::success('Faq Category saved successfully.');

        return redirect(route('admin.faqCategories.index'));
    }

    /**
     * Display the specified FaqCategory.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        return redirect(route('admin.faqCategories.edit', $id));
    }

    /**
     * Show the form for editing the specified FaqCategory.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $faqCategory = $this->faqCategoryRepository->findWithoutFail($id);

        if (empty($faqCategory)) {
            Flash::error('Faq Category not found');

            return redirect(route('admin.faqCategories.index'));
        }

        return view('admin.faq_categories.edit')->with('faqCategory', $faqCategory);
    }

    /**
     * Update the specified FaqCategory in storage.
     *
     * @param $id
     * @param UpdateFaqCategoryRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateFaqCategoryRequest $request)
    {
        $faqCategory = $this->faqCategoryRepository->findWithoutFail($id);

        if (empty($faqCategory)) {
            Flash::error('Faq Category not found');

            return redirect(route('admin.faqCategories.index'));
        }

        $input = $request->all();

        $input = $this->saveIconImages($request, $input, $faqCategory);

        $this->faqCategoryRepository->update($input, $id);

        Flash::success('Faq Category updated successfully.');

        return redirect(route('admin.faqCategories.index'));
    }

    /**
     * Remove the specified FaqCategory from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $faqCategory = $this->faqCategoryRepository->findWithoutFail($id);

        if (empty($faqCategory)) {
            Flash::error('Faq Category not found');

            return redirect(route('admin.faqCategories.index'));
        }

        $this->faqCategoryRepository->delete($id);

        Flash::success('Faq Category deleted successfully.');

        return redirect(route('admin.faqCategories.index'));
    }

    /**
     * @param $request
     * @param array $input
     * @param FaqCategory|null $faqCategory
     * @return array
     */
    public function saveIconImages($request, array $input, FaqCategory $faqCategory = null): array
    {
        if ($request->has('icon')) {
            if (!empty($faqCategory) && !empty($faqCategory->icon)) {
                $path = public_path($faqCategory->icon);
                if (is_file($path)) {
                    unlink($path);
                }
            }
            $input['icon'] = 'img/fc_' . $input['name_en'] . '.' . $request->file('icon')->getClientOriginalExtension();
            Image::make($request->file('icon'))->save(public_path($input['icon']));
        }
        return $input;
    }
}
