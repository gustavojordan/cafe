<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsumerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumerController extends Controller
{
    private $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function index()
    {
        $consumer = $this->consumer->paginate('10');
        return response()->json($consumer, 200);
    }

    public function show($id)
    {
        Validator::make(['consumer_id' => $id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $consumer = $this->consumer->findOrFail($id);
            return response()->json([
                'data' => [
                    $consumer
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
    public function store(ConsumerRequest $request)
    {
        $data = $request->all();
        try {
            $consumer = $this->consumer->create($data);
            return response()->json([
                'data' => [
                    'message' => 'Consumer was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($id, ConsumerRequest $request)
    {
        $data = $request->all();
        try {
            $consumer = $this->consumer->findOrFail($id);
            $consumer->update($data);
            return response()->json([
                'data' => [
                    'message' => 'Consumer was updated'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        Validator::make(['consumer_id' => $id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $consumer = $this->consumer->findOrFail($id);
            $consumer->delete();
            return response()->json([
                'data' => [
                    'message' => 'Consumer was deleted'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function consume($id, Request $request)
    {
        $request->request->add(['consumer_id' => $id]);

        Validator::make($request->all(), [
            'drink_id' => ['required', 'exists:drink,drink_id'],
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();

        $data = $request->all();
        try {
            $consumer = $this->consumer->findOrFail($id);
            $consumer->consumption()->attach(
                [$id => ['drink_id' => $data['drink_id']]]
            );
            return response()->json([
                'data' => [
                    'message' => 'Consumer consumed'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function consumption($id)
    {
        Validator::make(['consumer_id' => $id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $consumer = $this->consumer->findOrFail($id);
            return response()->json([
                'data' => [
                    $consumer->consumption
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
