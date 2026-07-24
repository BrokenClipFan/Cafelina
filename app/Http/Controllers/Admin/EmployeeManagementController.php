<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeManagementController extends Controller
{
    public function index()
    {
        // Pulls all employees, calculating total sales count and total revenue earned per individual
        $employees = User::where('role', 'employee')
            ->leftJoin('purchases', 'users.id', '=', 'purchases.user_id')
            ->selectRaw('
                users.id, 
                users.name, 
                users.email, 
                users.online_status, 
                users.created_at,
                COUNT(purchases.id) as total_orders_handled,
                SUM(CASE WHEN purchases.status = "completed" THEN purchases.total ELSE 0 END) as total_sales_generated
            ')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.online_status', 'users.created_at')
            ->orderBy('users.name', 'asc')
            ->get();
        
        $schedules = EmployeeSchedule::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.employees.index', compact('employees', 'schedules'));
    }

    public function show(User $employee) {
        $employee->load([
            'schedules', 
            'orders.items'
        ]);

        $id = $employee->id;

        $dailySales = DB::table('purchases')
            ->where('user_id', $id)
            ->where('status', 'completed') // Optional: only count completed orders
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('total', 'date');

        // Fill missing daily dates with 0
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::now()->subDays($i)->format('M d');
            $dailyData[] = (float) ($dailySales[$date] ?? 0);
        }

        // Monthly Sales Breakdown (Last 12 Months)
        $monthlySales = DB::table('purchases')
            ->where('user_id', $id)
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('total', 'month');

        // Fill missing monthly dates with 0
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
            $monthlyLabels[] = Carbon::now()->subMonths($i)->format('M Y');
            $monthlyData[] = (float) ($monthlySales[$monthKey] ?? 0);
        }

        return view('admin.employees.employee', compact(
            'employee',
            'dailyLabels',
            'dailyData',
            'monthlyLabels',
            'monthlyData'
        ));
    }

    public function assignShift(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'station_role' => 'required|string|max:255',
            'days' => 'required|array|min:1',
        ]);

        // Changed to standard create() so an employee can have multiple unique rows
        EmployeeSchedule::create([
            'user_id' => $validated['user_id'],
            'days' => $validated['days'], 
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'station_role' => $validated['station_role'],
            'status' => 'Scheduled'
        ]);

        return redirect()->back()->with('success', 'New shift block assigned successfully.');
    }

    public function destroySchedule($id): RedirectResponse
    {
        $schedule = EmployeeSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Shift schedule removed successfully.');
    }

    /**
     * Forcefully terminate an employee's session or change their status.
     */
    public function toggleStatus($id): RedirectResponse
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        if($employee->online_status) {
            $employee = User::findOrFail($id);
            $employee->update([
                'online_status' => false
            ]);
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        return redirect()->back()->with('success', "Status updated successfully for {$employee->name}.");
    }

    /**
     * Handle removing an employee profile from the system.
     */
    public function destroy($id): RedirectResponse
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees')->with('success', 'Employee profile removed permanently.');
    }
}
