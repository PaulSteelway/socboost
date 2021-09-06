<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Kamaln7\Toastr\Facades\Toastr;

class TicketsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(10);
        return view('profile.tickets.index')->with('tickets', $tickets);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('profile.tickets.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'subject' => 'required|in:' . implode(',', array_keys(config('enumerations')['tickets']['subjects'])),
                'message' => 'required|string|min:6',
            ]);

            /** @var Ticket $ticket */
            $ticket = Ticket::create(['user_id' => Auth::id(), 'subject' => $request->get('subject')]);
            $ticket->ticketMessages()->create(['user_id' => Auth::id(), 'message' => $request->get('message')]);

            Toastr::success(__('Ticket added successfully!'), null, [
                    'positionClass' => 'toast-bottom-right notification-custom-place',
                    'hideDuration' => 1000,
                ]
            );

            return redirect(route('profile.tickets.index'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->first();
        if (empty($ticket)) {
            Toastr::error(__('Ticket does not exist.'), null, [
                    'positionClass' => 'toast-bottom-right notification-custom-place',
                    'hideDuration' => 1000,
                ]
            );
            return redirect(route('profile.tickets.index'));
        }
        $messages = $ticket->ticketMessages()->orderBy('id', 'desc')->get();

        return view('profile.tickets.show')->with('ticket', $ticket)->with('messages', $messages);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->first();
        if (empty($ticket)) {
            Toastr::error(__('Ticket does not exist.'), null, [
                    'positionClass' => 'toast-bottom-right notification-custom-place',
                    'hideDuration' => 1000,
                ]
            );
            return redirect(route('profile.tickets.index'));
        }
        return view('profile.tickets.edit')->with('ticket', $ticket);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->first();
            if (empty($ticket)) {
                Toastr::error(__('Ticket does not exist.'), null, [
                        'positionClass' => 'toast-bottom-right notification-custom-place',
                        'hideDuration' => 1000,
                    ]
                );
                return redirect(route('profile.tickets.index'));
            }

            $this->validate($request, [
                'message' => 'required|string|min:6',
            ]);

            $ticket->ticketMessages()->create(['user_id' => Auth::id(), 'message' => $request->get('message')]);
            $ticket->status = 1;
            $ticket->save();

            Toastr::success(__('New message added successfully!'), null, [
                    'positionClass' => 'toast-bottom-right notification-custom-place',
                    'hideDuration' => 1000,
                ]
            );

            return redirect(route('profile.tickets.show', $ticket->id));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
}
