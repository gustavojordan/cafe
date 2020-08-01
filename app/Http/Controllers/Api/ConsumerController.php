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
        $consumer = $this->consumer->with('favoriteDrinks')->paginate('10');
        return response()->json($consumer, 200);
    }

    public function show($consumer_id)
    {
        Validator::make(['consumer_id' => $consumer_id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $consumer = $this->consumer->with('favoriteDrinks')->findOrFail($consumer_id);
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
            $this->consumer->beginTransaction();
            $consumer = $this->consumer->create($data);
            if (isset($data['favorite_drinks']) && count($data['favorite_drinks'])) {
                $consumer->saveDrinkFavorite()->sync($data['favorite_drinks']);
            }
            $this->consumer->commit();
            return response()->json([
                'data' => [
                    'message' => 'Consumer was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($consumer_id, Request $request)
    {
        $request->request->add(['consumer_id' => $consumer_id]);
        Validator::make(
            $request->all(),
            [
                'consumer_id' => ['required', 'exists:consumer,consumer_id'],
                'favorite_drinks.*' => ['required', 'exists:drink,drink_id'],
                'consumption_limit' => ['required']
            ]
        )->validate();
        $data = $request->only(['consumer_id', 'consumption_limit', 'favorite_drinks']);
        try {
            $this->consumer->beginTransaction();
            $consumer = $this->consumer->findOrFail($consumer_id);
            $consumer->update($data);
            if (isset($data['favorite_drinks']) && count($data['favorite_drinks'])) {
                $consumer->saveDrinkFavorite()->sync(
                    $data['favorite_drinks']
                );
            }
            $this->consumer->commit();
            return response()->json([
                'data' => [
                    'message' => 'Consumer was updated'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($consumer_id)
    {
        Validator::make(['consumer_id' => $consumer_id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $this->consumer->beginTransaction();
            $consumer = $this->consumer->findOrFail($consumer_id);
            $consumer->saveDrinkFavorite()->detach();
            $consumer->consumption()->sync(
                []
            );
            $consumer->delete();
            $this->consumer->commit();
            return response()->json([
                'data' => [
                    'message' => 'Consumer was deleted'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function consume($consumer_id, Request $request)
    {
        $request->request->add(['consumer_id' => $consumer_id]);

        Validator::make($request->all(), [
            'drink_id' => ['required', 'exists:drink,drink_id'],
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();

        $data = $request->all();
        try {
            $this->consumer->beginTransaction();
            $consumer = $this->consumer->findOrFail($consumer_id);
            $consumer->consumption()->attach(
                [$consumer_id => ['drink_id' => $data['drink_id']]]
            );
            $this->consumer->commit();
            return response()->json([
                'data' => [
                    'message' => 'Consumer consumed'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function consumption($consumer_id)
    {
        Validator::make(['consumer_id' => $consumer_id], [
            'consumer_id' => ['required', 'exists:consumer,consumer_id']
        ])->validate();
        try {
            $this->consumer->beginTransaction();
            $consumer = $this->consumer->findOrFail($consumer_id);
            $this->consumer->commit();
            return response()->json([
                'data' => [
                    $consumer->consumption
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->consumer->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
