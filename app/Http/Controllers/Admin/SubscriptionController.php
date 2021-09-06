<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Repositories\SubscriptionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class SubscriptionController extends AppBaseController
{
    /** @var  SubscriptionRepository */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepo)
    {
        $this->subscriptionRepository = $subscriptionRepo;
    }

    /**
     * Display a listing of the Subscription.
     *
     * @param SubscriptionDataTable $subscriptionDataTable
     *
     * @return mixed
     */
    public function index(SubscriptionDataTable $subscriptionDataTable)
    {
        return $subscriptionDataTable->render('admin.subscriptions.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect(route('admin.subscriptions.edit', $id));
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

            return redirect(route('admin.subscriptions.index'));
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
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            Flash::error('Subscription not found');

            return redirect(route('admin.subscriptions.index'));
        }

        $this->subscriptionRepository->update($request->all(), $id);

        Flash::success('Subscription updated successfully.');

        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        return redirect(route('admin.subscriptions.edit', $id));
    }
}
