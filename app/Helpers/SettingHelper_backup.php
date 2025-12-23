<?php

namespace App\Helpers;

use App\Models\CommonColumnSettings;
use App\Models\EmailSettings;
use App\Models\QuotationSettings;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use App\Models\InvoiceSettings;
use App\Models\LeadActivity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class SettingHelper
{


    private static function incrementEstimateNumber($input)
    {
        $input = trim($input);
        $base = substr($input, 0, -1);
        $last = substr($input, -1);

        if (ctype_digit($last)) {
            return $base . ((int)$last + 1);
        }

        if (ctype_alpha($last)) {
            $new = chr(ord($last) + 1);
            if ($last === 'Z') $new = 'A';
            elseif ($last === 'z') $new = 'a';
            return $base . $new;
        }

        return $input;
    }

    // app/Models/EstimateSettings.php

    public static function generateAndSaveNextEstimateNumber()
    {
        $settings = QuotationSettings::first();

        if (!$settings || empty($settings->estimate_number)) {
            return '';
        }

        $nextNumber = self::incrementEstimateNumber($settings->estimate_number);

        // Update in DB
        $settings->estimate_number = $nextNumber;
        $settings->save();

        return $nextNumber;
    }



    public static function getEstimateNumber()
    {
        $settings = QuotationSettings::first();

        if (!$settings || empty($settings->estimate_number)) {
            return '';
        }

        return self::incrementEstimateNumber($settings->estimate_number);
    }

    public static function getStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Draft';
            case 1:
                return 'Sent';
            case 2:
                return 'Accepted';
            case 3:
                return 'Discarded';
            case 4:
                return 'Revised';
            case 5:
                return 'Suspended';
            case 6:
                return 'Invoiced';
            default:
                return false;
        }
    }


    public static function getColoumName($name)
    {
        return CommonColumnSettings::first()->$name;
    }

    public static function getGenrealSettings($name)
    {
        return GeneralSettings::first()->$name;
    }

    public static function formatCurrency($amount, $format = 'comma_dot', $decimals = 2)
    {
        $amount = floatval($amount);

        switch ($format) {
            case 'comma_dot':     // 1,234,567.89
                return number_format($amount, $decimals, '.', ',');
            case 'dot_comma':     // 1.234.567,89
                return number_format($amount, $decimals, ',', '.');
            case 'space_comma':   // 1 234 567,89
                return number_format($amount, $decimals, ',', ' ');
            case 'none_dot':      // 1234567.89
                return number_format($amount, $decimals, '.', '');
            case 'none_comma':    // 1234567,89
                return number_format($amount, $decimals, ',', '');
            default:
                return number_format($amount, $decimals, '.', ','); // fallback
        }
    }


    private static function incrementInvoiceNumber($input)
    {
        $input = trim($input);
        $base = substr($input, 0, -1);
        $last = substr($input, -1);

        if (ctype_digit($last)) {
            return $base . ((int)$last + 1);
        }

        if (ctype_alpha($last)) {
            $new = chr(ord($last) + 1);
            if ($last === 'Z') $new = 'A';
            elseif ($last === 'z') $new = 'a';
            return $base . $new;
        }

        return $input;
    }

    public static function generateAndSaveNextInvoiceNumber()
    {
        $settings = InvoiceSettings::first();

        if (!$settings || empty($settings->invoice_number)) {
            return '';
        }

        $nextNumber = self::incrementInvoiceNumber($settings->invoice_number);

        // Update in DB
        $settings->invoice_number = $nextNumber;
        $settings->save();

        return $nextNumber;
    }



    public static function getInvoiceNumber()
    {
        $settings = InvoiceSettings::first();

        if (!$settings || empty($settings->invoice_number)) {
            return '';
        }

        return self::incrementEstimateNumber($settings->invoice_number);
    }

    public static function getInvoiceStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Draft';
            case 1:
                return 'Sent';
            case 2:
                return 'Paid';
            case 3:
                return 'Discarded';
            default:
                return false;
        }
    }


    public static function leadActivityLog($msg_id, $lead_id, $user_id = null, $coloum = null)
    {
        $actvity = LeadActivity::create([
            'lead_id' => $lead_id,
            'msg_type' => $msg_id,
        ]);
        if ($coloum) {
            $actvity->update([
                $coloum => $user_id,
            ]);
        }
    }


    public static function InvEstActivityLog($msg_id, $invoice_id = null, $estimate_id = null, $user_id = null, $pr_id = null)
    {
        InvEstActivity::create([
            'invoice_id' => $invoice_id,
            'quotation_id' => $estimate_id,
            'proforma_invoice_id' => $pr_id,
            'msg_type' => $msg_id,
            'user_id' => $user_id ??  Auth::id(),
        ]);
    }

    public static function sendEmail($type, $variables, $to, $from = null)
    {
        $template = EmailSettings::where('type', $type)->first();
        if (!$template) {
            return;
        }
        $renderedMessage = $template->message;
        foreach ($variables as $key => $value) {
            $renderedMessage = str_replace($key, $value, $renderedMessage);
        }
        $renderedSubject = $template->subject;
        foreach ($variables as $key => $value) {
            $renderedSubject = str_replace($key, $value, $renderedSubject);
        }
        Mail::html($renderedMessage, function ($message) use ($to, $renderedSubject, $from) {
            $message->to($to)
                ->subject($renderedSubject);

            if ($from) {
                $message->from($from);
            }
        });
    }

    public static function conditionalRound($value)
    {
        $decimalPart = $value - floor($value);
        if ($decimalPart < 0.50) {
            return round($value); // Round to nearest integer
        } else {
            return round($value, 2); // Keep to 2 decimal places
        }
    }

    public static function getMarkup()
    {
        return (GeneralSettings::first()->markup_rate ?? 25);
    }

    public static function getUsdPrice()
    {
        return GeneralSettings::first()->usd_rate ?? 80;
    }

    public static function getDefaultCurrency()
    {
        $generalSettings = GeneralSettings::first();
        if ($generalSettings && $generalSettings->currency) {
            return $generalSettings->base_currency->code;
        }

        return  "₹";
    }
}
