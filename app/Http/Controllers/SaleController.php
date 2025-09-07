<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Helpers\Telegram;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::with('inventory')->orderBy('created_at', 'desc')->paginate(10);
        $totalSales = Sale::sum('total_amount');
        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');

        return view('sales.index', compact('sales', 'totalSales', 'todaySales'));
    }

    public function create()
    {
        $inventoryItems = Inventory::where('quantity', '>', 0)->get();
        return view('sales.create', compact('inventoryItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity_sold' => 'required|integer|min:1'
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        if ($inventory->quantity < $request->quantity_sold) {
            return back()->withErrors([
                'quantity_sold' => 'Not enough stock. Only ' . $inventory->quantity . ' left.'
            ])->withInput();
        }

        $unit_price = $inventory->price;
        $total_amount = $unit_price * $request->quantity_sold;

        $sale = Sale::create([
            'inventory_id' => $request->inventory_id,
            'quantity_sold' => $request->quantity_sold,
            'unit_price' => $unit_price,
            'total_amount' => $total_amount
        ]);

        // ✅ Send Telegram alert
        $telegram = new Telegram();
        $message = "🛒 <b>New Sale Recorded!</b>\n";
        $message .= "Item: {$inventory->name}\n";
        $message .= "Quantity: {$request->quantity_sold}\n";
        $message .= "Unit Price: " . number_format($unit_price, 2) . "\n";
        $message .= "Total: " . number_format($total_amount, 2) . "\n";
        $message .= "Sold At: " . now()->toDateTimeString();
        $telegram->sendMessage($message);

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded successfully! Stock updated.');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')
            ->with('success', 'Sale record deleted and stock restored.');
    }

    public function quickSale(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity_sold' => 'required|integer|min:1'
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        if ($inventory->quantity < $request->quantity_sold) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock. Only ' . $inventory->quantity . ' left.'
            ], 422);
        }

        $unit_price = $inventory->price;
        $total_amount = $unit_price * $request->quantity_sold;

        $sale = Sale::create([
            'inventory_id' => $request->inventory_id,
            'quantity_sold' => $request->quantity_sold,
            'unit_price' => $unit_price,
            'total_amount' => $total_amount
        ]);

        // ✅ Telegram alert for quick sale
        $telegram = new Telegram();
        $message = "⚡ <b>Quick Sale!</b>\n";
        $message .= "Item: {$inventory->name}\n";
        $message .= "Quantity: {$request->quantity_sold}\n";
        $message .= "Total: " . number_format($total_amount, 2) . "\n";
        $message .= "Sold At: " . now()->toDateTimeString();
        $telegram->sendMessage($message);

        return response()->json([
            'success' => true,
            'message' => 'Sale recorded!',
            'remaining_stock' => $inventory->quantity - $request->quantity_sold
        ]);
    }

    // ✅ Daily Sales Report
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $sales = Sale::with('inventory')
            ->whereDate('sold_at', $date)
            ->orderBy('sold_at', 'asc')
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalQuantity = $sales->sum('quantity_sold');

        return view('sales.daily_report', compact('sales', 'totalSales', 'totalQuantity', 'date'));
    }

    public function sendDailyReportTelegram(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $sales = Sale::with('inventory')
            ->whereDate('sold_at', $date)
            ->orderBy('sold_at', 'asc')
            ->get();

        if ($sales->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No sales for this date.']);
        }

        $totalSales = $sales->sum('total_amount');
        $totalQuantity = $sales->sum('quantity_sold');

        // Prepare Telegram message
        $message = "📊 <b>Daily Sales Report - $date</b>\n\n";
        foreach ($sales as $sale) {
            $message .= "{$sale->inventory->name} (Size: {$sale->inventory->size}) | Qty: {$sale->quantity_sold} | Total: " . number_format($sale->total_amount, 2) . "\n";
        }
        $message .= "\nTotal Quantity: $totalQuantity\nTotal Sales: " . number_format($totalSales, 2);

        try {
            $telegram = new \App\Helpers\Telegram();
            $telegram->sendMessage($message);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
