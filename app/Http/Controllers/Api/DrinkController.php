<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\DrinkRequest;

class DrinkController extends Controller
{
    public function index()
    {
        $drinks = $this->drink->paginate('10');
        return response()->json($drinks, 200);
    }

    public function show($id)
    {
        try {
            $drink = $this->drink->findOrFail($id);
            return response()->json([
                'data' => [
                    $drink
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
    public function store(DrinkRequest $request)
    {
        $data = $request->all();
        try {
            $drink = $this->drink->create($data);
            return response()->json([
                'data' => [
                    'message' => 'Drink was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($id, DrinkRequest $request)
    {
        $data = $request->all();
        try {
            $drink = $this->drink->findOrFail($id);
            $drink->update($data);
            return response()->json([
                'data' => [
                    'message' => 'Drink was updated'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $drink = $this->drink->findOrFail($id);
            $drink->delete();
            return response()->json([
                'data' => [
                    'message' => 'Drink was deleted'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
