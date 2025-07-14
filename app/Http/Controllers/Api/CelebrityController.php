<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCelebrityRequest;
use App\Services\DynamoDB\CelebrityService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CelebrityController extends Controller
{
    public function store(StoreCelebrityRequest $request, CelebrityService $service)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        $celeb = $service->create($data);

        return response()->json(['status' => 'success', 'data' => $celeb]);
    }

    public function show(string $id, CelebrityService $service)
    {
        $celeb = $service->get($id);

        if (!$celeb) {
            return response()->json(['status' => 'error', 'message' => 'Celebrity not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $celeb]);
    }

    public function update(StoreCelebrityRequest $request, string $id, CelebrityService $service)
    {
        $data = $request->validated();
        $celeb = $service->update($id, $data);

        if (!$celeb) {
            return response()->json(['status' => 'error', 'message' => 'Celebrity not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $celeb]);
    }

    public function destroy(string $id, CelebrityService $service)
    {
        $success = $service->delete($id);

        if (!$success) {
            return response()->json(['status' => 'error', 'message' => 'Celebrity not found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Celebrity deleted']);
    }
}
