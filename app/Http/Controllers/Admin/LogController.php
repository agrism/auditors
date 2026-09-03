<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Log;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of user activity logs.
     */
    public function index(Request $request)
    {
        $query = Log::with('user')->latest('id');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Filter by HTTP method
        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->get('method')));
        }

        // Filter by IP
        if ($request->filled('ip')) {
            $query->where('ip', 'LIKE', '%' . trim($request->get('ip')) . '%');
        }

        // Search in URL or payload data
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('url', 'LIKE', "%{$search}%")
                  ->orWhere('data', 'LIKE', "%{$search}%")
                  ->orWhere('ip', 'LIKE', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }

        $logs = $query->paginate(30)->withQueryString();

        // Summary Statistics
        $totalCount = Log::count();
        $todayCount = Log::whereDate('created_at', Carbon::today())->count();
        $uniqueUsersCount = Log::whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $uniqueIpsCount = Log::distinct('ip')->count('ip');

        $users = User::orderBy('name')->get();

        return view('admin.logs.index', compact(
            'logs',
            'users',
            'totalCount',
            'todayCount',
            'uniqueUsersCount',
            'uniqueIpsCount'
        ));
    }
}
