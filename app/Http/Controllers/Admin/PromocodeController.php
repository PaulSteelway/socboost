<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PromocodeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePromocodeRequest;
use App\Http\Requests\UpdatePromocodeRequest;
use App\Models\Promocode;
use App\Repositories\PromocodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Http\Request;
use Response;
use Validator;

class PromocodeController extends AppBaseController
{
    /** @var  PromocodeRepository */
    private $promocodeRepository;

    public function __construct(PromocodeRepository $promocodeRepo)
    {
        $this->promocodeRepository = $promocodeRepo;
    }

    /**
     * Display a listing of the Promocode.
     *
     * @param PromocodeDataTable $promocodeDataTable
     * @return Response
     */
    public function index(PromocodeDataTable $promocodeDataTable)
    {
        return $promocodeDataTable->render('admin.promocodes.index');
    }

    /**
     * Show the form for creating a new Promocode.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.promocodes.create');
    }

    /**
     * Store a newly created Promocode in storage.
     *
     * @param CreatePromocodeRequest $request
     *
     * @return Response
     */
    public function store(CreatePromocodeRequest $request)
    {
        $input = $request->all();


        Promocodes::create(
            $request->get('quantity'),
            $request->get('reward'),
            $request->get('data'),
            $request->get('expires_at'),
            $request->get('is_disposable'),
            $request->get('is_disposable')
        );

        Flash::success('Promocode saved successfully.');

        return redirect(route('admin.promocodes.index'));
    }

    /**
     * Display the specified Promocode.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $promocode = $this->promocodeRepository->findWithoutFail($id);

        if (empty($promocode)) {
            Flash::error('Promocode not found');

            return redirect(route('admin.promocodes.index'));
        }

        return view('admin.promocodes.show')->with('promocode', $promocode);
    }

    /**
     * Show the form for editing the specified Promocode.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $promocode = $this->promocodeRepository->findWithoutFail($id);

        if (empty($promocode)) {
            Flash::error('Promocode not found');

            return redirect(route('promocodes.index'));
        }

        return view('admin.promocodes.edit')->with('promocode', $promocode);
    }

    /**
     * Update the specified Promocode in storage.
     *
     * @param int $id
     * @param UpdatePromocodeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePromocodeRequest $request)
    {
        $promocode = $this->promocodeRepository->findWithoutFail($id);

        if (empty($promocode)) {
            Flash::error('Promocode not found');

            return redirect(route('admin.promocodes.index'));
        }

        $promocode = $this->promocodeRepository->update($request->all(), $id);

        Flash::success('Promocode updated successfully.');

        return redirect(route('admin.promocodes.index'));
    }

    /**
     * Remove the specified Promocode from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $promocode = $this->promocodeRepository->findWithoutFail($id);

        if (empty($promocode)) {
            Flash::error('Promocode not found');

            return redirect(route('admin.promocodes.index'));
        }

        $this->promocodeRepository->delete($id);

        Flash::success('Promocode deleted successfully.');

        return redirect(route('admin.promocodes.index'));
    }

    public function get_promo_details($id, Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'g-recaptcha-response' => 'recaptcha',
        ]);
        
        // check if validator fails
        if($validator->fails()) {
            return Response::json(['status' => __('Not valid'), 'data' => $data], 200);
            $errors = $validator->errors();
        }
        
        $promocode = Promocode::where('code', $id)->first();
        if ($promocode) {
            try {
                if (Promocodes::check($promocode->code) && isset($promocode->reward) && isset($promocode->data['apply_from'])) {
                    $apply_from = $promocode->data['apply_from'];
                    $reward = __('You received a bonus of') . ' ' . number_format(socialboosterPriceByAmount($promocode->reward), 2, '.', '') . ' ' . __('USD') . ', ' . __('it will be active when refilling is above') . ' ' . number_format(socialboosterPriceByAmount($apply_from), 2, '.', '') . ' ' . __('USD');
                    $data = " <span>$reward</span>";
                    return Response::json(['status' => 'Valid', 'data' => $data], 200);
                }
            } catch (\Exception $exeption) {
                \Log::debug('Code not valid');
            }
            $data = "<span>" . __('Not valid') . "</span>";
            return Response::json(['status' => __('Not valid'), 'data' => $data], 200);
        } else {
            $data = "<span>" . __('Not valid') . "</span>";
            return Response::json(['status' => __('Not exist'), 'data' => $data], 200);
        }

    }

}
