<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateTicketMessageRequest;
use App\Http\Requests\UpdateTicketMessageRequest;
use App\Repositories\TicketMessageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;

class TicketMessageController extends AppBaseController
{
    /** @var  TicketMessageRepository */
    private $ticketMessageRepository;

    public function __construct(TicketMessageRepository $ticketMessageRepo)
    {
        $this->ticketMessageRepository = $ticketMessageRepo;
    }

    /**
     * Display a listing of the TicketMessage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {
        return redirect(route('admin.tickets.index'));
    }

    /**
     * Show the form for creating a new TicketMessage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('admin.ticket_messages.create')->with('ticket_id', $request->get('ticket_id'));
    }

    /**
     * Store a newly created TicketMessage in storage.
     *
     * @param CreateTicketMessageRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateTicketMessageRequest $request)
    {
        $input = $request->all();

        $input['user_id'] = Auth::id();

        $ticketMessage = $this->ticketMessageRepository->create($input);

        $ticketMessage->ticket->status = 2;
        $ticketMessage->ticket->save();

        Flash::success('Ticket Message saved successfully.');

        return redirect(route('admin.tickets.edit', $ticketMessage->ticket_id));
    }

    /**
     * Display the specified TicketMessage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect(route('admin.ticketMessages.edit', $id));
    }

    /**
     * Show the form for editing the specified TicketMessage.
     *
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $ticketMessage = $this->ticketMessageRepository->findWithoutFail($id);
        $ticketId = $request->get('ticket_id');
        if (empty($ticketMessage)) {
            Flash::error('Ticket Message not found');
            return redirect(route('admin.ticket.edit', $ticketId));
        }
        return view('admin.ticket_messages.edit')->with('ticketMessage', $ticketMessage)->with('ticket_id', $ticketId);
    }

    /**
     * Update the specified TicketMessage in storage.
     *
     * @param $id
     * @param UpdateTicketMessageRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateTicketMessageRequest $request)
    {
        $ticketMessage = $this->ticketMessageRepository->findWithoutFail($id);

        if (empty($ticketMessage)) {
            Flash::error('Ticket Message not found');

            return redirect(route('admin.tickets.index'));
        }

        $ticketMessage = $this->ticketMessageRepository->update($request->all(), $id);

        Flash::success('Ticket Message updated successfully.');

        return redirect(route('admin.tickets.edit', $ticketMessage->ticket_id));
    }

    /**
     * Remove the specified TicketMessage from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $ticketMessage = $this->ticketMessageRepository->findWithoutFail($id);

        if (empty($ticketMessage)) {
            Flash::error('Ticket Message not found');

            return redirect(route('admin.tickets.index'));
        }

        $this->ticketMessageRepository->delete($id);

        Flash::success('Ticket Message deleted successfully.');

        return redirect(route('admin.tickets.edit', $ticketMessage->ticket_id));
    }
}
