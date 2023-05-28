<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'projects' => Project::all()
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
            'started_at' => 'date',
            'latitude' => 'string',
            'longitude' => 'string',
            'orientation' => 'string',
            'power_peak' => 'string',
            'area' => 'string',
            'inclination' => 'string',
        ]);

        $result = [];

        try {
            $projectInputs = ['name', 'description', 'started_at'];
            $projectData = $request->only($projectInputs);
            $projectData['user_id'] = $request->user()->id;
            $project = new Project($projectData);
            if ($project->save()) {
                $result = [
                    'message' => 'Project created successfully.',
                    'project' => $project
                ];

                if ($request->has('longitude') && $request->has('latitude')) {
                    $data = array_merge($request->except($projectInputs), [
                        'user_id' => $projectData['user_id'],
                        'elementable_type' => Project::class,
                        'elementable_id' => $project->id,
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
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'project' => $project
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function products(Project $project): JsonResponse
    {
        return response()->json([
            'project' => $project,
            'products' => Product::query()->where('project_id', $project->id)->get()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function addProduct(Request $request, Project $project): JsonResponse
    {
        $request->validate([
            'name' => 'string|required',
            'description' => 'string',
        ]);
        $result = [];
        try {
            $data = $request->all();
            $data['project_id'] = $project->id;
            $product = new Product($data);
            if ($product->save()) {
                $result = [
                    'project' => $project,
                    'product' => $product
                ];
            }
        } catch (Exception $exc) {
            $result['error'] = 'Unexpected error: ' . $exc->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return JsonResponse
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
        ]);

        $result = [];

        try {
            if ($project->update($data)) {
                $result = [
                    'message' => 'Project updated successfully.',
                    'project' => $project
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
     * @param Project $project
     * @return JsonResponse
     */
    public function destroy(Project $project): JsonResponse
    {
        $result = [];

        try {
            if ($project->delete()) {
                $result = [
                    'message' => 'Project deleted successfully.'
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
