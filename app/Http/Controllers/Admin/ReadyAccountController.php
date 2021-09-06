<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AccountCategoryDataTable;
use App\DataTables\CategoryDataTable;
use App\DataTables\PacketDataTable;
use App\DataTables\ProductItemsDataTable;
use App\DataTables\ProductsDataTable;
use App\DataTables\SubscriptionDataTable;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\SubscriptionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class ReadyAccountController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->subscriptionRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Subscription.
     *
     * @param AccountCategoryDataTable $accountDataTable
     *
     * @return mixed
     */
    public function index(AccountCategoryDataTable $accountDataTable)
    {
        return $accountDataTable->render('admin.readyAccount.account_category');
    }
    /**
     * Display a listing of the Subscription.
     *
     * @param ProductsDataTable $productsDataTable
     *
     * @return mixed
     */
    public function products(ProductsDataTable $productsDataTable)
    {
        return $productsDataTable->render('admin.readyAccount.products');
    }
    /**
     * Display a listing of the Subscription.
     *
     * @param ProductItemsDataTable $productItemsDataTable
     *
     * @return mixed
     */
    public function products_items(ProductItemsDataTable $productItemsDataTable)
    {
        return $productItemsDataTable->render('admin.readyAccount.product_items');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect(route('admin.readyAccount.products_items'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        return redirect(route('admin.readyAccount.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect(route('admin.readyAccount.edit', $id));
    }

    /**
     * Show the form for editing the specified Subscription.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('admin.readyAccount.index'));
        }

        return view('admin.subscriptions.edit')->with('subscription', $subscription);
    }

    /**
     * Update the specified Subscription in storage.
     *
     * @param int $id
     * @param UpdateSubscriptionRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateSubscriptionRequest $request)
    {

        return redirect(route('admin.readyAccount.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        return redirect(route('admin.readyAccount.edit', $id));
    }
}
