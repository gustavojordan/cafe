<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate('10');
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        try {
            $this->user->beginTransaction();
            $data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data);
            $this->user->commit();

            return response()->json([
                'data' => [
                    'message' => 'User was registered'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->user->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        Validator::make(['user_id' => $user_id], [
            'user_id' => ['required', 'exists:user,user_id']
        ])->validate();
        try {
            $user = $this->user->with('consumer')->findOrFail($user_id);
            return response()->json([
                'data' => [
                    $user
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $user_id)
    {
        $data = $request->all();
        try {
            $this->user->beginTransaction();

            if ($request->has('password') || $request->get('password')) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $this->user->findOrFail($user_id);
            $user->update($data);
            $this->user->commit();

            return response()->json([
                'data' => [
                    'message' => 'User was updated'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->user->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        Validator::make(['user_id' => $user_id], [
            'user_id' => ['required', 'exists:user,user_id']
        ])->validate();
        try {
            $this->user->beginTransaction();
            $user = $this->user->findOrFail($user_id);
            $user->delete();
            $this->user->commit();

            return response()->json([
                'data' => [
                    'message' => 'User was deleted'
                ]
            ], 200);
        } catch (\Exception $e) {
            $this->user->rollback();
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
