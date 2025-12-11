<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationAccount;
use Illuminate\Http\Request;

class DonationAccountController extends Controller
{
    public function index()
    {
        return response()->json(
            DonationAccount::orderByDesc('created_at')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
        ]);

        $account = DonationAccount::create($data);

        return response()->json([
            'message' => 'Rekening donasi berhasil ditambahkan',
            'data' => $account,
        ]);
    }

    public function update(Request $request, $id)
    {
        $account = DonationAccount::findOrFail($id);

        $data = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
        ]);

        $account->update($data);

        return response()->json([
            'message' => 'Rekening donasi berhasil diperbarui',
            'data' => $account,
        ]);
    }

    public function destroy($id)
    {
        $account = DonationAccount::findOrFail($id);
        $account->delete();

        return response()->json([
            'message' => 'Rekening donasi berhasil dihapus',
        ]);
    }
}

