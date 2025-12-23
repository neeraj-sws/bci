<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Leads as Model;
use App\Models\LeadFollowups;
use App\Models\LeadActivity;
use App\Models\Tourists;
use Carbon\Carbon;

class LeadSeederController extends Controller
{
    public function generate()
    {
        $touristIds = Tourists::pluck('tourist_id')->toArray();

        if (empty($touristIds)) {
            return "Error: No Tourist records found!";
        }

        $names = ["Rahul", "Amit", "Sneha", "Pooja", "Fahad", "Rohan", "Neha", "Aditya", "Kiran", "Imran"];
        $destinations = ["Goa", "Kanha", "Manali", "Kashmir", "Dubai", "Kerala", "Thailand"];
        $remarks = [
            "Hi, my name is NAME and I want to go to DEST.",
            "Planning a trip to DEST soon.",
            "Need budget package for DEST.",
            "I want to travel to DEST next month."
        ];

        for ($i = 1; $i <= 20; $i++) {

            $name = $names[array_rand($names)];
            $dest = $destinations[array_rand($destinations)];
            $remark = str_replace(["NAME", "DEST"], [$name, $dest], $remarks[array_rand($remarks)]);
            $randomTouristId = $touristIds[array_rand($touristIds)];

            $lead = Model::create([
                'type_id' => rand(1, 3),
                'contact' => rand(7000000000, 9999999999),
                'email' => strtolower($name) . rand(1, 1000) . "@gmail.com",
                'stage_id' => 1,
                'status_id' => rand(1, 5),
                'source_id' => rand(1, 5),
                'notes' => $remark,
                'budget' => rand(5000, 100000),
                'follow_up_date' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                'follow_up_time' => "10:00",
                'destination' => $dest,
                'travel_date' => Carbon::now()->addDays(rand(5, 30))->format('Y-m-d'),
                'travel_days' => rand(2, 10),
                'user_id' => 1,
                'tourist_id' => $randomTouristId,
                'tags' => "demo,auto",
            ]);

            $lead->uuid = base64_encode(Str::uuid() . '-' . $lead->id . '-' . Str::uuid());
            $lead->save();

            LeadFollowups::create([
                'lead_id' => $lead->id,
                'followup_date' => $lead->follow_up_date,
                'followup_time' => $lead->follow_up_time,
                'stage_id' => $lead->stage_id,
                'status_id' => 1,
                'comments' => $remark,
            ]);

            LeadActivity::create(['lead_id' => $lead->id, 'msg_type' => 1]);
            LeadActivity::create(['lead_id' => $lead->id, 'msg_type' => 3]);
        }

        return "20 Demo Leads Created Successfully!";
    }
}
