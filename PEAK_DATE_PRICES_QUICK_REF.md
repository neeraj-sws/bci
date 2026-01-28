# Peak Date Prices - Quick Reference

## Database Schema Update

**Run this SQL first:**
```sql
ALTER TABLE `peak_date_room_category_occupances` 
ADD COLUMN `season_id` INT UNSIGNED NULL AFTER `peak_date_id`,
ADD COLUMN `start_date` DATE NULL AFTER `weekend_rate`,
ADD COLUMN `end_date` DATE NULL AFTER `start_date`,
ADD INDEX `idx_season_id` (`season_id`),
ADD INDEX `idx_date_range` (`start_date`, `end_date`);
```

## Files Created/Modified

### Created
1. `app/Livewire/Common/HotelMaster/PeakDatePrices.php` - New Livewire component
2. `resources/views/livewire/common/hotel-master/peak-date-prices.blade.php` - New Blade view
3. `db/schema_changes_peak_date_prices.sql` - SQL schema changes
4. `PEAK_DATE_PRICES_IMPLEMENTATION.md` - Full documentation

### Modified
1. `app/Livewire/Common/HotelMaster/PeakDates.php` - Removed date/price logic
2. `resources/views/livewire/common/hotel-master/peak-dates.blade.php` - Simplified form
3. `app/Models/PeackDate.php` - Removed start_date, end_date from fillable
4. `app/Models/PeakDateRoomCategoryOccupances.php` - Added season relationship
5. `app/Models/Season.php` - Added accessors for compatibility

## Workflow

### 1. Create Peak Date (Basic Info Only)
```
Peak Date Form:
- Title: "Christmas 2026"
- Hotel: "Grand Hotel"
- Room Category: "Deluxe Rooms"
- Status: Active
```

### 2. Create Peak Date Prices (With Date Ranges & Rates)
```
Peak Date Price Form:
- Peak Date: "Christmas 2026 - Grand Hotel"
- Season: "High Season"
- Start Date: 2026-12-20
- End Date: 2026-12-31
- Rates Table:
  * Single: Weekday 100, Weekend 120
  * Double: Weekday 150, Weekend 180
  * Triple: Weekday 200, Weekend 240
```

## Key Differences

| Feature | Old Peak Date | New Peak Date | New Peak Date Price |
|---------|--------------|---------------|---------------------|
| Title | ✅ | ✅ | ❌ |
| Hotel | ✅ | ✅ | Via Peak Date |
| Room Category | ✅ | ✅ | Via Peak Date |
| Date Range | ✅ | ❌ | ✅ |
| Season | ❌ | ❌ | ✅ |
| Occupancy Rates | ✅ | ❌ | ✅ |
| Status | ✅ | ✅ | ✅ |

## API Relationships

```php
// Get Peak Date with its prices
$peakDate = PeackDate::with('occupancies.season')->find(1);

// Get prices for specific season
$prices = PeakDateRoomCategoryOccupances::where('peak_date_id', 1)
    ->where('season_id', 2)
    ->get();

// Get all prices with filters
$prices = PeakDateRoomCategoryOccupances::with(['peakDate', 'season', 'occupancy'])
    ->whereHas('peakDate', function($q) {
        $q->where('hotel_id', 5);
    })
    ->where('season_id', 2)
    ->whereBetween('start_date', ['2026-12-01', '2026-12-31'])
    ->get();
```

## Common Queries

### Get all prices for a hotel
```php
$prices = PeakDateRoomCategoryOccupances::whereHas('peakDate', function($q) use ($hotelId) {
    $q->where('hotel_id', $hotelId);
})->get();
```

### Get prices for specific date range
```php
$prices = PeakDateRoomCategoryOccupances::where('start_date', '<=', $date)
    ->where('end_date', '>=', $date)
    ->get();
```

### Get prices by season
```php
$prices = PeakDateRoomCategoryOccupances::where('season_id', $seasonId)
    ->with(['peakDate', 'occupancy'])
    ->get();
```

## Validation Logic

Peak Date Prices validates:
1. Peak Date exists and is valid
2. Season exists and is valid
3. End date >= Start date
4. Rates provided for ALL occupancies in the room category
5. All rates are numeric and >= 0
6. No duplicate occupancies

## UI Components

### Peak Date Form (Simplified)
- Title input
- Hotel dropdown
- Room Category dropdown
- Status toggle

### Peak Date Price Form (Full)
- Peak Date dropdown (shows: Title - Hotel)
- Season dropdown
- Date range pickers
- Dynamic rate table (auto-populated from room category's occupancies)
- Weekday/Weekend rate inputs for each occupancy

### Peak Date Price List
- Filters: Hotel, Room Category, Season
- Groups by: Peak Date + Season + Date Range
- Shows all occupancy rates in one row
- Edit/Delete group operations
