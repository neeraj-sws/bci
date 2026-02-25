<div class="row">
    <!-- LEFT COLUMN - Sticky Timeline -->
    <div class="col-lg-4">
        @livewire('common.hotel-master.hotel.room-peak-timeline', ['hotelId' => $hotelId], key('timeline-'.$hotelId))
    </div>

    <!-- RIGHT COLUMN - Room Category Cards -->
    <div class="col-lg-8">
        @livewire('common.hotel-master.hotel.room-cards', ['hotelId' => $hotelId], key('cards-'.$hotelId))
    </div>
</div>
