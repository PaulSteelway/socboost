<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TicketDataTable;
use App\Http\Requests\UpdateTicketRequest;
use App\Repositories\TicketRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class TicketController extends AppBaseController
{
    /** @var  TicketRepository */
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepo)
    {
        $this->ticketRepository = $ticketRepo;
    }

    /**
     * Display a listing of the Ticket.
     *
     * @param TicketDataTable $ticketDataTable
     *
     * @return mixed
     */
    public function index(TicketDataTable $ticketDataTable)
    {
        return $ticketDataTable->render('admin.tickets.index');
    }

    /**
     * Show the form for creating a new Ticket.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect(route('admin.tickets.index'));
    }

    /**
     * Store a newly created Ticket in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        return redirect(route('admin.tickets.index'));
    }

    /**
     * Display the specified Ticket.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        return redirect(route('admin.tickets.edit', $id));
    }

    /**
     * Show the form for editing the specified Ticket.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $ticket = $this->ticketRepository->findWithoutFail($id);

        if (empty($ticket)) {
            Flash::error('Ticket not found');

            return redirect(route('admin.tickets.index'));
        }

        $messages = $ticket->ticketMessages()->orderBy('id', 'desc')->get();

        return view('admin.tickets.edit')->with('ticket', $ticket)->with('messages', $messages);
    }

    /**
     * Update the specified Ticket in storage.
     *
     * @param $id
     * @param UpdateTicketRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateTicketRequest $request)
    {
        $ticket = $this->ticketRepository->findWithoutFail($id);

        if (empty($ticket)) {
            Flash::error('Ticket not found');

            return redirect(route('admin.tickets.index'));
        }

        $this->ticketRepository->update($request->all(), $id);

        Flash::success('Ticket updated successfully.');

        return redirect(route('admin.tickets.index'));
    }

    /**
     * Remove the specified Ticket from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $ticket = $this->ticketRepository->findWithoutFail($id);

        if (empty($ticket)) {
            Flash::error('Ticket not found');

            return redirect(route('admin.tickets.index'));
        }

        $this->ticketRepository->delete($id);

        Flash::success('Ticket deleted successfully.');

        return redirect(route('admin.tickets.index'));
    }
}
