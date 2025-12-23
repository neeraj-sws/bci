<?php

// namespace App\Livewire\Common;

// use App\Models\Quotations;
// use App\Models\Invoices;
// use App\Models\Items;
// use App\Models\Leads;
// use App\Models\ParkGates;
// use App\Models\Parks;
// use App\Models\Resorts;
// use App\Models\Taxis;
// use App\Models\Tours;
// use App\Models\Zones;
// use Livewire\Attributes\Layout;
// use Livewire\Component;
// use Illuminate\Support\Facades\Auth;

// #[Title('Dashboard')]
// #[Layout('components.layouts.common-app')]
// class Dashboard extends Component
// {
//     public $dashboardData = [];

//     public function mount()
//     {
//         $user = Auth::guard('web')->user();

//         $commonData = [
//             'parks' => Parks::count(),
//             'zones' => Zones::count(),
//             'taxis' => Taxis::count(),
//             'gates' => ParkGates::count(),
//             'resorts' => Resorts::count(),
//             'items' => Items::count(),
//             'projects' => Tours::count(),
//         ];
//         $userData = [
//             'leads' => Leads::where('user_id', $user->id)->count(),
//             'Invoice' => Invoices::where('user_id', $user->id)->count(),
//             'Estimate' => Quotations::where('user_id', $user->id)->count(),
//         ];
//         $adminDashboard = [
//             ['title' => 'Park', 'value' => $commonData['parks'], 'route' => route('common.park')],
//             ['title' => 'Zone', 'value' => $commonData['zones'], 'route' => route('common.zone')],
//             ['title' => 'Taxi', 'value' => $commonData['taxis'], 'route' => route('common.taxi')],
//             ['title' => 'Gates', 'value' => $commonData['gates'], 'route' => route('common.gates')],
//             ['title' => 'Resort', 'value' => $commonData['resorts'], 'route' => route('common.resort')],
//             ['title' => 'Items', 'value' => $commonData['items'], 'route' => route('common.item')],
//             ['title' => 'Project', 'value' => $commonData['projects'], 'route' => route('common.tour')],
//         ];

//         $userDashboard = [
//             ['title' => 'Leads', 'value' => $userData['leads'], 'route' => route('common.lead')],
//             ['title' => 'Invoice', 'value' => $userData['Invoice'], 'route' => route('common.invoice')],
//             ['title' => 'Estimate', 'value' => $userData['Estimate'], 'route' => route('common.quotation')],
//         ];

//         $this->dashboardData = $user->hasRole('admin')  ? $adminDashboard : $userDashboard;
//     }

//     public function render()
//     {
//         return view('livewire.common.dashboard');
//     }
// }


namespace App\Livewire\Common;

use App\Models\Invoices;
use App\Models\LeadFollowups;
use App\Models\ProformaInvoices;
use App\Models\Quotations;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard')]
#[Layout('components.layouts.common-app')]
class Dashboard extends Component
{

    public $followUps, $qutoations, $proformas,$proformaSum, $invoices,$invoicesSum,$qutoationSum;

    public function mount()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // $this->followUps = LeadFollowups::query()
        //     ->orderBy('followup_date', 'desc')
        //     ->orderBy('followup_time', 'desc')
        //     ->take(10) // only top 10
        //     ->get();


        $this->followUps = LeadFollowups::query()
            ->whereHas('lead', function ($q) {
                $q->whereIn('stage_id', [1, 3]);
            })
            ->orderByRaw("
      CASE
            WHEN followup_date = CURDATE() THEN 0
            WHEN followup_date > CURDATE() THEN 1
            ELSE 2
        END
    ")
            ->orderBy('followup_date', 'asc')
            ->take(10)
            ->get();
            
        $this->qutoations = Quotations::query()
            ->whereIn('status',[0,1,2])
            ->orderBy('quotation_date', 'desc')
            ->take(10)
            ->get();

            $this->qutoationSum = $this->qutoations->sum('amount');

        $this->proformas = ProformaInvoices::query()
            ->orderBy('proforma_invoice_date', 'desc')
            ->take(10)
            ->get();

            $this->proformaSum = $this->proformas->sum('amount');


        $this->invoices = Invoices::query()
            ->orderBy('invoice_date', 'desc')
            ->take(10)
            ->get();

            $this->invoicesSum = $this->invoices->sum('amount');

    }

    public function render()
    {
        return view('livewire.common.dashboard');
    }
}
