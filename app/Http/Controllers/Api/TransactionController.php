<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class TransactionController extends Controller
{
    public function index()
    {
        try {

            $transactions = Transaction::with('masterItem')->get();
            return response()->json([
                'status' => true,
                'message' => 'List Data Transactions',
                'data' => $transactions->map(function ($transaction) {
                    return [
                        'transaction_id' => $transaction->id,
                        'transaction_date' => $transaction->transaction_date,
                        'account' => $transaction->account,
                        'account_name' => $transaction->account_name,
                        'note' => $transaction->note,
                        'amount' => $transaction->amount,
                        'company' => $transaction->masterItem->company, // Field dari master_items
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_item_id' => 'required|exists:master_items,id',
            'transaction_date' => 'required|date',
            'account' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction = Transaction::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Transaction created successfully!',
            'data' => $transaction,
        ]);
    }

    public function show($id)
    {
        $transaction_with_master_item = Transaction::with('masterItem')->find($id);
        $transaction = Transaction::find($id);
        if (!$transaction_with_master_item) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found!',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Data Transaction',
            "transaction" => $transaction,
            'transaction_with_master_item' => $transaction_with_master_item,
        ]);
    }


    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found!',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'master_item_id' => 'nullable|exists:master_items,id',
            'transaction_date' => 'nullable|date',
            'account' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Transaction updated successfully!',
            'data' => $transaction,
        ]);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found!',
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaction deleted successfully!',
            'data' => $transaction,
        ]);
    }
}
