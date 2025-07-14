<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;
use App\Services\DynamoDB\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function store(StoreActivityRequest $request, ActivityService $service)
    {
        $data = $request->validated();
        $data['activity_id'] = (string) Str::uuid(); // optional
        $activity = $service->create($data);

        return response()->json(['status' => 'success', 'data' => $activity]);
    }

    public function show(string $id, ActivityService $service)
    {
        $activity = $service->get($id);

        if (!$activity) {
            return response()->json(['status' => 'error', 'message' => 'Activity not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $activity]);
    }

    public function update(StoreActivityRequest $request, string $id, ActivityService $service)
    {
        $data = $request->validated();
        $activity = $service->update($id, $data);

        if (!$activity) {
            return response()->json(['status' => 'error', 'message' => 'Activity not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $activity]);
    }

    public function destroy(string $id, ActivityService $service)
    {
        $success = $service->delete($id);

        if (!$success) {
            return response()->json(['status' => 'error', 'message' => 'Activity not found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Activity deleted']);
    }
}
