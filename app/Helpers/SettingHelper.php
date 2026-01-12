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
use Maatwebsite\Excel\Facades\Excel;

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

    public static function generateAndSaveNextEstimateNumber($id = null)
    {
        $settings = QuotationSettings::where('company_id', $id)->first();

        if (!$settings || empty($settings->estimate_number)) {
            return '';
        }

        $nextNumber = self::incrementEstimateNumber($settings->estimate_number);

        // Update in DB
        $settings->estimate_number = $nextNumber;
        $settings->save();

        return $nextNumber;
    }



    public static function getEstimateNumber($id = null)
    {
        $settings = QuotationSettings::where('company_id', $id)->first();

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
                return 'Proforma';
            case 7:
                return 'Invoiced';
            default:
                return false;
        }
    }

    public static function getProFormaInvoiceStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Sent';
            case 2:
                return 'Paid';
            case 3:
                return 'Partial';
            default:
                return false;
        }
    }


    public static function getColoumName($name, $id = null)
    {
        $columnSetting = CommonColumnSettings::where('company_id', $id)->first();
        if ($columnSetting) {
            return $columnSetting->$name;
        }
        return null;
    }

    public static function getGenrealSettings($name, $id = null)
    {
        $columnSetting = GeneralSettings::where('company_id', $id)->first();
        if ($columnSetting) {
            return $columnSetting->$name;
        }
        return null;
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

    public static function generateAndSaveNextInvoiceNumber($id = null)
    {
        $settings = InvoiceSettings::where('company_id', $id)->first();

        if (!$settings || empty($settings->invoice_number)) {
            return '';
        }

        $nextNumber = self::incrementInvoiceNumber($settings->invoice_number);

        // Update in DB
        $settings->invoice_number = $nextNumber;
        $settings->save();

        return $nextNumber;
    }



    public static function getInvoiceNumber($id = null)
    {
        $settings = InvoiceSettings::where('company_id', $id)->first();

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

    public static function sendEmail($type, $id, $variables, $to, $from = null, $attachment = null, $pdf = null)
    {
        try {
            $template = EmailSettings::where('company_id', $id)->where('type', $type)->first();
            if (!$template) {
                return [
                    'status' => 'error',
                    'message' => 'Email template not found for the given type and company.'
                ];
            }
            $renderedMessage = $template->message;
            foreach ($variables as $key => $value) {
                $renderedMessage = str_replace($key, $value, $renderedMessage);
            }
            $renderedSubject = $template->subject;
            foreach ($variables as $key => $value) {
                $renderedSubject = str_replace($key, $value, $renderedSubject);
            }
            $renderedMessage = nl2br($renderedMessage);
            Mail::html($renderedMessage, function ($message) use ($to, $renderedSubject, $from, $attachment, $pdf) {
                $message->to('bci.lead.module@yopmail.com')
                // $message->to($to)
                    ->subject($renderedSubject);

                if ($from) {
                    $message->from(config('mail.from.address'), $from);
                }
                if ($attachment) {
                    $filePath = public_path("{$attachment}");
                    if (file_exists($filePath)) {
                        $message->attach($filePath);
                    }
                }
                if ($pdf) {
                    $filePath = public_path("{$pdf}");
                    if (file_exists($filePath)) {
                        $message->attach($filePath);
                    }
                }
            });
            return [
                'status' => 'success',
                'message' => 'Email sent successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'An error occurred while sending the email: ' . $e->getMessage()
            ];
        }
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

    public static function getMarkup($id = null)
    {
        return (GeneralSettings::where('company_id', $id)->first()->markup_rate ?? 25);
    }

    public static function getUsdPrice($id = null)
    {
        return GeneralSettings::where('company_id', $id)->first()->usd_rate ?? 80;
    }


    public static function getDefaultCurrency($id = null)
    {
        $generalSettings = GeneralSettings::where('company_id', $id)->first();
        if ($generalSettings && $generalSettings->currency) {
            return $generalSettings->base_currency->code;
        }

        return  "₹";
    }
    
        //  EXPORT HELPER 
    public static function ExportHelper($fileName, $headings, $data)
    {
        return Excel::download(new class($headings, $data) implements
            \Maatwebsite\Excel\Concerns\FromArray,
            \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $headings;
            protected $data;
            public function __construct($headings, $data)
            {
                $this->headings = $headings;
                $this->data = $data;
            }
            public function headings(): array
            {
                return $this->headings;
            }
            public function array(): array
            {
                return $this->data;
            }
        }, $fileName . '.xlsx');
    }
    
    public static function ImportHelper($file, $modelClass, $mapping = [])
    {
        try {
            $rows = Excel::toArray([], $file)[0];

            if (!$rows || count($rows) < 2) {
                return ['error' => 'File is empty or invalid'];
            }

            $fileHeaders = array_map('trim', $rows[0]);
            $expectedHeaders = array_values($mapping);

            if ($fileHeaders !== $expectedHeaders) {
                return [
                    'error' => "Header mismatch. Expected: " . implode(', ', $expectedHeaders)
                ];
            }

            array_shift($rows);

            $inserted = 0;
            $errors   = [];

            foreach ($rows as $index => $row) {

                // if (!isset($row[0]) || trim($row[0]) === '') continue;

                $data = [];
                foreach ($mapping as $colIndex => $fieldName) {
                    $data[$fieldName] = $row[$colIndex] ?? null;
                }

                try {
                    $modelClass::create($data);
                    $inserted++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ': ' . $e->getMessage();
                }
            }
      
            return [
                'success'  => true,
                'inserted' => $inserted,
                'errors'   => $errors
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    // 

    public static  function format_phone(?string $phone): ?string
        {
            try {
                if (!$phone) {
                    return null;
                }
                $digits = preg_replace('/\D/', '', $phone);
                if (strlen($digits) !== 10) {
                    return $phone;
                }
                return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1 $2 $3', $digits);
            } catch (\Throwable $e) {
                return $phone;
            }
        }
}
