<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LBM_conversation;
use App\Models\LBM_conversation_user;
use Auth;
use DB;

class LBM_conversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isSchoolAdmin())
        {
            $user = $user->profile;
            $school_id = $user->school_id;
            $admin_name = $user->where('school_id', $school_id)->get();
            $arr = [];
            foreach ($admin_name as $admin) {
                $arr[] = $admin->id;
            }
            $conversations = LBM_conversation::whereHas('users', function ($query) use ($arr) {
                $query->whereIn('profile_id', $arr)->where('profile_type', 'school');
            })
            ->with("last_user", "last_user.profile.avatar", "users.profile")
            ->orderBy("updated_at", "desc")->get();
        }
        else
        {
            $id = $user->id;
            $conversations = LBM_conversation::with("last_user", "last_user.profile.avatar", "users.profile")
            ->whereHas('users', function ($query) use ($id) {
                $query->where('users.id', $id);
            })
            ->get();
        }
        return $conversations;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $transaction = LBP_transaction::where("txn_id", $request->txn_id)->first();
        // if ($transaction)
        // {
        //     $ipn = new LBP_transaction_ipn;
        //     $ipn->ipn_version = $request->ipn_version;
        //     $ipn->ipn_id = $request->ipn_id;
        //     $ipn->ipn_mode = $request->ipn_mode;
        //     $ipn->merchant = $request->merchant;
        //     $ipn->ipn_type = $request->ipn_type;
        //     $ipn->status = $request->status;
        //     $ipn->status_text = $request->status_text;
        //     $ipn->currency1 = $request->currency1;
        //     $ipn->currency2 = $request->currency2;
        //     $ipn->fee = $request->fee;
        //     $ipn->amount1 = $request->amount1;
        //     $ipn->amount2 = $request->amount2;
        //     $ipn->fee = $request->fee;
        //     $ipn->buyer_name = $request->buyer_name;
        //     $ipn->received_amount = $request->received_amount;
        //     $ipn->received_confirms = $request->received_confirms;
        //     $ipn->lbp_transaction_id = $transaction->id;
        //     $ipn->save();

        //     $transaction->status_id = $ipn->status;
        //     $transaction->save();
        // }
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
                    $conversation_id = $data->conversation_id;
                    $check = true;
                    break;
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
            return response([
                "id" => $conversation->id
            ]);
        }
        else
        {
            if (!LBM_conversation::find($conversation_id))
            {
                $conversation = new LBM_conversation;
                $conversation->id = $conversation_id;
                $conversation->save();
            }

            return response([
                "id" => $conversation_id
            ]);
            // return response([
            //     "description" => "you haved conversation"
            // ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
