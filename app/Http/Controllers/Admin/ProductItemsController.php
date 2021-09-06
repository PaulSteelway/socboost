<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductItemsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductItemRequest;
use App\Http\Requests\UpdateProductItemRequest;
use App\Models\Product;
use App\Repositories\ProductItemsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ProductItemsController extends AppBaseController
{
    /** @var  ProductItemsRepository */
    private $productItemRepository;

    public function __construct(ProductItemsRepository $productItemRepo)
    {
        $this->productItemRepository = $productItemRepo;
    }

    /**
     * Display a listing of the ProductItem.
     *
     * @param ProductItemsDataTable $productItemDataTable
     * @return Response
     */
    public function index(ProductItemsDataTable $productItemDataTable)
    {
        return $productItemDataTable->render('admin.product_items.index');
    }

    /**
     * Show the form for creating a new ProductItem.
     *
     * @return Response
     */
    public function create()
    {
        $products = Product::pluck('name_ru', 'id')->all();
        return view('admin.product_items.create')->with('products', $products);
    }

    /**
     * Store a newly created ProductItem in storage.
     *
     * @param CreateProductItemRequest $request
     *
     * @return Response
     */
    public function store(CreateProductItemRequest $request)
    {
        $native_input = $request->all();
        $keys = isset($native_input['keys']) ? $native_input['keys'] : [];
        $values = isset($native_input['values']) ? $native_input['values'] : [];
        if (count($keys) != count($values)) {
            Flash::error('Keys and Error havent count of elements');
            $products = Product::pluck('name_ru', 'id')->all();
            return view('admin.product_items.create')->with('products', $products);
        }
        $actual_username='';

        foreach ($keys as $key=>$value) {
            $actual_username .= $keys[$key].'::'.$values[$key].';;';
        }
        $actual_input = ['username'=>$actual_username, 'password'=>$native_input['password_field'],
            'product_id'=>$native_input['product_id']];

        $productItem = $this->productItemRepository->create($actual_input);

        Flash::success('Product Item saved successfully.');

        return redirect(route('admin.productItems.index'));
    }

    /**
     * Display the specified ProductItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $productItem = $this->productItemRepository->findWithoutFail($id);

        if (empty($productItem)) {
            Flash::error('Product Item not found');

            return redirect(route('admin.productItems.index'));
        }

        return view('admin.product_items.show')->with('productItem', $productItem);
    }

    /**
     * Show the form for editing the specified ProductItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productItem = $this->productItemRepository->findWithoutFail($id);
        $products = Product::pluck('name_ru', 'id')->all();

        if (empty($productItem)) {
            Flash::error('Product Item not found');

            return redirect(route('admin.productItems.index'));
        }

        return view('admin.product_items.edit')->with('productItem', $productItem)->with('products',$products);
    }

    /**
     * Update the specified ProductItem in storage.
     *
     * @param  int              $id
     * @param UpdateProductItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductItemRequest $request)
    {
        $productItem = $this->productItemRepository->findWithoutFail($id);

        if (empty($productItem)) {
            Flash::error('Product Item not found');

            return redirect(route('admin.productItems.index'));
        }

        $productItem = $this->productItemRepository->update($request->all(), $id);

        Flash::success('Product Item updated successfully.');

        return redirect(route('admin.productItems.index'));
    }

    /**
     * Remove the specified ProductItem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $productItem = $this->productItemRepository->findWithoutFail($id);

        if (empty($productItem)) {
            Flash::error('Product Item not found');

            return redirect(route('admin.productItems.index'));
        }

        $this->productItemRepository->delete($id);

        Flash::success('Product Item deleted successfully.');

        return redirect(route('admin.productItems.index'));
    }
}
