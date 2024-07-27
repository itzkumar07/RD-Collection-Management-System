<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Alumni;
use Exception;

interface DashboardInterface
{
    public function viewDashboard();
}

class DashboardController extends Controller implements DashboardInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('logout');
    }

    /**
     * View Dashboard
     *
     * @return mixed
     */
    public function viewDashboard(): mixed
    {
        try {

            $total_admin = Admin::where('status', true)->count();
            
            return view('admin.pages.dashboard.dashboard',[
                'total_admin' => $total_admin,
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
