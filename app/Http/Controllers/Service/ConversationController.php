<?php

namespace App\Http\Controllers\Service;

use App\Http\Requests;
use App\Http\Requests\Service\IdRequest;
use App\Http\Requests\Service\MessageRequest;

use App\Models\Conversation_item;
use App\Models\LBM_conversation;
use App\Models\LBM_conversation_item;
use App\Models\LBM_conversation_user;
use App\Order_item;
use App\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use Auth;
use Log;
use Illuminate\Support\Facades\DB;

class ConversationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $conversations = LBM_conversation::with("last_user", "last_user.profile.avatar", "users.profile")
        ->whereHas("users", function ($q) use ($user_id) {
            $q->where('users.id', $user_id);
        })
        ->orderBy("updated_at", "desc")->get();
        return $conversations;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(IdRequest $request)
    {
        \DB::beginTransaction();
        try
        {
            $check = false;
            $data = LBM_conversation_user::select('conversation_id', DB::raw('COUNT(*) as total_conversation'))
                ->whereIn('user_id', [Auth::user()->id, $request->id])
                ->groupBy('conversation_id')->get();
            if (count($data) > 0)
            {
                foreach ($data as $data) 
                {
                    if ($data->total_conversation == 2)
                    {
                        $check = true;
                        if (!LBM_conversation::find($data->conversation_id))
                        {
                            $conversation = new LBM_conversation;
                            $conversation->id = $data->conversation_id;
                            $conversation->save();
                        }
                        \DB::commit();
                        return response([
                            'conversation_id' => $data->conversation_id,
                            'conversation' => LBM_conversation::whereId($data->conversation_id)->with("last_user", "last_user.profile.avatar", "users.profile")->first()
                        ]);
                    }
                }
            }
            // return $data;
            // $data = Auth::user()->conversations()->get();
            if (!$check) 
            {
                $conversation = new LBM_conversation;
                $conversation->save();
                $conversation->users()->sync([Auth::user()->id, $request->id]);
                \DB::commit();
                return response([
                    'conversation_id' => $data->conversation_id,
                    'conversation' => LBM_conversation::whereId($conversation->id)->with("last_user", "last_user.profile.avatar", "users.profile")->first()
                ]);
            }
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return $e->getMessage();
        }
    }


    public function addMessage(MessageRequest $request, $conversation_id)
    {
        $conversation = LBM_conversation::find($conversation_id);
        if ($conversation)
        {
            $conversation->addMessage($request->content);
            return response([
                "description" => "success"
            ]); 
        }
        else 
        {
            return $this->errorInvalid([
                'conversation_id' => ['conversation_id is invalid']
            ]);
        }
    }

    public function getMessage($conversation_id)
    {
        $conversation = LBM_conversation::find($conversation_id);
        if ($conversation)
        {
            return response([
                "data" => $conversation->items()->with("creator.profile")->get(), 
                "user" => Auth::user()
            ]);
        }
        else
        {
            return $this->errorInvalid([
                'conversation_id' => ['conversation_id is invalid']
            ]);
        }
    }

    public function getNewMessage(Request $request, $conversation_id) 
    {
        $conversation = LBM_conversation_item::with("creator.profile")->where([
            ['conversation_id', '=', $conversation_id],
            ['created_at', '>', $request->date],
        ])->orderBy('created_at')->get();
        return response([
            "data" => $conversation, 
            "user" => Auth::user()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {

    }
}
