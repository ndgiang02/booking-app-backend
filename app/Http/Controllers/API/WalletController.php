<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\Driver;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getWalletDriver()
    {

        $user = auth()->user();
        $driver = Driver::where('user_id', $user->id)->first();

        if ($user->user_type !== 'driver') {
            return response()->json([
                'status' => false,
                'message' => 'User is not a driver'
            ], 403);
        }

        $driverId = $driver->id;
        
        $balance = WalletTransaction::where('driver_id', $driverId)->sum('amount');
        $transactions = WalletTransaction::where('driver_id', $driverId)->get();

        return response()->json([
            'status'=> true,
            'message' => 'Wallet transactions fetched successfully',
            'balance' => $balance,
            'transactions' => $transactions
        ]);
    }
}