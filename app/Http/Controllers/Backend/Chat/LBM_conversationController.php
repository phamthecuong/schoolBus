<?php

namespace App\Http\Controllers\Backend\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LBM_conversation;
use App\Models\User;
use App\Models\Parents;
use App\Models\Driver;
use App\Models\SchoolAdmin;
use Auth;

class LBM_conversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = LBM_conversation::get();
        return view("libressltd.lbmessenger.conversation.index", [
            "conversations" => $conversations
        ]);
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
        // $parent_id = \Auth::user()->id;
        // $driver_id = $request->id;
        // \DB::beginTransaction();
        // try
        // {
        //     $conversation = new LBM_conversation;
        //     $conversation->save();
        //     $conversation->users()->sync([Auth::user()->id, $request->id]);
        //     \DB::commit();
        // }
        // catch (\Exception $e)
        // {
        //     \DB::rollBack();
        //     dd($e->getMessage());
        // }
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
        
    }
}
