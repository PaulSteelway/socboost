<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Models\Category;
use App\Repositories\PackageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PackageController extends AppBaseController
{
    /** @var  PackageRepository */
    private $packageRepository;

    public function __construct(PackageRepository $packageRepo)
    {
        $this->packageRepository = $packageRepo;
    }

    /**
     * Display a listing of the Package.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
//        $this->packageRepository->pushCriteria(new RequestCriteria($request));
        $packages = $this->packageRepository->all();

        return view('admin.packages.index')
            ->with('packages', $packages);
    }

    /**
     * Show the form for creating a new Package.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::pluck('name_ru', 'id');
        return view('admin.packages.create')
            ->with('categories', $categories);
    }

    /**
     * Store a newly created Package in storage.
     *
     * @param CreatePackageRequest $request
     *
     * @return Response
     */
    public function store(CreatePackageRequest $request)
    {
        $input = $request->all();

        $package = $this->packageRepository->create($input);

        Flash::success('Package saved successfully.');

        return redirect(route('admin.packages.index'));
    }

    /**
     * Display the specified Package.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('Package not found');

            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.show')->with('package', $package);
    }

    /**
     * Show the form for editing the specified Package.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);
        $categories = Category::pluck('name_ru', 'id');

        if (empty($package)) {
            Flash::error('Package not found');

            return redirect(route('admin.packages.index'));
        }

        return view('admin.packages.edit')->with('package', $package)
            ->with('categories', $categories);
    }

    /**
     * Update the specified Package in storage.
     *
     * @param  int              $id
     * @param UpdatePackageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePackageRequest $request)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('Package not found');

            return redirect(route('admin.packages.index'));
        }

        $package = $this->packageRepository->update($request->all(), $id);

        Flash::success('Package updated successfully.');

        return redirect(route('admin.packages.index'));
    }

    /**
     * Remove the specified Package from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $package = $this->packageRepository->findWithoutFail($id);

        if (empty($package)) {
            Flash::error('Package not found');

            return redirect(route('admin.packages.index'));
        }

        $this->packageRepository->delete($id);

        Flash::success('Package deleted successfully.');

        return redirect(route('admin.packages.index'));
    }
}
