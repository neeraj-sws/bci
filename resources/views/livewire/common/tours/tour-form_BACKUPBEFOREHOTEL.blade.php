<div>
    <style>
        .excel-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
            table-layout: fixed;
        }

        .overflow-x-auto {
            overflow-x: auto
        }

        .excel-table th {
            background: #f1f5f9;
            text-align: left;
            padding: 6px 10px;
            border: 1px solid #ddd;
            white-space: normal;
            /* Allows line breaks */
            word-break: break-word;
            /* Break long words */
            font-size: 12px
        }

        .short-input {
            text-align: right;
            font-size: 12px;
            padding: 3px;
            margin-top: 10px
        }

        .short-input-2 {
            text-align: right;
            font-size: 12px;
            padding: 3px;
        }
        .form-control:disabled, .form-control[readonly]{
            background: #ead1dc
        }

        .excel-table textarea {
            font-size: 12px
        }

        .excel-table td {
            border: 1px solid #ddd;
            padding: 0;
            text-align: center;
        }

        .excel-table tr:nth-child(even) {
            background: rgba(0, 0, 0, 0.075);
        }

        .excel-table tr.bg-total {
            background: #bbf7d0;
            font-weight: bold;
        }

        .excel-table tr.bg-total-gst {
            background: #86efac;
            font-weight: bold;
        }

        .excel-table tr.bg-markup {
            background: #dbeafe;
            font-weight: bold;
        }

        /* Markup */
        .excel-table tr.bg-usd {
            background: #e9d5ff;
            font-weight: bold;
        }

        /* USD */
        .excel-table tr.bg-perperson {
            background: #fde68a;
            font-weight: bold;
        }

        .excel-table th:nth-child(1) {
            width: 250px;
        }

        .excel-table th:nth-child(2) {
            width: 100px;
        }

         .excel-table th:nth-child(4) {
            width: 60px;
        }

          .excel-table th:nth-child(7) {
            width: 60px;
        }

          .excel-table th:nth-child(9) {
            width: 60px;
        }



        .excel-table th {
            width: 80px;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">

                            <div class="mb-3">
                                <label for="title" class="form-label">Tour Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" placeholder="Tour Name"
                                    class="form-control text-capitalize @error('name') is-invalid @enderror" wire:model="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Total Days</label>
                                <input type="number" class="form-control @error('day') is-invalid @enderror"
                                    wire:model="day">
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Total Night</label>
                                <input type="number" class="form-control  @error('night') is-invalid @enderror"
                                    wire:model="night">
                                @error('night')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Status</label>
                                <select id="filter_category" class="form-select" wire:model.live='status'
                                    placeholder="Select Category">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Tour Synopsis (Description) <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" placeholder="Item Description" class="form-control @error('description') is-invalid @enderror"
                                    wire:model="description"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <div class="mb-3">
                                <label for="attachment" class="form-label fw-bold">+ Attachment <span
                                        class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="file" class="form-control" id="attachment" wire:model="attachment"
                                        accept=".pdf">
                                    <label class="input-group-text" for="attachment">Choose File</label>
                                </div>

                                @if ($attachment)
                                    <div
                                        class="mt-2 alert alert-primary d-flex justify-content-between align-items-center p-2">
                                        <span class="text-truncate"
                                            style="max-width: 90%;">{{ $attachment->getClientOriginalName() }}</span>
                                        <button wire:click="$set('attachment', null)" class="btn-close"
                                            aria-label="Remove"></button>
                                    </div>
                                @endif
                                @if ($existingImage && !$attachment)
                                    <div
                                        class="mt-2 alert alert-secondary d-flex justify-content-between align-items-center p-2">
                                        <span class="text-truncate"
                                            style="max-width: 90%;">{{ basename($existingImage) }}</span>
                                        <a href="javascript:void(0)" wire:click="confirmRemove()" class="btn-close" aria-label="Delete"></a>
                                    </div>
                                @endif
                                <div wire:loading wire:target="attachment" class="mt-2 text-primary">
                                    Uploading file, please wait...
                                </div>
                                @if (!$attachment && !$existingImage)
                                    <p class="mt-2 text-muted">No file uploaded yet.</p>
                                @endif
                                
                                      @error('attachment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">+ Tour Json<span
                                        class="text-danger">*</span></label>
                                <div class="">
                                    <input type="file" wire:model="file" accept=".xls,.xlsx">
                                    @error('file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            @if (!empty($tableDataJson))
                                @php
                                    $tourPackage = $tableDataJson['tourPackage'];
                                    $days = $tourPackage['days'] ?? [];
                                    $summary = $tourPackage['summary'] ?? [];
                                    $headers = $tableDataJson['headers'] ?? [];
                                @endphp
                                <div class="table-responsive mt-4">
                                    <table class="excel-table table table-bordered table-hover bg-white">
                                        <thead class="thead-light">
                                            <tr>
                                                @foreach ($headers as $header)
                                                    <th>{{ $header ?? 'NA' }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $index => $day)
                                                <tr class="bg-day">
                                                    @foreach ($day as $key => $value)
                                                        <td>
                                                            @if (in_array($key, ['particular', 'activitiesCovered']))
                                                                <textarea class="form-control textarea-cell"
                                                                    wire:model="tableDataJson.tourPackage.days.{{ $index }}.{{ $key }}"></textarea>
                                                            @elseif (in_array($key, ['totalForTheDay', 'hotelTotal', 'hotelBalance']))
                                                                <input type="text" class="form-control short-input"
                                                                    wire:model="tableDataJson.tourPackage.days.{{ $index }}.{{ $key }}"
                                                                    disabled />
                                                            @else
                                                                <input type="text" class="form-control short-input"
                                                                    wire:model="tableDataJson.tourPackage.days.{{ $index }}.{{ $key }}" 
                                                                    wire:change="recalculateDay({{ $index }})"
                                                                    />
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            @foreach ($summary as $summaryKey => $summaryRow)
                                                @php
                                                    $rowClass = match ($summaryKey) {
                                                        'Total' => 'bg-total',
                                                        'Total + GST' => 'bg-total-gst',
                                                        'With Markup %' => 'bg-markup',
                                                        'USD' => 'bg-usd',
                                                        'Per Person for 2 Pax' => 'd-none',
                                                        default => '',
                                                    };
                                                @endphp
                                                <tr class="{{ $rowClass }}">
                                                    <td class="font-weight-bold">{{ $summaryKey }}</td>
                                                    @foreach ($summaryRow as $key => $value)
                                                        <td>
                                                            @if (in_array($key, ['Total for the Day', 'Hotel Total', 'Hotel Balance']))
                                                                <input type="text" class="form-control short-input-2"
                                                                    wire:model="tableDataJson.tourPackage.summary.{{ $summaryKey }}.{{ $key }}"
                                                                    disabled />
                                                            @else
                                                                <input type="text" class="form-control short-input-2"
                                                                    @if ($summaryKey === 'With Markup %' && $key == 'Entry Numbers') wire:model.live="markupammount"
                                                                    @elseif($summaryKey === 'USD' && $key == 'Entry Numbers')
                                                                        wire:model.live="usdammount"
                                                                    @else
                                                                        wire:model="tableDataJson.tourPackage.summary.{{ $summaryKey }}.{{ $key }}" @endif />
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update changes' : 'Save changes' }}
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>
                            <a href="{{ route($route . '.tour') }}" class="btn btn-secondary greygradientbtn">Close</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
