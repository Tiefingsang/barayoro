<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PAGES PUBLIQUES ====================
Route::get('/conditions-utilisation', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/politique-confidentialite', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/help-center', function () {
    return view('pages.help-center');
})->name('help.center');

// ==================== AUTHENTIFICATION ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/forgot-password/confirmation', [AuthController::class, 'showConfirmation'])->name('password.confirmation');

// ==================== ROUTES PROTÉGÉES ====================
Route::middleware(['auth'])->group(function () {

    // ----- Tableau de bord -----
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----- Profil utilisateur -----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ----- Gestion des utilisateurs (avec permissions) -----
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view_users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create_users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create_users');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view_users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit_users');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_users');
    Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate')->middleware('permission:edit_users');
    Route::put('/users/notification', [UserController::class, 'notification'])->name('users.notification');

    // ----- Gestion des tâches -----
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/tasks/export/csv', [TaskController::class, 'export'])->name('tasks.export');

    // ----- Gestion des projets -----
    Route::resource('projects', ProjectController::class);
    Route::put('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::put('/projects/{project}/update-progress', [ProjectController::class, 'updateProgress'])->name('projects.update-progress');
    Route::get('/projects/export/csv', [ProjectController::class, 'export'])->name('projects.export');

    // ----- Gestion des clients -----
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
    Route::get('/clients/export/csv', [ClientController::class, 'export'])->name('clients.export');

    // ----- Gestion des produits -----
    Route::resource('products', ProductController::class);
    Route::get('/products/grid', [ProductController::class, 'grid'])->name('products.grid');
    Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');

    // ----- Gestion des commandes -----
    Route::resource('orders', OrderController::class);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/generate-invoice', [OrderController::class, 'generateInvoice'])->name('orders.generate-invoice');
    Route::get('/orders/export/csv', [OrderController::class, 'export'])->name('orders.export');

    // ----- Gestion des factures -----
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::put('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');

    // ----- Gestion des paiements -----
    Route::resource('payments', PaymentController::class);

    // ----- Gestion des dépenses -----
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);

    // ----- Gestion des départements -----
    Route::resource('departments', DepartmentController::class);
    Route::put('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
    Route::put('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::get('/departments/export/csv', [DepartmentController::class, 'export'])->name('departments.export');

    // ----- Gestion des commentaires -----
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ----- Finance & Rapports (avec permissions) -----
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index')->middleware('permission:view_reports');
    Route::get('/finance/cash-flow', [FinanceController::class, 'cashFlow'])->name('finance.cash-flow')->middleware('permission:view_reports');
    Route::get('/finance/profit-loss', [FinanceController::class, 'profitLoss'])->name('finance.profit-loss')->middleware('permission:view_reports');
    Route::get('/finance/aging', [FinanceController::class, 'agingReport'])->name('finance.aging')->middleware('permission:view_reports');
    Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export')->middleware('permission:export_reports');

    // ----- Statistiques & Analytics -----
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/ecommerce', [EcommerceController::class, 'index'])->name('ecommerce');

    // ----- Boutique -----
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // ----- Outils de collaboration -----
   // ----- Outils de collaboration -----
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban');
Route::put('/kanban/update-status', [KanbanController::class, 'updateTaskStatus'])->name('kanban.update-status');
Route::post('/kanban/quick-task', [KanbanController::class, 'quickTask'])->name('kanban.quick-task');
Route::get('/kanban/tasks', [KanbanController::class, 'getTasks'])->name('kanban.tasks');
    Route::get('/mail', [MailController::class, 'index'])->name('mail');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');




    // Rapports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
    Route::get('/weekly', [ReportController::class, 'weekly'])->name('weekly');
    Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
    Route::get('/quarterly', [ReportController::class, 'quarterly'])->name('quarterly');
    Route::get('/annual', [ReportController::class, 'annual'])->name('annual');
    Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
    Route::get('/download/{report}', [ReportController::class, 'download'])->name('download');
    Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
});

    // ----- Gestionnaire de fichiers -----
// ----- Gestionnaire de fichiers -----
Route::prefix('files')->name('files.')->group(function () {
    // Routes principales
    Route::get('/', [FileManagerController::class, 'index'])->name('index');
    Route::get('/search', [FileManagerController::class, 'search'])->name('search');
    Route::get('/set-view', [FileManagerController::class, 'setView'])->name('set-view'); // Changé ici

    // Opérations sur les dossiers
    Route::post('/create-folder', [FileManagerController::class, 'createFolder'])->name('create-folder');
    Route::put('/rename/{file}', [FileManagerController::class, 'rename'])->name('rename');
    Route::put('/move/{file}', [FileManagerController::class, 'move'])->name('move');
    Route::delete('/delete/{file}', [FileManagerController::class, 'destroy'])->name('destroy');

    // Opérations sur les fichiers
    Route::post('/upload', [FileManagerController::class, 'upload'])->name('upload');
    Route::get('/download/{file}', [FileManagerController::class, 'download'])->name('download');
    Route::get('/show/{file}', [FileManagerController::class, 'show'])->name('show');
    Route::post('/upload-chunk', [FileManagerController::class, 'uploadChunk'])->name('upload-chunk');

    // Opérations de copie
    Route::post('/copy/{file}', [FileManagerController::class, 'copy'])->name('copy');
    Route::post('/zip/{folder}', [FileManagerController::class, 'zip'])->name('zip');
    Route::post('/unzip/{file}', [FileManagerController::class, 'unzip'])->name('unzip');

    // Partages
    Route::post('/share/{file}', [FileManagerController::class, 'share'])->name('share');
    Route::get('/shared/{token}', [FileManagerController::class, 'shared'])->name('shared');
    Route::delete('/share/{share}', [FileManagerController::class, 'revokeShare'])->name('revoke-share');

    // Favoris
    Route::post('/favorite/{file}', [FileManagerController::class, 'addFavorite'])->name('add-favorite');
    Route::delete('/favorite/{file}', [FileManagerController::class, 'removeFavorite'])->name('remove-favorite');
    Route::get('/favorites', [FileManagerController::class, 'favorites'])->name('favorites');

    // Corbeille
    Route::get('/trash', [FileManagerController::class, 'trash'])->name('trash');
    Route::post('/restore/{file}', [FileManagerController::class, 'restore'])->name('restore');
    Route::delete('/force-delete/{file}', [FileManagerController::class, 'forceDelete'])->name('force-delete');
    Route::delete('/empty-trash', [FileManagerController::class, 'emptyTrash'])->name('empty-trash');

    // Informations et statistiques
    Route::get('/info/{file}', [FileManagerController::class, 'info'])->name('info');
    Route::get('/stats', [FileManagerController::class, 'stats'])->name('stats');
    Route::get('/disk-usage', [FileManagerController::class, 'diskUsage'])->name('disk-usage');

    // Upload en masse
    Route::post('/bulk-upload', [FileManagerController::class, 'bulkUpload'])->name('bulk-upload');
    Route::post('/bulk-delete', [FileManagerController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-move', [FileManagerController::class, 'bulkMove'])->name('bulk-move');

    // Export
    Route::get('/export/folder/{folder}', [FileManagerController::class, 'exportFolder'])->name('export-folder');
    Route::get('/export/csv', [FileManagerController::class, 'exportCsv'])->name('export-csv');
});

    // ----- Blog -----
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.list');
    Route::get('/blog/grid', [BlogController::class, 'grid'])->name('blog.grid');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.details');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');

    // ----- Offres d'emploi -----
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.list');
    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.details');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');

    // ----- Tours -----
    Route::get('/tours', [TourController::class, 'index'])->name('tours.list');
    Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.details');
    Route::get('/tours/create', [TourController::class, 'create'])->name('tours.create');
    Route::post('/tours', [TourController::class, 'store'])->name('tours.store');
    Route::get('/tours/{id}/edit', [TourController::class, 'edit'])->name('tours.edit');
    Route::put('/tours/{id}', [TourController::class, 'update'])->name('tours.update');

    // ----- Avis clients -----
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.manage');

    // ----- Parrainages -----
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');

    // ----- Pages statiques -----
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('/payment-page', [PageController::class, 'payment'])->name('payment.page');

    // ----- Maintenance -----
    Route::get('/maintenance', [PageController::class, 'maintenance'])->name('maintenance');
    Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming.soon');
});

// ==================== ROUTES ADMIN ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/backups', [BackupController::class, 'index'])->name('backups');
    Route::post('/backups', [BackupController::class, 'store'])->name('backups.store');
    Route::delete('/backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');
});

// ==================== ROUTE 404 ====================
Route::fallback(function () {
    return view('errors.404');
});
