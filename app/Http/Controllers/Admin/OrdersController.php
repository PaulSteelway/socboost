<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateOrdersRequest;
use App\Repositories\OrdersRepository;
use App\Http\Controllers\AppBaseController;
use Flash;

class OrdersController extends AppBaseController
{
    /** @var  OrdersRepository */
    private $ordersRepository;

    public function __construct(OrdersRepository $ordersRepo)
    {
        $this->ordersRepository = $ordersRepo;
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $order = $this->ordersRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('admin.users.show', $order->user_id));
        }

        return view('admin.orders.edit')->with('order', $order);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrdersRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateOrdersRequest $request)
    {
        $order = $this->ordersRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Orders not found');

            return redirect(route('admin.users.show', $order->user_id));
        }

        $this->ordersRepository->update($request->all(), $id);

        Flash::success('Order updated successfully.');

        return redirect(route('admin.orders.edit', $order->id));
    }
}
