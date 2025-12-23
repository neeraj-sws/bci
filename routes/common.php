<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\User\User;
use App\Livewire\Common\Preferences\EmailSettings\Email;
use App\Livewire\User\Auth\LoginComponent;
use App\Livewire\Common\Dashboard;

use App\Livewire\Common\Calculator\Calculator;
use App\Livewire\Common\Companies\AddCompanies;
use App\Livewire\Common\Companies\CompaniesList;
use App\Livewire\Common\HotelMaster\HotelCategories;
use App\Livewire\Common\HotelMaster\HotelType;
use App\Livewire\Common\HotelMaster\RateType;
use App\Livewire\Common\HotelMaster\MealType;
use App\Livewire\Common\HotelMaster\Occupancy;
use App\Livewire\Common\Tourists\TouristForm;
use App\Livewire\Common\Tourists\TouristList;
use App\Livewire\Common\Tourists\TouristView;

use App\Livewire\Common\Quotations\AddQuotation;
use App\Livewire\Common\Quotations\Quotation;
use App\Livewire\Common\Quotations\QuotationView;

use App\Livewire\Common\Invoices\Invoice;
use App\Livewire\Common\Invoices\AddInvoice;
use App\Livewire\Common\Invoices\InvoiceView;

use App\Livewire\Common\IncomeExpenses\Expense;
use App\Livewire\Common\IncomeExpenses\Income;
use App\Livewire\Common\IncomeExpenses\IncomeExpenseCategorey;
use App\Livewire\Common\IncomeExpenses\IncomeExpenseSubCategorey;

use App\Livewire\Common\Items\Item;
use App\Livewire\Common\Leads\Form;
use App\Livewire\Common\Leads\Leads;
use App\Livewire\Common\Leads\LeadsView;
use App\Livewire\Common\Leads\LeadTag;
use App\Livewire\Common\Master\Gates;
use App\Livewire\Common\Master\Park;
use App\Livewire\Common\Master\Taxis;
use App\Livewire\Common\Master\Vehicles;
use App\Livewire\Common\Master\Vendors;
use App\Livewire\Common\Master\Zones;

// use App\Livewire\Common\Organization\Organization;
use App\Livewire\Common\Preferences\Preferences;
use App\Livewire\Common\Resorts\Resort;
use App\Livewire\Common\Taxes\Tax;

use App\Livewire\Common\Leads\Source;
use App\Livewire\Common\Leads\Stages;
use App\Livewire\Common\Leads\Status;
use App\Livewire\Common\Leads\Type;
use App\Livewire\Common\Master\ServiceLocation;
use App\Livewire\Common\Master\VendorType;
use App\Livewire\Common\Tours\TourForm;
use App\Livewire\Common\ProformaInvoice\AddProformaInvoice;
use App\Livewire\Common\ProformaInvoice\ProformaInvoice;
use App\Livewire\Common\ProformaInvoice\ProformaInvoiceView;
use App\Livewire\Common\Tours\TourList;

// Route::name('common.')->middleware(['auth.guard:web', 'web', 'role:admin,sales,marketing'])->group(function () {
Route::name('common.')->middleware(['auth.guard:web', 'web', 'role'])->group(function () {

    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master Data
    Route::prefix('master')->group(function () {
        // Route::get('/park', Park::class)->name('park');
        // Route::get('/zone', Zones::class)->name('zone');
        // Route::get('/gates', Gates::class)->name('gates');
        Route::get('/taxi', Taxis::class)->name('taxi');
        // Route::get('/vehicles', Vehicles::class)->name('vehicles');
        // Route::get('/vendors', Vendors::class)->name('vendors');
        // Route::get('/vendor-type', VendorType::class)->name('vendor-type');
        // Route::get('/vendor-service-area', ServiceLocation::class)->name('vendor-service-area');
    });

    Route::prefix('park-master')->group(function () {
        Route::get('/parks', Park::class)->name('park');
        Route::get('/zones', Zones::class)->name('zone');
        Route::get('/gates', Gates::class)->name('gates');
    });

    Route::prefix('vendor-master')->group(function () {
          Route::get('/vehicle', Vehicles::class)->name('vehicles');
        Route::get('/vendors', Vendors::class)->name('vendors');
        Route::get('/vendor-type', VendorType::class)->name('vendor-type');
        Route::get('/vendor-service-location', ServiceLocation::class)->name('vendor-service-location');
    });

    // Resorts & Tours
    Route::prefix('resort-master')->group(function () {
        Route::get('/', Resort::class)->name('resort');
        // Route::get('/tour-master', TourList::class)->name('tour');
        // Route::get('/add-tour', TourForm::class)->name('tour-create');
        // Route::get('/edit-tour/{id}', TourForm::class)->name('tour-edit');
        // Route::get('/copy-tour/{copy_id}', TourForm::class)->name('tour-copy');
    });

        // Resorts & Tours
    Route::prefix('tour-master')->group(function () {
        Route::get('/', TourList::class)->name('tour');
        Route::get('/add', TourForm::class)->name('tour-create');
        Route::get('/edit/{id}', TourForm::class)->name('tour-edit');
        Route::get('/copy/{copy_id}', TourForm::class)->name('tour-copy');
    });


    // Items & Taxes
    Route::prefix('items')->group(function () {
        Route::get('/', Item::class)->name('item');
    });

    // Clients
    Route::prefix('tourists')->group(function () {
        Route::get('/', TouristList::class)->name('tourist');
        Route::get('/create', TouristForm::class)->name('tourist-create');
        Route::get('/{id}/edit', TouristForm::class)->name('tourist-edit');
        Route::get('/view/{id}', TouristView::class)->name('view-tourist');
    });

    // Expenses & Calculator
    Route::prefix('tools')->group(function () {
        // Route::get('/expense', Expense::class)->name('expense');
        Route::get('/calculator', Calculator::class)->name('calculator');
    });

    // Expenses & Calculator
    Route::prefix('income-expenses')->group(function () {
        Route::get('/expenses', Expense::class)->name('expense');
        Route::get('/income', Income::class)->name('income');
    });

    // Organization & Preferences
    // Route::prefix('settings')->group(function () {
    //     Route::get('/organization-profile', Organization::class)->name('profile');
    //     Route::get('/preferences', Preferences::class)->name('preferences');
    //     Route::get('/emails', Email::class)->name('emails');
    // });

    // Estimates
    Route::prefix('quotations')->group(function () {
        Route::get('/', Quotation::class)->name('quotation');
        Route::get('/{lead_id?}/create', AddQuotation::class)->name('add-quotation');
        Route::get('/edit/{estimate}', AddQuotation::class)->name('edit-quotation');
        Route::get('/view/{id}', QuotationView::class)->name('view-quotation');
        Route::get('/{revised_id}/revised', AddQuotation::class)->name('revised-quotation');
    });

    // ProformaInvoice
    Route::prefix('proforma')->group(function () {
        Route::get('/', ProformaInvoice::class)->name('proforma');
        Route::get('/{quotation_id?}/create', AddProformaInvoice::class)->name('add-proformainvoice');
        Route::get('/view/{id}', ProformaInvoiceView::class)->name('view-proformainvoice');
    });


    // Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/', Invoice::class)->name('invoice');
        Route::get('/{quotation_id?}/create', AddInvoice::class)->name('add-invoice');
        Route::get('/view/{id}', InvoiceView::class)->name('view-invoice');
    });

    // Users
    // Route::prefix('admin')->group(function () {
    //     Route::get('/users', User::class)->name('users');
    // });

    // Leads
    Route::prefix('leads')->group(function () {
        // Route::get('/setting', Type::class)->name('leads-pipeline');
        Route::get('/status', Status::class)->name('leads-status');
        Route::get('/stages', Stages::class)->name('leads-stages');
        Route::get('/source', Source::class)->name('leads-source');

        Route::get('/create', Form::class)->name('lead-create');
        Route::get('/{id}/edit', Form::class)->name('lead-edit');
        Route::get('/', Leads::class)->name('lead');
        Route::get('/view/{id}', LeadsView::class)->name('lead-view');

        // Route::get('/tags', LeadTag::class)->name('lead-tags');
    });

    // Company
    Route::prefix('company-setting')->group(function () {
        Route::get('/', CompaniesList::class)->name('companies');
        Route::get('/create', AddCompanies::class)->name('add-company');
        Route::get('/edit/{id}', AddCompanies::class)->name('edit-company');
        Route::get('/tax', Tax::class)->name('tax');
    });

    Route::prefix('other-settings')->group(function () {
        Route::get('/lead-setting', Type::class)->name('leads-pipeline');
        Route::get('/lead-tag', LeadTag::class)->name('lead-tags');
        Route::get('/income-expense-category', IncomeExpenseCategorey::class)->name('income-expense-categorey');
        Route::get('/income-expense-sub-category', IncomeExpenseSubCategorey::class)->name('income-expense-sub-categorey');
    });

    // HOTEL MASTER 
    Route::prefix('hotel-master')->group(function () {
        Route::get('/type', HotelType::class)->name('hotel-type');
        Route::get('/categories', HotelCategories::class)->name('hotel-categories');
        Route::get('/rate-type', RateType::class)->name('rate-type');

        Route::get('ocupancy', Occupancy::class)->name('ocupancy');
        Route::get('meal-type', MealType::class)->name('meal-type');
    });

    // Logout
    Route::get('/logout', [LoginComponent::class, 'logout'])->name('logout');
});
