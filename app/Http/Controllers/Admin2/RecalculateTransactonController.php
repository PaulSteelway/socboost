<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\TransactionType;
use AdminSection;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Jobs\SendLogsJob;


class RecalculateTransactonController extends Controller
{

  public function __construct() {
    $this->middleware('role:root|admin|moderator');
  }


  public function index() {
    $statuses = TransactionType::select([
    'id', 'name'
    ])
      ->get();

    $content = view('admin2.recalculate.index', [
      'statuses' => $statuses,
    ]);

    $title = 'Пересчет транзакций';
    return AdminSection::view($content, $title);
  }


  public function cleared(Request $request) {
    $wallet = Wallet::find($request->id);
    $email = isset($wallet->user->email) ? $wallet->user->email : $wallet->user_id;

    $wallet->old_balance = $wallet->balance;
    // $wallet->balance = $wallet->balance - 1; //dev
    $wallet->balance = 0;
    $save = $wallet->save();

    if ($save && config('app.env') !== 'development') {
      $message = 'Баланс пользователя ' . $email . ' обнулен админом';
      SendLogsJob::dispatch($message)->onQueue(getSupervisorName().'-low')->delay(0);
    }

    return [
      'success' => (bool) $save,
      'balance' => $wallet->balance,
    ];
  }


  public function count(Request $request) {
    $counter = 0;
    $users = collect();


    if (isset($request->more) && $request->more !== 0) {
      $wallets = Wallet::where('balance', '>=', $request->more)
        ->with('user:id,email')
        ->with('currency:id,code')
        ->with('paymentSystem:id,code');

      $wallets = $wallets->take(200)->get();

      return [
        'success' => true,
        'count' => $counter,
        'users' => $wallets,
      ];
    }

    $count = Transaction::select([
      'id', 'approved', 'amount',
      'user_id', 'type_id', 'wallet_id'
    ])->groupby('user_id')->distinct();

    if ($request->status !== 0) {
      $count = $count->where('type_id', $request->status);
    }

    if (isset($request->selectdate[0])) {
      $stat_start = Carbon::parse($request->selectdate[0])
        ->isoFormat('YYYY-MM-DD');
      $stat_end = Carbon::parse($request->selectdate[1])
        ->isoFormat('YYYY-MM-DD');
    } else {
      $stat_start = $stat_end = Carbon::now()->isoFormat('YYYY-MM-DD');
    }

    $count = $count
      ->whereDate('created_at', '>=', $stat_start)
      ->whereDate('created_at', '<=', $stat_end);

    $counter = $count->count();

    $users = $count
      // ->with('user:id,email')
      ->with('wallet.user:id,email')
      ->with('wallet.currency')
      ->with('wallet.paymentSystem')
      ->get();

    return [
      'success' => true,
      'count' => $counter,
      'users' => $users,
    ];

  }




}
