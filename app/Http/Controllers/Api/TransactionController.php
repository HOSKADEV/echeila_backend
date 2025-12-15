<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $user = auth()->user();

      $wallet = $user->wallet;

      if (!$wallet) {
        throw new Exception('Wallet not found', 404);
      }

      $query = $wallet->transactions();

      // Filter by period
      $period = $request->query('period');
      if ($period) {
        match ($period) {
          'today' => $query->whereDate('created_at', now()->toDateString()),
          'last_week' => $query->whereBetween('created_at', [now()->subWeek(), now()]),
          'last_month' => $query->whereBetween('created_at', [now()->subMonth(), now()]),
          default => null,
        };
      }

      // Filter by direction
      $direction = $request->query('direction');
      if ($direction) {
        if ($direction === 'in') {
          $query->where('amount', '>', 0);
        } elseif ($direction === 'out') {
          $query->where('amount', '<', 0);
        }
      }

      $transactions = $query->latest()->paginate(10);

      return $this->successResponse(
        data: TransactionResource::collection($transactions),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }
}