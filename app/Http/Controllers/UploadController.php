<?php

namespace App\Http\Controllers;

use App\Models\TimeTrackingEvents;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(private UploadService $service) {}

    // Upload JSON file
    public function store(Request $request): JsonResponse
    {
        // $file = $request->file('file');
        // $path = $file->store('imports');
        // $chunkSize = (int) ($request->input('chunk_size', 500));

        // $import = $this->service->create($path, $file->getClientOriginalName(), $chunkSize);

        return response()->json(['id' => '', 'status' => ''], 201);
    }

    // // list imports (with pagination)
    public function index(): JsonResponse
    {
        return response()->json(['ping' => 'pong']);
    }

    // // show single import with logs
    // public function show(Import $import): JsonResponse
    // {
    //     $import->load(['logs' => function($q){ $q->latest()->limit(200); }]);
    //     return response()->json($import);
    // }

    // // reprocess
    // public function reprocess(Import $import): JsonResponse
    // {
    //     $this->service->reprocess($import);
    //     return response()->json(['id'=>$import->id,'status'=>$import->status]);
    // }
}
