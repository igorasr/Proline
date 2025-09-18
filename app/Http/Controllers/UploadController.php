<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\UploadHistory;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(private UploadService $service) {}

    public function store(UploadRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('imports');

        $import = $this->service->create($path, $file->getClientOriginalName());

        return response()->json(['id' => $import->id, 'status' => $import->status], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $perPage = max(1, min($perPage, 100)); // limita entre 1 e 100
        $page = (int) $request->query('page', 1);

        return response()->json(
            UploadHistory::latest()->paginate($perPage, ['*'], 'page', $page)
        );
    }

    public function show(UploadHistory $import): JsonResponse
    {
        return response()->json($import);
    }

    // // reprocess
    // public function reprocess(Import $import): JsonResponse
    // {
    //     $this->service->reprocess($import);
    //     return response()->json(['id'=>$import->id,'status'=>$import->status]);
    // }
}
