<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // ✅ Add this

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Check if tables exist
            if (!Schema::hasTable('inventories') || !Schema::hasTable('sales')) {
                return view('dashboard', [
                    'totalItems' => 0,
                    'totalInventoryValue' => 0,
                    'lowStockItems' => 0,
                    'totalSales' => 0,
                    'todaySales' => 0,
                    'recentItems' => collect(),
                    'systemStatus' => 'setup'
                ]);
            }

            $totalItems = Inventory::count();
            $totalInventoryValue = Inventory::sum('price');
            $lowStockItems = Inventory::where('quantity', '<', 10)->count();
            $totalSales = Sale::sum('total_amount');
            $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');

            $recentItems = Inventory::orderBy('created_at', 'desc')->take(5)->get();

            return view('dashboard', compact(
                'totalItems',
                'totalInventoryValue',
                'lowStockItems',
                'totalSales',
                'todaySales',
                'recentItems'
            ));
        } catch (\Exception $e) {
            return view('dashboard', [
                'totalItems' => 0,
                'totalInventoryValue' => 0,
                'lowStockItems' => 0,
                'totalSales' => 0,
                'todaySales' => 0,
                'recentItems' => collect(),
                'error' => $e->getMessage()
            ]);
        }
    }
}
