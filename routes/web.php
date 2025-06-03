<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Main Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/vendors/validation', [AdminController::class, 'vendorValidation'])->name('vendors.validation');
        Route::get('/facility-visits', [AdminController::class, 'facilityVisits'])->name('facility-visits');
        Route::get('/workforce', [AdminController::class, 'workforce'])->name('workforce');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/download', [AdminController::class, 'download'])->name('download');
        
        // Report management routes
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/schedules', [AdminController::class, 'reportSchedules'])->name('reports.schedules');
        Route::get('/reports/history', [AdminController::class, 'reportHistory'])->name('reports.history');
        Route::get('/reports/templates', [AdminController::class, 'reportTemplates'])->name('reports.templates');
        Route::post('/reports', [AdminController::class, 'storeReport'])->name('reports.store');
        Route::post('/reports/{report}/toggle-status', [AdminController::class, 'toggleReportStatus'])->name('reports.toggle-status');
        Route::delete('/reports/{report}', [AdminController::class, 'deleteReport'])->name('reports.delete');
        
        // Task Routes
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        
        // Report Routes
        Route::post('/reports/send-email', [ReportController::class, 'sendReportEmail'])->name('reports.send-email');
        Route::get('/reports/send/form', [ReportController::class, 'showSendForm'])->name('reports.send.form');
        Route::post('/reports/send', [ReportController::class, 'sendReport'])->name('reports.send');
        Route::get('/reports/download/{stakeholder_id?}', [ReportController::class, 'downloadStakeholderReport'])->name('reports.download');
        Route::get('/reports/create', [PdfController::class, 'showReportForm'])->name('reports.create');
        Route::post('/reports/generate', [PdfController::class, 'generateStaticPDF'])->name('reports.generate');
        Route::get('/reports/sent', [PdfController::class, 'viewSentReports'])->name('reports.sent');
        Route::get('/reports/table', [PdfController::class, 'reportsTable'])->name('reports.table');
        
        // API route for stakeholders
        Route::get('/api/stakeholders/{type}', [ReportController::class, 'getStakeholdersByType'])->name('api.stakeholders.by.type');
        Route::post('/send-report', function(Request $request) {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);
            
            // Send email (using Laravel's mail system)
            Mail::to($validated['email'])->send(new \App\Mail\ReportSent());
            
            return response()->json([
                'success' => true,
                'message' => 'Report sent successfully'
            ]);
        });
    });

    // Market Routes (Wholesaler, Retailer, Customer)
    Route::prefix('market')->name('market.')->group(function () {
        Route::get('/bulk-orders', [MarketController::class, 'bulkOrders'])->name('bulk-orders');
        Route::get('/supplier-coordination', [MarketController::class, 'supplierCoordination'])->name('supplier-coordination');
        Route::get('/place-orders', [MarketController::class, 'placeOrders'])->name('place-orders');
        Route::get('/inventory', [MarketController::class, 'inventory'])->name('inventory');
        Route::get('/deliveries', [MarketController::class, 'deliveries'])->name('deliveries');
        Route::get('/products', [MarketController::class, 'products'])->name('products');
        Route::get('/orders', [MarketController::class, 'orders'])->name('orders');
        Route::get('/track-orders', [MarketController::class, 'trackOrders'])->name('track-orders');
    });

    // Supply Routes (Vendor & Supplier)
    Route::prefix('supply')->name('supply.')->group(function () {
        Route::get('/application', [SupplyController::class, 'application'])->name('application');
        Route::get('/validation-status', [SupplyController::class, 'validationStatus'])->name('validation-status');
        Route::get('/chat', [SupplyController::class, 'chat'])->name('chat');
        Route::get('/inventory', [SupplyController::class, 'inventory'])->name('inventory');
        Route::get('/stock-update', [SupplyController::class, 'stockUpdate'])->name('stock-update');
        Route::get('/orders', [SupplyController::class, 'orders'])->name('orders');
        Route::get('/shipments', [SupplyController::class, 'shipments'])->name('shipments');
        Route::get('/chat-manufacturer', [SupplyController::class, 'chatManufacturer'])->name('chat-manufacturer');
        Route::get('/reports', [SupplyController::class, 'reports'])->name('reports');
    });

    // Analytics Routes
    Route::prefix('analytics')->middleware(['role:admin,wholesaler,retailer'])->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/overview', [AnalyticsController::class, 'overview'])->name('overview');
        Route::get('/demand-prediction', [AnalyticsController::class, 'demandPrediction'])->name('demand-prediction');
        Route::get('/customer-segmentation', [AnalyticsController::class, 'customerSegmentation'])->name('customer-segmentation');
        Route::get('/inventory-projections', [AnalyticsController::class, 'inventoryProjections'])->name('inventory-projections');
        Route::get('/workforce', [AnalyticsController::class, 'workforce'])->name('workforce');
        Route::get('/sales-trends', [AnalyticsController::class, 'salesTrends'])->name('sales-trends');
        Route::get('/product-performance', [AnalyticsController::class, 'productPerformance'])->name('product-performance');
        Route::get('/restock-predictions', [AnalyticsController::class, 'restockPredictions'])->name('restock-predictions');
    });

    // Worker Management Routes
    Route::resource('workers', WorkerController::class);
});

Route::get('/', function () {
    return redirect()->route('login');
});