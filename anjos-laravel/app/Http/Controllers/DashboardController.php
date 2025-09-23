<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Repair;
use App\Models\Customization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Si es admin, mostrar dashboard administrativo
        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }
        
        // Si es cliente, mostrar dashboard de cliente
        return $this->clientDashboard();
    }

    private function adminDashboard()
    {
        // Estadísticas principales
        $totalSales = Order::whereRaw('strftime("%m", created_at) = ?', [str_pad(now()->month, 2, '0', STR_PAD_LEFT)])
            ->whereRaw('strftime("%Y", created_at) = ?', [now()->year])
            ->sum('total');

        $totalUsers = User::count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        // Gráficos para Chart.js
        $monthlySales = $this->getMonthlySales();
        $categoryStats = $this->getCategoryStats();
        $orderStatusStats = $this->getOrderStatusStats();

        return view('dashboard.index', compact(
            'totalSales',
            'totalUsers',
            'totalProducts',
            'recentOrders',
            'monthlySales',
            'categoryStats',
            'orderStatusStats'
        ));
    }

    private function clientDashboard()
    {
        $user = auth()->user();
        
        // Órdenes del cliente
        $recentOrders = Order::with('orderItems.product')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
            
        // Reparaciones del cliente
        $recentRepairs = Repair::where('user_id', $user->id)
            ->latest()
            ->limit(3)
            ->get();
            
        // Personalizaciones del cliente
        $recentCustomizations = Customization::where('user_id', $user->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard.client', compact(
            'recentOrders',
            'recentRepairs',
            'recentCustomizations'
        ));
    }

    public function sales(Request $request)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        $query = Order::with('user');

        // Filtros multicriterio
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('min_total')) {
            $query->where('total', '>=', $request->min_total);
        }

        if ($request->filled('max_total')) {
            $query->where('total', '<=', $request->max_total);
        }

        $orders = $query->latest()->paginate(20);
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'cliente');
        })->get();

        // Datos para gráfico
        $salesChart = $this->getSalesChart($request);

        return view('dashboard.sales', compact('orders', 'users', 'salesChart'));
    }

    public function stock()
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        $products = Product::with('category')->paginate(20);
        return view('dashboard.stock', compact('products'));
    }

    public function services()
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        $repairs = Repair::with(['user', 'assignedTechnician'])->latest()->get();
        $customizations = Customization::with('user')->latest()->get();

        return view('dashboard.services', compact('repairs', 'customizations'));
    }

    public function reports(Request $request)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        $reportType = $request->get('type', 'orders');

        switch ($reportType) {
            case 'orders':
                return $this->ordersReport($request);
            case 'inventory':
                return $this->inventoryReport($request);
            case 'clients':
                return $this->clientsReport($request);
            default:
                return $this->ordersReport($request);
        }
    }

    private function ordersReport(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportOrdersToPdf($orders);
        }

        return view('dashboard.reports.orders', compact('orders'));
    }

    private function inventoryReport(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('stock', '<=', 5);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->get();

        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportInventoryToPdf($products);
        }

        return view('dashboard.reports.inventory', compact('products'));
    }

    private function clientsReport(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'cliente');
        })->withCount('orders');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $clients = $query->get();

        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportClientsToPdf($clients);
        }

        return view('dashboard.reports.clients', compact('clients'));
    }

    private function getMonthlySales()
    {
        return Order::select(
                DB::raw('strftime("%m", created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->whereRaw('strftime("%Y", created_at) = ?', [now()->year])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    private function getCategoryStats()
    {
        return Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(products.id) as count'))
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->pluck('count', 'name')
            ->toArray();
    }

    private function getOrderStatusStats()
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getSalesChart(Request $request)
    {
        $query = Order::select(
            DB::raw('strftime("%Y-%m-%d", created_at) as date'),
            DB::raw('SUM(total) as total')
        );

        if ($request->filled('date_from')) {
            $query->whereRaw('strftime("%Y-%m-%d", created_at) >= ?', [$request->date_from]);
        } else {
            $query->whereRaw('strftime("%Y-%m-%d", created_at) >= ?', [now()->subDays(30)->format('Y-m-d')]);
        }

        if ($request->filled('date_to')) {
            $query->whereRaw('strftime("%Y-%m-%d", created_at) <= ?', [$request->date_to]);
        }

        return $query->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
    }

    private function exportOrdersToPdf($orders)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.reports.pdf.orders', compact('orders'));
        return $pdf->download('reporte-pedidos-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportInventoryToPdf($products)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.reports.pdf.inventory', compact('products'));
        return $pdf->download('reporte-inventario-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportClientsToPdf($clients)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.reports.pdf.clients', compact('clients'));
        return $pdf->download('reporte-clientes-' . now()->format('Y-m-d') . '.pdf');
    }
}


