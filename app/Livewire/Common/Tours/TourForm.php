<?php

namespace App\Livewire\Common\Tours;

use App\Helpers\SettingHelper;
use App\Models\TourJsons;
use App\Models\Tours as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination, WithFileUploads};
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.common-app')]
class TourForm extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $itemId;
    public $status = 1;
    public $name, $day, $night, $description, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Tour';

    public $model = Model::class;
    public $view = 'livewire.common.tours.tour-form';
    public $file;
    public $tableData = [];
    public $tableDataJson = [];

    public $route;
    
     public $markupammount, $usdammount;
     
    // NEW DEV 
    public $attachment;
    public $existingImage;

    public function mount($id = null,$copy_id=null)
    {
        if($copy_id){
            $this->copy($copy_id);
        }
        if ($id) {
            $this->isEditing = true;
            $this->edit($id);
        }else{
            $this->markupammount = SettingHelper::getMarkup();
            $this->usdammount = SettingHelper::getUsdPrice();
        }

        
        $this->route = 'common';
    }

  public function rules()
{
    $table = (new $this->model)->getTable();

    $nameRule = $this->isEditing
        ? 'unique:' . $table . ',name,' . $this->itemId . ',tour_id'
        : 'unique:' . $table . ',name';
$attachmentRule = $this->existingImage ? 'nullable' : 'required';
    return [
        'name' => [
            'required',
            'string',
            'max:255',
            $nameRule
        ],
        'description' => 'required',
        'file' => 'required',
        'attachment' => $attachmentRule,
    ];
}

public function messages()
{
    return [
        'name.required' => 'The Tour name field is required.',
        'name.string' => 'The Tour name must be a string.',
        'name.max' => 'The Tour name may not be greater than 255 characters.',
        'name.unique' => 'The Tour name has already been taken.',
        'description.required' => 'The Tour description field is required.',
        'file.required' => 'The Tour .xlx is required.',
    ];
}

    public function render()
    {
        return view($this->view);
    }



    public function store()
    {
        $this->validate($this->rules());
        
        $tour = $this->model::create([
            'name' => $this->name,
            'day' => $this->day,
            'night' => $this->night,
            'description' => $this->description,
            'status' => $this->status,
        ]);
        
        if ($this->attachment) {
            $path = "uploads/tours/{$tour->id}";
            if (!Storage::disk('public_root')->exists($path)) {
                Storage::disk('public_root')->makeDirectory($path);
            }
            $fileName = pathinfo($this->attachment->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $this->attachment->getClientOriginalExtension();
            $fullFileName = $fileName . '.' . $extension;

            $this->attachment->storeAs($path, $fullFileName, 'public_root');
            $attachmentPath = $path . '/' . $fullFileName;
        } else {
            $attachmentPath = null;
        }
        $tour->update([
            'attachment' => $attachmentPath
        ]);
        
        if ($this->tableDataJson) {
            // $tableData = json_encode($this->tableDataJson);
        $tableDataArray = $this->tableDataJson;
        $tableDataArray['markupammount'] = $this->markupammount ?? 25;
        $tableDataArray['usdammount'] = $this->usdammount ?? 80;
        $tableData = json_encode($tableDataArray);
        
            TourJsons::create([
                'tour_id' => $tour->id,
                'json' => $tableData
            ]);
        }
        $this->resetForm();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route($this->route . '.tour'), navigate: true);
    }

    public function edit($id)
    {
        $this->resetForm();
        $item = $this->model::findOrFail($id);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->day = $item->day;
        $this->night = $item->night;
        $this->description = $item->description;
        $this->status = $item->status;
        $this->existingImage = $item->attachment;
        $tourJson = $item->tourJsons()->first();
        if ($tourJson) {
            $this->tableDataJson = json_decode($tourJson->json, true);
            
            $this->markupammount = $this->tableDataJson['markupammount'] ?? 1.25;
            $this->usdammount = $this->tableDataJson['usdammount'] ?? 80;
            
            $this->file = true;
        }
        $this->isEditing = true;
    }
    
      public function copy($id)
    {
        $this->resetForm();
        $item = $this->model::findOrFail($id);
        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->day = $item->day;
        $this->night = $item->night;
        $this->description = $item->description;
        $this->status = $item->status;
        $tourJson = $item->tourJsons()->first();
        if ($tourJson) {
            $this->tableDataJson = json_decode($tourJson->json, true);
            $this->file = true;
        }
    }

    public function update()
    {
        $this->validate($this->rules());
        
        
        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
            'day' => $this->day,
            'night' => $this->night,
            'description' => $this->description,
            'status' => $this->status,
        ]);
        
         if ($this->attachment) {
            $path = "uploads/tours/{$this->itemId}";
            if ($this->existingImage && file_exists(public_path($this->existingImage))) {
                @unlink(public_path($this->existingImage));
            }
            $fileName = pathinfo($this->attachment->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $this->attachment->getClientOriginalExtension();
            $fullFileName = $fileName . '.' . $extension;
            $this->attachment->storeAs($path, $fullFileName, 'public_root');
            $attachmentPath = $path . '/' . $fullFileName;
        } else {
            $attachmentPath = null;
        }
       
       if($attachmentPath){
            $this->model::findOrFail($this->itemId)->update([
                'attachment' => $attachmentPath
            ]);
       }
        
        
        if ($this->tableDataJson) {
            $tourJson = TourJsons::where('tour_id', $this->itemId)->first();
            // $tableData = json_encode($this->tableDataJson);
            
        $tableDataArray = $this->tableDataJson;
        $tableDataArray['markupammount'] = $this->markupammount ?? 25;
        $tableDataArray['usdammount'] = $this->usdammount ?? 80;
        $tableData = json_encode($tableDataArray);
        
            if ($tourJson) {
                $tourJson->update([
                    'json' => $tableData
                ]);
            } else {
                TourJsons::create([
                    'tour_id' => $this->itemId,
                    'json' => $this->tableData
                ]);
            }
        }
        $this->resetForm();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);
        $this->redirect(route($this->route . '.tour'), navigate: true);
    }
    public function confirmDelete($id)
    {
        $this->itemId = $id;

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, delete it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'delete'
        ]);
    }
    #[On('delete')]
    public function delete()
    {
                TourJsons::where('tour_id', $this->itemId)->delete();
                
        $this->model::destroy($this->itemId);


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }
    public function resetForm()
    {
        $this->reset([
            'itemId',
            'isEditing',
            'name',
            'day',
            'night',
            'description',
            'status'
        ]);
        $this->resetValidation();
    }

    public function updatedFile()
    {
        if (!$this->file) {
            return $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => 'Please provide a file',
            ]);
        }

        try {
            $spreadsheet = IOFactory::load($this->file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = [];
            $rawHeaders = null;

            foreach ($worksheet->getRowIterator() as $row) {
                $cells = array_map(
                    fn($cell) => trim((string)$cell->getFormattedValue()),
                    iterator_to_array($row->getCellIterator())
                );

                while (!empty($cells) && end($cells) === '') {
                    array_pop($cells);
                }

                if (!$cells) continue;

                if (!$rawHeaders) {
                    $rawHeaders = array_values($cells);
                    continue;
                }

                $rowAssoc = array_combine(
                    $rawHeaders,
                    array_pad(array_slice($cells, 0, count($rawHeaders)), count($rawHeaders), '')
                );

                $rows[] = $rowAssoc;
            }

            if (!$rawHeaders) {
                return $this->dispatch('swal:toast', [
                    'type' => 'error',
                    'message' => 'Invalid or empty Excel file.',
                ]);
            }

            $days = [];
            $summary = [];

            foreach ($rows as $r) {
                $particular = strtolower($r['Particular'] ?? '');
                if (str_starts_with($particular, 'day')) {
                    $days[] = [
                        'particular'        => $r['Particular'] ?? '',
                        'activitiesCovered' => $r['Activities Covered'] ?? '',
                        'roomPerNight'      => (int)($r['Room per Night'] ?? 0),
                        'numberOfRooms'     => (int)($r['No of Rooms'] ?? 0),
                        'vehicleCost'       => (float)($r['Vehicle for the day'] ?? 0),
                        'safariCost'        => (float)($r['Safari / Birding Excursion'] ?? 0),
                        'safariNumber'      => (int)($r['Safari Number'] ?? 0),
                        'monumentFee'       => (float)($r['Monument or Fort Entry Fee'] ?? 0),
                        'entryNumbers'      => (int)($r['Entry Numbers'] ?? 0),
                        'totalForTheDay'    => (float)($r['Total for the Day'] ?? 0),
                        'hotelTotal'        => (float)($r['Hotel Total'] ?? 0),
                        'hotelAdvance'      => (float)($r['Hotel Advance Payment'] ?? 0),
                        'hotelBalance'      => (float)($r['Hotel Balance'] ?? 0),
                    ];
                } else {
                    $key = $r['Particular'] ?? 'unknown';
                    $summary[$key] = [
                        'Activities Covered'          => $r['Activities Covered'] ?? '',
                        'Room per Night'              => $r['Room per Night'] ?? '',
                        'No of Rooms'                 => $r['No of Rooms'] ?? '',
                        'Vehicle for the day'         => $r['Vehicle for the day'] ?? '',
                        'Safari / Birding Excursion'  => $r['Safari / Birding Excursion'] ?? '',
                        'Safari Number'               => $r['Safari Number'] ?? '',
                        'Monument or Fort Entry Fee'  => $r['Monument or Fort Entry Fee'] ?? '',
                        'Entry Numbers'               => $r['Entry Numbers'] ?? '',
                        'Total for the Day'           => is_numeric($r['Total for the Day'] ?? null) ? SettingHelper::conditionalRound($r['Total for the Day']) : ($r['Total for the Day'] ?? ''),
                        'Hotel Total'                 => is_numeric($r['Hotel Total'] ?? null) ? round((float)$r['Hotel Total'], 2) : ($r['Hotel Total'] ?? ''),
                        'Hotel Advance Payment'       => is_numeric($r['Hotel Advance Payment'] ?? null) ? round((float)$r['Hotel Advance Payment'], 2) : ($r['Hotel Advance Payment'] ?? ''),
                        'Hotel Balance'               => is_numeric($r['Hotel Balance'] ?? null) ? round((float)$r['Hotel Balance'], 2) : ($r['Hotel Balance'] ?? ''),
                    ];
                }
            }

            $finalJson = [
                'headers' => array_values($rawHeaders),
                'tourPackage' => [
                    'days'    => $days,
                    'summary' => $summary,
                ],
            ];
            $this->tableData = json_encode($finalJson, JSON_PRETTY_PRINT);
            $this->tableDataJson = $finalJson;
             $this->calculateTotals();
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => 'Error processing file: ' . $e->getMessage(),
            ]);
        }
    }
    
        public function recalculateDay($index)
    {
        if (!isset($this->tableDataJson['tourPackage']['days'][$index])) {
            return;
        }

        $day = &$this->tableDataJson['tourPackage']['days'][$index];

        $roomPerNight = (float) ($day['roomPerNight'] ?? 0);
        $numberOfRooms = (int) ($day['numberOfRooms'] ?? 0);
        $vehicleCost   = (float) ($day['vehicleCost'] ?? 0);
        $safariCost    = (float) ($day['safariCost'] ?? 0);
        $safariNumber  = (int) ($day['safariNumber'] ?? 0);
        $monumentFee   = (float) ($day['monumentFee'] ?? 0);
        $entryNumbers  = (int) ($day['entryNumbers'] ?? 0);
        $hotelAdvance  = (float) ($day['hotelAdvance'] ?? 0);

        // ---- Formula ----
        $day['hotelTotal']   = $roomPerNight * $numberOfRooms;
        $day['totalForTheDay'] = ($roomPerNight * $numberOfRooms)
            + $vehicleCost
            + ($safariCost * $safariNumber)
            + ($monumentFee * $entryNumbers);

        $day['hotelBalance'] = $day['hotelTotal'] - $hotelAdvance;


        $this->calculateTotals();
    }
    public function calculateTotals()
    {
          if (empty($this->tableDataJson['tourPackage']['days']) || !$this->usdammount) {
            return;
        }
        
        
        // Step 1: Calculate total from all days
        $total = collect($this->tableDataJson['tourPackage']['days'])
            ->sum('totalForTheDay');

        $markupPersent =    ($this->markupammount / 100 + 1);
        // Step 2: Total + GST (5%)
        $totalWithGst = $total * 1.05;

        // Step 3: With Markup (25%)
        // $withMarkup = $totalWithGst * 1.25;
        $withMarkup = $totalWithGst * (float)($markupPersent ?? 1.5);

        // Step 4: USD Conversion (divide by 80)
        // $usd = $withMarkup / 80;
        $usd = $withMarkup / (float)($this->usdammount ?? 80);

        // Step 5: Per Person for 2 Pax
        $perPerson = $usd / 2;

        // ---- Update inside summary ----
        $this->tableDataJson['tourPackage']['summary']['Total']['Total for the Day'] = round($total, 2);
        $this->tableDataJson['tourPackage']['summary']['Total + GST']['Total for the Day'] = round($totalWithGst, 2);
        $this->tableDataJson['tourPackage']['summary']['With Markup %']['Total for the Day'] = SettingHelper::conditionalRound($withMarkup);
        $this->tableDataJson['tourPackage']['summary']['USD']['Total for the Day'] = SettingHelper::conditionalRound($usd);
        // $this->tableDataJson['tourPackage']['summary']['Per Person for 2 Pax']['Total for the Day'] = round($perPerson, 2);
    }
    
       public function updatedMarkupammount($value)
    {
        $this->calculateTotals();
    }
    public function updatedUsdammount($value)
    {
        $this->calculateTotals();
    }
    
    public function confirmRemove()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, delete it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'deleteImage'
        ]);
    }
    #[On('deleteImage')]
    public function deleteImage()
    {
        if($this->existingImage){
            $this->model::where('tour_id',$this->itemId)->update([
            "attachment"=>null
                ]);
            if ($this->existingImage && file_exists(public_path($this->existingImage))) {
            @unlink(public_path($this->existingImage));
            }
        }
        
        $this->existingImage = null;
        $this->attachment = null;
        
}

}
