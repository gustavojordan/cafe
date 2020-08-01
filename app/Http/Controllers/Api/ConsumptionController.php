<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Consumption;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsumptionRequest;
use Illuminate\Support\Facades\Validator;

class ConsumptionController extends Controller
{
    private $consumption;

    public function __construct(Consumption $consumption)
    {
        $this->consumption = $consumption;
    }

    public function store(ConsumptionRequest $request)
    {
        $this->consumption->beginTransaction();

        $data = $request->all();
        try {
            $consumption = $this->consumption->create($data);
            $this->consumption->commit();
            return response()->json([
                'data' => [
                    'message' => 'Consumption was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function index()
    {
        $consumption = $this->consumption->paginate('10');
        return response()->json($consumption, 200);
    }

    public function show($consumption_id)
    {
        Validator::make(['consumption_id' => $consumption_id], [
            'consumption_id' => ['required', 'exists:consumption,consumption_id']
        ])->validate();
        try {
            $consumption = $this->consumption->findOrFail($consumption_id);
            return response()->json([
                'data' => [
                    $consumption->get()
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
