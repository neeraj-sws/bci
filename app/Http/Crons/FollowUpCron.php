<?php

namespace App\Http\Crons;

use App\Http\Controllers\Controller;
use App\Models\LeadFollowups;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FollowUpCron extends Controller
{
    public function sendNotification()
    {
        $followups = LeadFollowups::whereDate('created_at', DB::raw('CURDATE()'))->where('is_read', 0)->get();
        foreach ($followups as $followup) {
            Notifications::create([
                "msg_type" => 25,
                "lead_id" => $followup->lead_id,
                "followup_id" => $followup->id,
                "user_id" => $followup->user_id
            ]);
            $followup->update([
                'is_read' => 1
            ]);
        }
        Log::info('Daily task ran at ' . now());
        return response()->json(['status' => 'Cron job executed']);
    }
}
