<?php

use App\Http\Controllers\LeadSeederController;
use App\Http\Crons\FollowUpCron;
use App\Livewire\Common\Quotations\QuotationPortal;
use App\Livewire\Common\Invoices\InvoicePortal;
use App\Livewire\User\Auth\LoginComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\User\Leads\UserLeadForm;
use App\Livewire\User\Leads\UserLeads;
use App\Livewire\User\Leads\UserLeadView;
use App\Livewire\Common\EstimatePdf;
use App\Livewire\Common\InvoicePdf;
use App\Livewire\Common\ProformaInvoice\ProformaInvoicePortal;
use App\Livewire\Common\ProformaInvoicePdf;
use Illuminate\Support\Facades\Artisan;

Route::get('/generate-demo-leads', [LeadSeederController::class, 'generate']);


// NEW DEV
// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;
// use Illuminate\Support\Facades\Artisan;
// Route::get('/assign-all-admin-permissions', function () {
//     try {
//         $permissions = [
//                 'dashboard view',

//                 'park view',
//                 'park create',
//                 'park edit',
//                 'park delete',
//                 'zone view',
//                 'zone create',
//                 'zone edit',
//                 'zone delete',
//                 'gates view',
//                 'gates create',
//                 'gates edit',
//                 'gates delete',
//                 'taxi view',
//                 'taxi create',
//                 'taxi edit',
//                 'taxi delete',
//                 'resort view',
//                 'resort create',
//                 'resort edit',
//                 'resort delete',
//                 'item view',
//                 'item create',
//                 'item edit',
//                 'item delete',
//                 'tour view',
//                 'tour create',
//                 'tour edit',
//                 'tour delete',

//                 "tourist's view",
//                 "tourist's create",
//                 "tourist's edit",
//                 "tourist's delete",
//                 "tourist's view_detail",
//                 'expense view',
//                 'expense create',
//                 'expense edit',
//                 'expense delete',
//                 'calculator view',
//                 'calculator use',
//                 'tax view',
//                 'tax create',
//                 'tax edit',
//                 'tax delete',
//                 'organization view',
//                 'organization edit',
//                 'preferences view',
//                 'preferences edit',

//                 'estimates view',
//                 'estimates create',
//                 'estimates edit',
//                 'estimates delete',
//                 'estimates view_detail',
//                 'invoices view',
//                 'invoices create',
//                 'invoices edit',
//                 'invoices delete',
//                 'invoices view_detail',
//                 'invoices convert',

//                 'leads view',
//                 'leads create',
//                 'leads edit',
//                 'leads delete',
//                 'leads view_detail',
//                 'pipeline view',
//                 'pipeline manage',
//                 'leads_status view',
//                 'leads_status manage',
//                 'leads_stages view',
//                 'leads_stages manage',
//                 'leads_source view',
//                 'leads_source manage',

//                 'users view',
//                 'users create',
//                 'users edit',
//                 'users delete',
//                 'emails view',
//                 'emails manage',
//                 'vendors view',
//                 'vendors manage',
//                 'vehicles view',
//                 'vehicles manage',

//                 'tag manage','tag view'
//             ];

//         // 🔍 Find or create the admin role with web guard
//         $adminRole = Role::firstOrCreate([
//             'name' => 'admin',
//             'guard_name' => 'web',
//         ]);

//         // 🔗 Sync all permissions to admin
//         $adminRole->syncPermissions($permissions);

//         // 🧹 Clear permission cache
//         Artisan::call('permission:cache-reset');

//         return '✅ All admin permissions have been assigned successfully (guard: web)!';
//     } catch (\Exception $e) {
//         return '❌ Error: ' . $e->getMessage();
//     }
// });




Route::get('/', function () {
    return view('welcome');
});

Route::get('/quotation/{id}/pdf', [EstimatePdf::class, 'download'])
    ->name('estimate.pdf');
Route::get('/quotation/{id}/view', [EstimatePdf::class, 'preview'])
    ->name('estimate.view');
// PR INVOICE
Route::get('/proformainvoice/{id}/pdf', [ProformaInvoicePdf::class, 'download'])
    ->name('proformainvoice.pdf');
Route::get('/proformainvoice/{id}/view', [ProformaInvoicePdf::class, 'preview'])
    ->name('proformainvoice.view');

Route::get('/invoice/{id}/pdf', [InvoicePdf::class, 'download'])
    ->name('invoice.pdf');
Route::get('/invoice/{id}/view', [InvoicePdf::class, 'preview'])
    ->name('invoice.view');

Route::get('/optimize', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:cache');
        Artisan::call('optimize:clear');
        Artisan::call('permission:cache-reset');
    } catch (\Exception $e) {
    }
    return 'Application cache has been cleared';
});
// CRON
Route::prefix('cron')->name('user.')->group(function () {
    Route::get('Cron_followup_notification', [FollowUpCron::class, 'sendNotification']);
});
Route::get('quotation-portal/{id}', QuotationPortal::class)->name('quotation-portal');
Route::get('proformainvoice-portal/{id}', ProformaInvoicePortal::class)->name('proformainvoice-portal');
Route::get('invoice-portal/{id}', InvoicePortal::class)->name('invoice-portal');
Route::get('/', LoginComponent::class);
Route::get('/login', LoginComponent::class);
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/', LoginComponent::class);
        Route::get('login', LoginComponent::class)->name('login');
    });
    Route::middleware(['auth.guard:web', 'web', 'role:sales,marketing'])->group(callback: function () {
        Route::get('logout', [LoginComponent::class, 'logout'])->name('logout');
    });
});
require __DIR__ . '/admin.php';
require __DIR__ . '/common.php';
