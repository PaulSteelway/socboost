<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqDataTable;
use App\Http\Requests\CreateFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\FaqCategory;
use App\Repositories\FaqRepository;
use Flash;
use App\Http\Controllers\AppBaseController;

class FaqController extends AppBaseController
{
    /** @var  FaqRepository */
    private $faqRepository;

    public function __construct(FaqRepository $faqRepo)
    {
        $this->faqRepository = $faqRepo;
    }

    /**
     * Display a listing of the Faq.
     *
     * @param FaqDataTable $faqDataTable
     *
     * @return mixed
     */
    public function index(FaqDataTable $faqDataTable)
    {
        return $faqDataTable->render('admin.faqs.index');
    }

    /**
     * Show the form for creating a new Faq.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = FaqCategory::where('status', 1)->orderBy('order', 'asc')->pluck('name_en', 'id');

        return view('admin.faqs.create')->with('categories', $categories);
    }

    /**
     * Store a newly created Faq in storage.
     *
     * @param CreateFaqRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateFaqRequest $request)
    {
        $input = $request->all();

        $this->faqRepository->create($input);

        Flash::success('Faq saved successfully.');

        return redirect(route('admin.faqs.index'));
    }

    /**
     * Display the specified Faq.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        return redirect(route('admin.faqs.edit', $id));
    }

    /**
     * Show the form for editing the specified Faq.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $faq = $this->faqRepository->findWithoutFail($id);

        if (empty($faq)) {
            Flash::error('Faq not found');

            return redirect(route('admin.faqs.index'));
        }

        $categories = FaqCategory::where('status', 1)->orderBy('order', 'asc')->pluck('name_en', 'id');

        return view('admin.faqs.edit')->with('faq', $faq)->with('categories', $categories);
    }

    /**
     * Update the specified Faq in storage.
     *
     * @param $id
     * @param UpdateFaqRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateFaqRequest $request)
    {
        $faq = $this->faqRepository->findWithoutFail($id);

        if (empty($faq)) {
            Flash::error('Faq not found');

            return redirect(route('admin.faqs.index'));
        }

        $this->faqRepository->update($request->all(), $id);

        Flash::success('Faq updated successfully.');

        return redirect(route('admin.faqs.index'));
    }

    /**
     * Remove the specified Faq from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $faq = $this->faqRepository->findWithoutFail($id);

        if (empty($faq)) {
            Flash::error('Faq not found');

            return redirect(route('admin.faqs.index'));
        }

        $this->faqRepository->delete($id);

        Flash::success('Faq deleted successfully.');

        return redirect(route('admin.faqs.index'));
    }
}
