<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MasterItemResource;
use App\Models\MasterItem;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class MasterItemController extends Controller
{
    public function index()
    {
        $masterItem = MasterItem::all();
        return response()->json([
            'status'  => true,
            'message' => 'List Data Master Items',
            'data'    => $masterItem
        ]);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'item_type' => 'required',
            // 'code' => 'required',
            'label' => 'required',
            'type' => 'required',
            'isActive' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // $generated_code = str()->Str::random($length = 8);
        $generated_code = strtoupper(Str::random(8)); // 8 karakter alfanumerik
        //create master-item
        $masterItem = MasterItem::create([
            'company' => $request->company,
            'item_type' => $request->item_type,
            'code' => $generated_code,
            'label' => $request->label,
            'type' => $request->type,
            'isActive' => $request->isActive,
        ]);

        //return response
        return response()->json([
            'status'  => true,
            'message' => 'Data Master Item Berhasil Ditambahkan!',
            'data'    => $masterItem
        ]);
    }

    public function show($id)
    {
        $masterItem = MasterItem::find($id);

        if (!$masterItem) {
            return response()->json([
                'status'  => false,
                'message' => 'Data Master Item Tidak Ditemukan!',
                'data'    => null
            ]);
        }
        return new MasterItemResource(true, 'Detail Data Master Item', $masterItem);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'item_type' => 'required',
            'label' => 'required',
            'type' => 'required',
            'isActive' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $masterItem = MasterItem::find($id);

        if (!$masterItem) {
            return response()->json([
                'status'  => false,
                'message' => 'Data Master Item Tidak Ditemukan!',
                'data'    => null
            ]);
        }

        $masterItem->update([
            'company' => $request->company,
            'item_type' => $request->item_type,
            'label' => $request->label,
            'type' => $request->type,
            'isActive' => $request->isActive,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Data Master Item Berhasil Diupdate!',
            'data'    => $masterItem
        ]);
    }

    public function destroy($id)
    {
        $masterItem = MasterItem::find($id);

        if (!$masterItem) {
            return response()->json([
                'status'  => false,
                'message' => 'Data Master Item Tidak Ditemukan!',
                'data'    => null
            ]);
        }

        $masterItem->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data Master Item Berhasil Dihapus!',
            'data'    => $masterItem
        ]);
    }
}
