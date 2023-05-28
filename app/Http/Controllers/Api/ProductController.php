<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'products' => Product::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'string|required',
            'description' => 'string',
            'latitude' => 'string',
            'longitude' => 'string',
            'orientation' => 'string',
            'power_peak' => 'string',
            'area' => 'string',
            'inclination' => 'string',
            'project_id' => 'numeric|exists:projects,id'
        ]);

        $result = [];

        try {
            $productInputs = ['name', 'description'];
            $productData = $request->only($productInputs);
            $productData['user_id'] = $request->user()->id;
            $product = new Product($productData);
            if ($product->save()) {
                $result = [
                    'message' => 'Product created successfully.',
                    'product' => $product
                ];

                if ($request->has('longitude') && $request->has('latitude')) {
                    $data = array_merge($request->except($productInputs), [
                        'user_id' => $productData['user_id'],
                        'elementable_type' => Product::class,
                        'elementable_id' => $product->id,
                    ]);
                    $setting = new Setting($data);
                    if ($setting->save()) {
                        $result['setting'] = $setting;
                    }
                }
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function products(Product $product): JsonResponse
    {
        return response()->json([
            'product' => $product,
            'products' => Product::query()->where('product_id', $product->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
        ]);

        $result = [];

        try {
            if ($product->update($data)) {
                $result = [
                    'message' => 'Product updated successfully.',
                    'product' => $product
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $result = [];

        try {
            if ($product->delete()) {
                $result = [
                    'message' => 'Product deleted successfully.'
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }
}
