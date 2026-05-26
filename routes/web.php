<?php

use Illuminate\Support\Facades\Route;

// Controllers
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
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\AnalyticsController;
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
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PAGES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/conditions-utilisation', [PageController::class, 'terms'])->name('terms');

Route::get('/politique-confidentialite', [PageController::class, 'privacy'])->name('privacy');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

Route::get('/help-center', [PageController::class, 'helpCenter'])->name('help.center');

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

Route::get('/payment-page', [PageController::class, 'payment'])->name('payment.page');

Route::get('/maintenance', [PageController::class, 'maintenance'])->name('maintenance');

Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming.soon');

/*
|--------------------------------------------------------------------------
| BLOG PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/actualites', [PageController::class, 'publicList'])->name('pages.blog.public');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.list');

Route::get('/blog/grid', [BlogController::class, 'grid'])->name('blog.grid');

/*
|--------------------------------------------------------------------------
| JOBS PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.list');

/*
|--------------------------------------------------------------------------
| TOURS PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/tours', [TourController::class, 'index'])->name('tours.list');

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RESET PASSWORD
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'subscription'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'recent'])
        ->name('notifications.recent');

    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');

    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | BLOG ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');

    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');

    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');

    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');

    Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');

    Route::get('/admin/blog', [BlogController::class, 'index'])->name('blog.index');

    /*
    |--------------------------------------------------------------------------
    | JOBS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');

    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');

    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');

    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');

    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/applications', [JobController::class, 'applications'])
        ->name('jobs.applications');

    Route::put('/applications/{id}/status', [JobController::class, 'updateApplicationStatus'])
        ->name('jobs.application.status');

    Route::get('/applications/{id}/cv', [JobController::class, 'downloadCv'])
        ->name('jobs.application.cv');

    Route::get('/admin/jobs', [JobController::class, 'index'])->name('jobs.index');

    /*
    |--------------------------------------------------------------------------
    | TOURS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/tours/create', [TourController::class, 'create'])->name('tours.create');

    Route::post('/tours', [TourController::class, 'store'])->name('tours.store');

    Route::get('/tours/{id}/edit', [TourController::class, 'edit'])->name('tours.edit');

    Route::put('/tours/{id}', [TourController::class, 'update'])->name('tours.update');

    Route::delete('/tours/{id}', [TourController::class, 'destroy'])->name('tours.destroy');

    Route::get('/tours/{id}/book', [TourController::class, 'book'])->name('tours.book');

    Route::post('/tours/{id}/book', [TourController::class, 'storeBooking'])
        ->name('tours.booking.store');

    Route::get('/admin/tours', [TourController::class, 'index'])->name('tours.index');

    /*
    |--------------------------------------------------------------------------
    | REVIEWS
    |--------------------------------------------------------------------------
    */

  /*
    |--------------------------------------------------------------------------
    | REVIEWS
    |--------------------------------------------------------------------------
    */

    Route::prefix('reviews')->name('reviews.')->group(function () {

        Route::get('/', [ReviewController::class, 'index'])->name('index');

        Route::get('/manage', [ReviewController::class, 'manage'])->name('manage');

        // AJOUTEZ CETTE LIGNE ICI POUR FIXER L'ERREUR :
        Route::post('/store', [ReviewController::class, 'store'])->name('store');

        Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');

        Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');

        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | AUTRES RESSOURCES
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class);

    Route::resource('clients', ClientController::class);

    Route::resource('products', ProductController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('invoices', InvoiceController::class);

Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');

Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])
    ->name('invoices.send');

Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])
    ->name('invoices.mark-paid');

    Route::resource('payments', PaymentController::class);

    /*
    |--------------------------------------------------------------------------
    | ORANGE MONEY
    |--------------------------------------------------------------------------
    */

    Route::prefix('payments')->name('payments.')->group(function () {

        Route::get('/orange-money/subscription', [PaymentController::class, 'showOrangeMoneySubscription'])
            ->name('orange-money.subscription');

        Route::get('/orange-money/invoice/{invoice}', [PaymentController::class, 'showOrangeMoneyInvoice'])
            ->name('orange-money.invoice');

        Route::post('/orange-money/initiate', [PaymentController::class, 'initiateOrangeMoneyPayment'])
            ->name('orange-money.initiate');

        Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])
            ->name('subscription.plans.payment');

        Route::post('/process', [SubscriptionController::class, 'process'])
            ->name('subscription.process.payment');

        Route::get('/success', [SubscriptionController::class, 'success'])
            ->name('subscription.success.payment');

        Route::get('/callback', [SubscriptionController::class, 'callback'])
            ->name('subscription.callback');

        Route::post('/webhook', [SubscriptionController::class, 'webhook'])
            ->name('subscription.webhook');
    });

    Route::get('/payments/orange-money/subscription', [PaymentController::class, 'showOrangeMoneySubscription'])
        ->name('payments.orange-money.subscription');

    Route::prefix('payments/orange-money')
        ->name('payments.orange-money.')
        ->group(function () {

            Route::get('/waiting/{payment}', [PaymentController::class, 'waitingOrangeMoneyPayment'])
                ->name('waiting');

            Route::get('/callback', [PaymentController::class, 'orangeMoneyCallback'])
                ->name('callback');

            Route::get('/cancel/{payment}', [PaymentController::class, 'orangeMoneyCancel'])
                ->name('cancel');
        });

    Route::post('/webhooks/orange-money', [PaymentController::class, 'orangeMoneyWebhook'])
        ->name('payments.orange-money.webhook');

    if (app()->environment('local')) {

        Route::get('/payments/orange-money/simulate/{payment}', [PaymentController::class, 'orangeMoneySimulate'])
            ->name('payments.orange-money.simulate');
    }

    /*
    |--------------------------------------------------------------------------
    | GESTION
    |--------------------------------------------------------------------------
    */

    Route::resource('expenses', ExpenseController::class);

    Route::resource('expense-categories', ExpenseCategoryController::class);

    Route::resource('departments', DepartmentController::class);

    Route::resource('projects', ProjectController::class);

    Route::resource('tasks', TaskController::class);

    /*
    |--------------------------------------------------------------------------
    | OUTILS
    |--------------------------------------------------------------------------
    */

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

  

    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

   



     /*
    |--------------------------------------------------------------------------
    | GESTIONNAIRE DE FICHIERS
    |--------------------------------------------------------------------------
    */
    Route::get('/files', [FileManagerController::class, 'index'])->name('files.index');
    Route::post('/files/create-folder', [FileManagerController::class, 'createFolder'])->name('files.create-folder');
    Route::post('/files/upload', [FileManagerController::class, 'upload'])->name('files.upload');
    Route::get('/files/{file}/download', [FileManagerController::class, 'download'])->name('files.download');
    Route::get('/files/{file}', [FileManagerController::class, 'show'])->name('files.show');
    Route::put('/files/{file}/rename', [FileManagerController::class, 'rename'])->name('files.rename');
    Route::put('/files/{file}/move', [FileManagerController::class, 'move'])->name('files.move');
    Route::delete('/files/{file}', [FileManagerController::class, 'destroy'])->name('files.destroy');
    Route::get('/files/search', [FileManagerController::class, 'search'])->name('files.search');
    Route::post('/files/set-view', [FileManagerController::class, 'setView'])->name('files.set-view');

    /*
    |--------------------------------------------------------------------------
    | KANBAN
    |--------------------------------------------------------------------------
    */
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban');
    Route::put('/kanban/update-status', [KanbanController::class, 'updateTaskStatus'])->name('kanban.update-status');
    Route::put('/kanban/update-order', [KanbanController::class, 'updateTaskOrder'])->name('kanban.update-order');
    Route::post('/kanban/quick-task', [KanbanController::class, 'quickTask'])->name('kanban.quick-task');
    Route::get('/kanban/tasks', [KanbanController::class, 'getTasks'])->name('kanban.tasks');
    /*
    |--------------------------------------------------------------------------
    | FINANCE & ANALYTICS
    |--------------------------------------------------------------------------
    */

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    /*
    |--------------------------------------------------------------------------
    | PARRAINAGE
    |--------------------------------------------------------------------------
    */

    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');

    /*
    |--------------------------------------------------------------------------
    | RAPPORTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/', [ReportController::class, 'index'])->name('index');

        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');

        Route::get('/weekly', [ReportController::class, 'weekly'])->name('weekly');

        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');

        Route::get('/annual', [ReportController::class, 'annual'])->name('annual');

        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
    });
});

/*
|--------------------------------------------------------------------------
| PAGES STATIQUES
|--------------------------------------------------------------------------
*/

Route::get('/fonctionnalites', [PageController::class, 'features'])->name('features');

Route::get('/offres-emploi', [PageController::class, 'jobs'])->name('pages.jobs');

Route::get('/tarifs', [PageController::class, 'pricing'])->name('pricing.fr');

Route::get('/offre-emploi/{id}', [PageController::class, 'jobDetail'])
    ->name('pages.jobs.details');

/*
|--------------------------------------------------------------------------
| BLOG DYNAMIQUE
|--------------------------------------------------------------------------
*/

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->name('blog.details');

/*
|--------------------------------------------------------------------------
| JOBS DYNAMIQUE
|--------------------------------------------------------------------------
*/

Route::get('/jobs/{id}/apply', [JobController::class, 'apply'])
    ->name('jobs.apply');

Route::post('/jobs/{id}/apply', [JobController::class, 'storeApplication'])
    ->name('jobs.apply.store');

Route::get('/jobs/{id}', [JobController::class, 'show'])
    ->name('jobs.details');

/*
|--------------------------------------------------------------------------
| TOURS DYNAMIQUE
|--------------------------------------------------------------------------
*/

Route::get('/tours/{id}', [TourController::class, 'show'])
    ->name('tours.details');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::resource('roles', RoleController::class);

        Route::resource('permissions', PermissionController::class);

        Route::get('/logs', [LogController::class, 'index'])
            ->name('logs');

        Route::get('/backups', [BackupController::class, 'index'])
            ->name('backups');

        Route::post('/backups', [BackupController::class, 'store'])
            ->name('backups.store');

        Route::delete('/backups/{backup}', [BackupController::class, 'destroy'])
            ->name('backups.destroy');

        Route::resource('tours', TourController::class)->except(['show']);

        Route::get('/tours/{id}', [TourController::class, 'show'])
            ->name('tours.show');
    });

/*
|--------------------------------------------------------------------------
| ABONNEMENTS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])
        ->name('subscription.plans');

    Route::get('/subscription/checkout', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');

    Route::post('/subscription/process', [SubscriptionController::class, 'process'])
        ->name('subscription.process');

    Route::get('/subscription/success', [SubscriptionController::class, 'success'])
        ->name('subscription.success');

    Route::get('/subscription/expired', [SubscriptionController::class, 'expired'])
        ->name('subscription.expired');

    Route::get('/subscription/required', [SubscriptionController::class, 'required'])
        ->name('subscription.required');
});

/*
|--------------------------------------------------------------------------
| ROUTES PROFIL SUPPLÉMENTAIRES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {

        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])
            ->name('avatar.update');

        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])
            ->name('avatar.delete');

        Route::put('/preferences', [ProfileController::class, 'updatePreferences'])
            ->name('preferences');

        Route::post('/sync', [ProfileController::class, 'syncOffline'])
            ->name('sync');

        Route::post('/two-factor/toggle', [ProfileController::class, 'toggleTwoFactor'])
            ->name('two-factor.toggle');
    });

/*
|--------------------------------------------------------------------------
| FALLBACK 404
|--------------------------------------------------------------------------
*/


// routes/web.php
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
        ['loc' => route('features'), 'priority' => '0.9', 'changefreq' => 'monthly'],
        ['loc' => route('pricing'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => route('contact'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('blog.index'), 'priority' => '0.7', 'changefreq' => 'daily'],
        ['loc' => route('help.center'), 'priority' => '0.6', 'changefreq' => 'weekly'],
    ];
    
    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::fallback(function () {
    return view('errors.404');
});
