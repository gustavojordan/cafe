<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Drink;
use App\Http\Controllers\Controller;
use App\Http\Requests\DrinkRequest;
use Illuminate\Support\Facades\Validator;

class DrinkController extends Controller
{

    private $drink;

    public function __construct(Drink $drink)
    {
        $this->drink = $drink;
    }

    public function index()
    {
        $drinks = $this->drink->paginate('10');
        return response()->json($drinks, 200);
    }

    public function show($drink_id)
    {
        Validator::make(['drink_id' => $drink_id], [
            'drink_id' => ['required', 'exists:drink,drink_id']
        ])->validate();
        try {
            $drink = $this->drink->findOrFail($drink_id);
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
            $this->drink->beginTransaction();
            $drink = $this->drink->create($data);
            $this->drink->commit();
            return response()->json([
                'data' => [
                    'message' => 'Drink was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            $this->drink->rollback();
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($drink_id, DrinkRequest $request)
    {
        $data = $request->all();
        try {
            $this->drink->beginTransaction();

            $drink = $this->drink->findOrFail($drink_id);
            $drink->update($data);
            $this->drink->commit();

            return response()->json([
                'data' => [
                    'message' => 'Drink was updated'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->drink->rollback();

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($drink_id)
    {
        Validator::make(['drink_id' => $drink_id], [
            'drink_id' => ['required', 'exists:drink,drink_id']
        ])->validate();
        try {
            $this->drink->beginTransaction();
            $drink = $this->drink->findOrFail($drink_id);
            $drink->delete();
            $this->drink->commit();

            return response()->json([
                'data' => [
                    'message' => 'Drink was deleted'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->drink->rollback();

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
