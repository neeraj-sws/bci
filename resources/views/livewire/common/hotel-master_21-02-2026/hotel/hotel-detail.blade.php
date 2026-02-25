<div>
    <style>
        .crm-page {
            background-color: #F6F8FC;
            min-height: 100vh;
            padding: 24px;
        }

        .crm-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .crm-primary {
            color: #0F172A;
        }

        .crm-success {
            color: #16A34A;
        }

        .crm-warning {
            color: #F59E0B;
        }

        .crm-danger {
            color: #DC2626;
        }

        .crm-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .crm-badge-active {
            background: #DCFCE7;
            color: #16A34A;
        }

        .crm-badge-inactive {
            background: #FEE2E2;
            color: #DC2626;
        }

        .crm-badge-draft {
            background: #FEF3C7;
            color: #F59E0B;
        }
    </style>

    <div class="crm-page">
        @livewire('common.hotel-master.hotel.header', ['hotelId' => $hotel->id])

        @livewire('common.hotel-master.hotel.kpi-cards', ['hotelId' => $hotel->id])

        @livewire('common.hotel-master.hotel.tabs', ['hotelId' => $hotel->id])
    </div>
</div>
