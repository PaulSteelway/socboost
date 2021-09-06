<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BlogEntryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBlogEntryRequest;
use App\Http\Requests\UpdateBlogEntryRequest;
use App\Repositories\BlogEntryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;

class BlogEntryController extends AppBaseController
{
    /** @var  BlogEntryRepository */
    private $blogEntryRepository;

    public function __construct(BlogEntryRepository $blogEntryRepo)
    {
        $this->blogEntryRepository = $blogEntryRepo;
    }

    /**
     * Display a listing of the BlogEntry.
     *
     * @param BlogEntryDataTable $blogEntryDataTable
     * @return Response
     */
    public function index(BlogEntryDataTable $blogEntryDataTable)
    {
        return $blogEntryDataTable->render('admin.blog_entries.index');
    }

    /**
     * Show the form for creating a new BlogEntry.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.blog_entries.create');
    }

    /**
     * Store a newly created BlogEntry in storage.
     *
     * @param CreateBlogEntryRequest $request
     *
     * @return Response
     */
    public function store(CreateBlogEntryRequest $request)
    {
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);

        $blogEntry = $this->blogEntryRepository->create($input);

        Flash::success('Blog Entry saved successfully.');

        return redirect(route('admin.blogEntries.index'));
    }



    /**
     * Show the form for editing the specified BlogEntry.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $blogEntry = $this->blogEntryRepository->findWithoutFail($id);

        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blogEntries.index'));
        }

        return view('admin.blog_entries.edit')->with('blogEntry', $blogEntry);
    }

    /**
     * Update the specified BlogEntry in storage.
     *
     * @param  int              $id
     * @param UpdateBlogEntryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBlogEntryRequest $request)
    {
        $blogEntry = $this->blogEntryRepository->findWithoutFail($id);
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);
        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blogEntries.index'));
        }

        $blogEntry = $this->blogEntryRepository->update($input, $id);

        Flash::success('Blog Entry updated successfully.');

        return redirect(route('admin.blogEntries.index'));
    }

    /**
     * Remove the specified BlogEntry from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $blogEntry = $this->blogEntryRepository->findWithoutFail($id);

        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blogEntries.index'));
        }

        $this->blogEntryRepository->delete($id);

        Flash::success('Blog Entry deleted successfully.');

        return redirect(route('admin.blogEntries.index'));
    }


    public function saveIconImages($request, array $input): array
    {
        if ($request->has('image')) {
            $res = Storage::disk('public_folder')->putFileAs('img/blog', $request->image, $request->image->getClientOriginalName());
            $input['image'] = $res;
        }

        return $input;
    }
}
