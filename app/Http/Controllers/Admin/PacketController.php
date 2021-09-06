<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PacketDataTable;
use App\Http\Requests\CreatePacketRequest;
use App\Http\Requests\UpdatePacketRequest;
use App\Models\Packet;
use App\Repositories\CategoryRepository;
use App\Repositories\PacketRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Illuminate\Http\Request;
use Response;

class PacketController extends AppBaseController
{
    /** @var  PacketRepository */
    private $packetRepository;

    public function __construct(PacketRepository $packetRepo)
    {
        $this->packetRepository = $packetRepo;
    }

    /**
     * Display a listing of the Packet.
     *
     * @param PacketDataTable $packetDataTable
     *
     * @return mixed
     */
    public function index(PacketDataTable $packetDataTable)
    {
        return $packetDataTable->render('admin.packets.index');
    }

    /**
     * Show the form for creating a new Packet.
     *
     * @return Response
     */
    public function create()
    {
        $categories = CategoryRepository::getCategoryTreeDropdown();

        return view('admin.packets.create')->with('categories', $categories);
    }

    /**
     * Store a newly created Packet in storage.
     *
     * @param CreatePacketRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreatePacketRequest $request)
    {
        $input = $request->all();

        $packet = $this->packetRepository->create($input);

        Flash::success('Packet saved successfully.');

        return redirect(route('admin.packets.index'));
    }

    /**
     * Display the specified Packet.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect(route('admin.packets.edit', $id));

//        $packet = $this->packetRepository->findWithoutFail($id);
//
//        if (empty($packet)) {
//            Flash::error('Packet not found');
//
//            return redirect(route('admin.packets.index'));
//        }
//
//        return view('admin.packets.show')->with('packet', $packet);
    }

    /**
     * Show the form for editing the specified Packet.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $packet = $this->packetRepository->findWithoutFail($id);

        if (empty($packet)) {
            Flash::error('Packet not found');

            return redirect(route('admin.packets.index'));
        }

        $categories = CategoryRepository::getCategoryTreeDropdown();

        return view('admin.packets.edit')->with('packet', $packet)->with('categories', $categories);
    }

    /**
     * Update the specified Packet in storage.
     *
     * @param $id
     * @param UpdatePacketRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdatePacketRequest $request)
    {
        $packet = $this->packetRepository->findWithoutFail($id);

        if (empty($packet)) {
            Flash::error('Packet not found');

            return redirect(route('admin.packets.index'));
        }

        $packet = $this->packetRepository->update($request->all(), $id);

        Flash::success('Packet updated successfully.');

        return redirect(route('admin.packets.index'));
    }

    /**
     * Remove the specified Packet from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $packet = $this->packetRepository->findWithoutFail($id);

        if (empty($packet)) {
            Flash::error('Packet not found');

            return redirect(route('admin.packets.index'));
        }

        $this->packetRepository->delete($id);

        Flash::success('Packet deleted successfully.');

        return redirect(route('admin.packets.index'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function reprice(Request $request)
    {
        try {
            $input = $request->all();
            $packets = Packet::whereIn('id', $input['ids'])->get();
            if ($packets->isNotEmpty()) {
                $percent = floatval($input['percent']);
                /** @var Packet $packet */
                foreach ($packets as $packet) {
                    if ($input['action'] === 'minus') {
                        $packet->price -= $packet->price * $percent / 100;
                    } else {
                        $packet->price += $packet->price * $percent / 100;
                    }
                    $packet->save();
                }
            }
            return $this->sendResponse([], 'Success!');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function repricePackets(Request $request)
    {
        try {
            $packets = Packet::select(['packets.*', 'c1.name_en AS category', 'c2.name_en AS parent'])
                ->join('categories AS c1', 'c1.id', '=', 'packets.category_id')
                ->leftJoin('categories AS c2', 'c2.id', '=', 'c1.parent_id');
            if (!empty($request->get('search'))) {
                $search = $request->get('search');
                $packets = $packets->where('packets.id', 'like', "%" . $search . "%")
                    ->orWhere('c1.name_en', 'like', "%" . $search . "%")
                    ->orWhere('c2.name_en', 'like', "%" . $search . "%")
                    ->orWhere('packets.service_id', 'like', "%" . $search . "%")
                    ->orWhere('packets.name_en', 'like', "%" . $search . "%")
                    ->orWhere('packets.name_ru', 'like', "%" . $search . "%")
                    ->orWhere('packets.min', 'like', "%" . $search . "%")
                    ->orWhere('packets.max', 'like', "%" . $search . "%")
                    ->orWhere('packets.price', 'like', "%" . $search . "%");
            }
            $packets = $packets->get()->pluck('id');
            return $this->sendResponse($packets, 'Success!');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
