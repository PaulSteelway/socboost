<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BlogCategoryDataTable;
use App\DataTables\BlogEntryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBlogEntryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Requests\UpdateBlogEntryRequest;
use App\Repositories\BlogCategoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;

class BlogCategoryController extends AppBaseController
{
    /** @var  BlogCategoryRepository */
    private $BlogCategoryRepository;

    public function __construct(BlogCategoryRepository $blogCategoryRepo)
    {
        $this->BlogCategoryRepository = $blogCategoryRepo;
    }

    /**
     * Display a listing of the BlogCategory.
     *
     * @param BlogCategoryDataTable $blogCategoryDataTable
     * @return Response
     */
    public function index(BlogCategoryDataTable $blogCategoryDataTable)
    {
        return $blogCategoryDataTable->render('admin.blog_categories.index');
    }

    /**
     * Show the form for creating a new BlogEntry.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.blog_categories.create');
    }

    /**
     * Store a newly created BlogEntry in storage.
     *
     * @param CreateBlogCategoryRequest $request
     *
     * @return Response
     */
    public function store(Requests\CreateBlogCategoryRequest $request)
    {
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);

        $blogEntry = $this->BlogCategoryRepository->create($input);

        Flash::success('Blog Entry saved successfully.');

        return redirect(route('admin.blog_categories.index'));
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
        $blogEntry = $this->BlogCategoryRepository->findWithoutFail($id);

        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blog_categories.index'));
        }

        return view('admin.blog_categories.edit')->with('blogEntry', $blogEntry);
    }

    /**
     * Update the specified BlogEntry in storage.
     *
     * @param  int              $id
     * @param UpdateBlogCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBlogCategoryRequest $request)
    {
        $blogEntry = $this->BlogCategoryRepository->findWithoutFail($id);
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);
        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blog_categories.index'));
        }

        $blogEntry = $this->BlogCategoryRepository->update($input, $id);

        Flash::success('Blog Entry updated successfully.');

        return redirect(route('admin.blog_categories.index'));
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
        $blogEntry = $this->BlogCategoryRepository->findWithoutFail($id);

        if (empty($blogEntry)) {
            Flash::error('Blog Entry not found');

            return redirect(route('admin.blog_categories.index'));
        }

        $this->BlogCategoryRepository->delete($id);

        Flash::success('Blog Entry deleted successfully.');

        return redirect(route('admin.blog_categories.index'));
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
