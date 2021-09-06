<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\VoucherDataTable;
use App\Http\Requests\CreateVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Currency;
use App\Models\Voucher;
use App\Repositories\VoucherRepository;
use Flash;
use App\Http\Controllers\AppBaseController;

class VoucherController extends AppBaseController
{
    /** @var  VoucherRepository */
    private $voucherRepository;

    public function __construct(VoucherRepository $voucherRepo)
    {
        $this->voucherRepository = $voucherRepo;
    }

    /**
     * Display a listing of the Voucher.
     *
     * @param VoucherDataTable $voucherDataTable
     *
     * @return mixed
     */
    public function index(VoucherDataTable $voucherDataTable)
    {
        return $voucherDataTable->render('admin.vouchers.index');
    }

    /**
     * Show the form for creating a new Voucher.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $currencies = Currency::whereIn('code', ['RUR', 'USD'])->pluck('symbol', 'id');

        return view('admin.vouchers.create')->with('currencies', $currencies->toArray());
    }

    /**
     * Store a newly created Voucher in storage.
     *
     * @param CreateVoucherRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateVoucherRequest $request)
    {
        $input = $request->all();

        $input['code'] = Voucher::createVoucherCode();

        $this->voucherRepository->create($input);

        Flash::success('Voucher saved successfully.');

        return redirect(route('admin.vouchers.index'));
    }

    /**
     * Display the specified Voucher.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        return redirect(route('admin.vouchers.edit', $id));

//        $voucher = $this->voucherRepository->findWithoutFail($id);
//
//        if (empty($voucher)) {
//            Flash::error('Voucher not found');
//
//            return redirect(route('admin.vouchers.index'));
//        }
//
//        return view('admin.vouchers.show')->with('voucher', $voucher);
    }

    /**
     * Show the form for editing the specified Voucher.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $voucher = $this->voucherRepository->findWithoutFail($id);

        if (empty($voucher)) {
            Flash::error('Voucher not found');

            return redirect(route('admin.vouchers.index'));
        }

        $currencies = Currency::whereIn('code', ['RUR', 'USD'])->pluck('symbol', 'id');

        return view('admin.vouchers.edit')->with('voucher', $voucher)->with('currencies', $currencies->toArray());
    }

    /**
     * Update the specified Voucher in storage.
     *
     * @param int $id
     * @param UpdateVoucherRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateVoucherRequest $request)
    {
        $voucher = $this->voucherRepository->findWithoutFail($id);

        if (empty($voucher)) {
            Flash::error('Voucher not found');

            return redirect(route('admin.vouchers.index'));
        }

        $this->voucherRepository->update($request->all(), $id);

        Flash::success('Voucher updated successfully.');

        return redirect(route('admin.vouchers.index'));
    }

    /**
     * Remove the specified Voucher from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $voucher = $this->voucherRepository->findWithoutFail($id);

        if (empty($voucher)) {
            Flash::error('Voucher not found');

            return redirect(route('admin.vouchers.index'));
        }

        $this->voucherRepository->delete($id);

        Flash::success('Voucher deleted successfully.');

        return redirect(route('admin.vouchers.index'));
    }
}
