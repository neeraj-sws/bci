<?php

namespace App\Livewire\Common\UserHeader;

use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.user-app')]
class UserHeaderNotification extends Component
{
    public $notifications;
    public function render()
    {
        $this->notifications = Notifications::where('user_id', Auth::guard('web')->user()->id)
            ->where('is_read', 0)
            ->latest()
            ->get();

        return view('livewire.common.userheader.user-header-notfication');
    }


    public function open($id)
    {
        $notification = Notifications::find($id);

        if ($notification) {
            $notification->update([
                "is_read" => 1
            ]);

            $this->redirect(route('user.lead-view', $notification->lead_id), navigate: true);
        }
    }
}
