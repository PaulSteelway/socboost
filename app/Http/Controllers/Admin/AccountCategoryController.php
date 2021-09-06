<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AccountCategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateAccountCategoryRequest;
use App\Http\Requests\UpdateAccountCategoryRequest;
use App\Repositories\AccountCategoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Storage;

class AccountCategoryController extends AppBaseController
{
    /** @var  AccountCategoryRepository */
    private $accountCategoryRepository;

    public function __construct(AccountCategoryRepository $accountCategoryRepo)
    {
        $this->accountCategoryRepository = $accountCategoryRepo;
    }

    /**
     * Display a listing of the AccountCategory.
     *
     * @param AccountCategoryDataTable $accountCategoryDataTable
     * @return Response
     */
    public function index(AccountCategoryDataTable $accountCategoryDataTable)
    {
        return $accountCategoryDataTable->render('admin.account_categories.index');
    }

    /**
     * Show the form for creating a new AccountCategory.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.account_categories.create');
    }

    /**
     * Store a newly created AccountCategory in storage.
     *
     * @param CreateAccountCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountCategoryRequest $request)
    {
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);

        $this->accountCategoryRepository->create($input);

        Flash::success('Account Category saved successfully.');

        return redirect(route('admin.accountCategories.index'));
    }

    /**
     * Display the specified AccountCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect(route('admin.accountCategories.edit', $id));
    }

    /**
     * Show the form for editing the specified AccountCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountCategory = $this->accountCategoryRepository->findWithoutFail($id);

        if (empty($accountCategory)) {
            Flash::error('Account Category not found');

            return redirect(route('admin.accountCategories.index'));
        }

        return view('admin.account_categories.edit')->with('category', $accountCategory);
    }

    /**
     * Update the specified AccountCategory in storage.
     *
     * @param  int              $id
     * @param UpdateAccountCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountCategoryRequest $request)
    {
        $accountCategory = $this->accountCategoryRepository->findWithoutFail($id);
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);
        if (empty($accountCategory)) {
            Flash::error('Account Category not found');

            return redirect(route('admin.accountCategories.index'));
        }

        $this->accountCategoryRepository->update($input, $id);

        Flash::success('Account Category updated successfully.');

        return redirect(route('admin.accountCategories.index'));
    }

    /**
     * Remove the specified AccountCategory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountCategory = $this->accountCategoryRepository->findWithoutFail($id);

        if (empty($accountCategory)) {
            Flash::error('Account Category not found');

            return redirect(route('admin.accountCategories.index'));
        }

        $this->accountCategoryRepository->delete($id);

        Flash::success('Account Category deleted successfully.');

        return redirect(route('admin.accountCategories.index'));
    }

    /**
     * @param  $request
     * @param array $input
     * @return array
     */
    public function saveIconImages($request, array $input): array
    {
        if ($request->has('icon_img')) {
            $res = Storage::disk('public_folder')->putFileAs('img/account_images', $request->icon_img, $request->icon_img->getClientOriginalName());
            $input['icon_img'] = $res;
        }
        if ($request->has('icon_img_active')) {
            $res = Storage::disk('public_folder')->putFileAs('img/account_images', $request->icon_img_active, $request->icon_img_active->getClientOriginalName());
            $input['icon_img_active'] = $res;
        }
        return $input;
    }
}
