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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Pages publiques
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



// ==================== ROUTES PUBLIQUES ====================
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
// Route pour la page de confirmation
Route::get('/forgot-password/confirmation', [AuthController::class, 'showConfirmation'])->name('password.confirmation');


// ==================== ROUTES PROTÉGÉES ====================
Route::middleware(['auth'])->group(function () {

    // Tableau de bord
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Gestion des utilisateurs (admin seulement)
    //Route::resource('users', UserController::class)->middleware('can:manage_users');

    // Gestion des tâches
    // Gestion des tâches
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/tasks/export/csv', [TaskController::class, 'export'])->name('tasks.export');



    // Gestion des projets
    Route::resource('projects', ProjectController::class);
    Route::put('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::put('/projects/{project}/update-progress', [ProjectController::class, 'updateProgress'])->name('projects.update-progress');
    Route::get('/projects/export/csv', [ProjectController::class, 'export'])->name('projects.export');

    // Gestion des clients
    // Gestion des clients
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
    Route::get('/clients/export/csv', [ClientController::class, 'export'])->name('clients.export');

    // Gestion des produits
    Route::resource('products', ProductController::class);
    Route::get('/products/grid', [ProductController::class, 'grid'])->name('products.grid');
    Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');

    // Gestion des factures
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::put('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');

    // Gestion des départements
    Route::resource('departments', DepartmentController::class);
    Route::put('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
    Route::put('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::get('/departments/export/csv', [DepartmentController::class, 'export'])->name('departments.export');

    // Gestion des paiements
    Route::resource('payments', PaymentController::class);

    // Gestion des dépenses
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);

    // Gestion des commentaires
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Calendrier
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Kanban
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban');

    // Messagerie
    Route::get('/mail', [MailController::class, 'index'])->name('mail');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    // Gestionnaire de fichiers
    Route::get('/files', [FileManagerController::class, 'index'])->name('files');

    // Statistiques
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/ecommerce', [EcommerceController::class, 'index'])->name('ecommerce');

    // Boutique
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.list');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.details');

    // Avis
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.manage');

    // Parrainages
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');

    // Blog
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.list');
    Route::get('/blog/grid', [BlogController::class, 'grid'])->name('blog.grid');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.details');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');

    // Offres d'emploi
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.list');
    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.details');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');

    // Tours
    Route::get('/tours', [TourController::class, 'index'])->name('tours.list');
    Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.details');
    Route::get('/tours/create', [TourController::class, 'create'])->name('tours.create');
    Route::post('/tours', [TourController::class, 'store'])->name('tours.store');
    Route::get('/tours/{id}/edit', [TourController::class, 'edit'])->name('tours.edit');
    Route::put('/tours/{id}', [TourController::class, 'update'])->name('tours.update');

    // Pages statiques
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('/payment-page', [PageController::class, 'payment'])->name('payment.page');

    // Maintenance
    Route::get('/maintenance', [PageController::class, 'maintenance'])->name('maintenance');
    Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming.soon');

    // // Utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view_users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create_users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create_users');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view_users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit_users');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_users');
    Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate')->middleware('permission:edit_users');
    Route::put('/users/notification', [UserController::class, 'notification'])->name('users.notification');
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

// ==================== ROUTES ERREURS ====================
Route::fallback(function () {
    return view('errors.404');
});
