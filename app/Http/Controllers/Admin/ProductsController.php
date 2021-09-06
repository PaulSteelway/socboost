<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\AccountCategory;
use App\Models\ProductItem;
use App\Repositories\ProductsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Storage;

class ProductsController extends AppBaseController
{
    /** @var  ProductsRepository */
    private $productRepository;

    public function __construct(ProductsRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param ProductsDataTable $productDataTable
     * @return Response
     */
    public function index(ProductsDataTable $productDataTable)
    {
        return $productDataTable->render('admin.products.index');
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $categories = AccountCategory::pluck('name_ru', 'id')->all();
        return view('admin.products.create')->with('categories', $categories);
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);
        if (isset($input['account'])) {
            $accounts = $input['account'];
            unset($input['account']);
        }

        $product = $this->productRepository->create($input);
        if (isset($accounts)) {
            $this->saveProductItems($accounts, $product);

        }
        Flash::success('Product saved successfully.');

        return redirect(route('admin.products.index'));
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }

        return view('admin.products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findWithoutFail($id);
        $categories = AccountCategory::pluck('name_ru', 'id')->all();
        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }

        return view('admin.products.edit')->with('product', $product)->with('categories', $categories);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductRequest $request)
    {
        $product = $this->productRepository->findWithoutFail($id);
        $input = $request->all();
        $input = $this->saveIconImages($request, $input);

        if (isset($input['account'])) {
            $accounts = $input['account'];
            unset($input['account']);
        }

        if (isset($input['exist_account'])) {
            $exist_account = $input['exist_account'];
            unset($input['exist_account']);
        }
        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }

        $product = $this->productRepository->update($input, $id);
        ProductItem::where('product_id', $product->id)->delete();
        if (isset($accounts)) {
            $this->saveProductItems($accounts, $product);
        }
        if (isset($exist_account)) {
            $this->saveProductItems($exist_account, $product);
        }
        Flash::success('Product updated successfully.');

        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('admin.products.index'));
        }

        $this->productRepository->delete($id);

        Flash::success('Product deleted successfully.');

        return redirect(route('admin.products.index'));
    }


    /**
     * @param  $request
     * @param array $input
     * @return array
     */
    public function saveIconImages($request, array $input): array
    {
        if ($request->has('icon_img')) {
            $res = Storage::disk('public_folder')->putFileAs('img/account_images', $request->icon_img, $request->icon_img->getClientOriginalName());
            $input['icon_img'] = $res;
        }
        if ($request->has('icon_img_active')) {
            $res = Storage::disk('public_folder')->putFileAs('img/account_images', $request->icon_img_active, $request->icon_img_active->getClientOriginalName());
            $input['icon_img_active'] = $res;
        }
        return $input;
    }

    /**
     * @param $accounts
     * @param $product
     */
    private function saveProductItems($accounts, $product): void
    {
        foreach ($accounts as $account) {
            $prodItem = new ProductItem([
                'username' => $account['username'],
                'product_id' => $product->id,
            ]);
            $prodItem->save();
        }
    }
}
