<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserReferralDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserReferralRequest;
use App\Http\Requests\UpdateUserReferralRequest;
use App\Models\User;
use App\Models\UserReferral;
use App\Repositories\UserReferralRepository;
use Flash;

class UserReferralController extends AppBaseController
{
    /** @var  UserReferralRepository */
    private $userReferralRepository;

    public function __construct(UserReferralRepository $userReferralRepo)
    {
        $this->userReferralRepository = $userReferralRepo;
    }

    /**
     * Display a listing of the UserReferral.
     *
     * @param UserReferralDataTable $userReferralDataTable
     * @return mixed
     */
    public function index(UserReferralDataTable $userReferralDataTable)
    {
        return $userReferralDataTable->render('admin.user_referrals.index');
    }

    /**
     * Show the form for creating a new UserReferral.
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        $referrals = User::where('id', '<>', $user->id)->get()->pluck('email', 'id');

        return view('admin.user_referrals.create')->with('user', $user)->with('referrals', $referrals);
    }

    /**
     * Store a newly created UserReferral in storage.
     *
     * @param CreateUserReferralRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateUserReferralRequest $request)
    {
        $input = $request->all();

        $this->userReferralRepository->create($input);

        Flash::success('User Referral saved successfully.');

        return redirect(route('admin.userReferrals.index'));
    }

    /**
     * Show the form for editing the specified UserReferral.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('admin.userReferrals.index'));
        }

        $userReferral = $user->userReferral;
        if (empty($userReferral)) {
            return redirect(route('admin.userReferrals.create', $user->id));
        }

        $referrals = User::where('id', '<>', $user->id)->get()->pluck('email', 'id');

        return view('admin.user_referrals.edit')->with('userReferral', $userReferral)->with('referrals', $referrals);
    }

    /**
     * Update the specified UserReferral in storage.
     *
     * @param $id
     * @param UpdateUserReferralRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateUserReferralRequest $request)
    {
        $userReferral = $this->userReferralRepository->findWithoutFail($id);

        if (empty($userReferral)) {
            Flash::error('User Referral not found');
            return redirect(route('admin.userReferrals.index'));
        }

        $this->userReferralRepository->update($request->all(), $id);

        Flash::success('User Referral updated successfully.');

        return redirect(route('admin.userReferrals.index'));
    }

    /**
     * Remove the specified UserReferral from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $userReferral = $this->userReferralRepository->findWithoutFail($id);

        if (empty($userReferral)) {
            Flash::error('User Referral not found');
            return redirect(route('admin.userReferrals.index'));
        }

        $this->userReferralRepository->delete($id);

        Flash::success('User Referral deleted successfully.');

        return redirect(route('admin.userReferrals.index'));
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function generate(User $user)
    {
        UserReferral::createUserReferralLink($user);
        return redirect(route('admin.userReferrals.edit', $user->id));
    }
}
