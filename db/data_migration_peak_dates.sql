-- ==========================================
-- OPTIONAL: Data Migration Script
-- ==========================================
-- This script migrates existing date ranges from peak_dates table
-- to the new peak_date_room_category_occupances structure
-- 
-- WARNING: Review and test this thoroughly before running in production!
-- ==========================================

-- Step 1: Check current state
-- See how many peak dates have date ranges
SELECT 
    COUNT(*) as peak_dates_with_dates,
    COUNT(CASE WHEN start_date IS NOT NULL THEN 1 END) as with_start_date,
    COUNT(CASE WHEN end_date IS NOT NULL THEN 1 END) as with_end_date
FROM peak_dates
WHERE deleted_at IS NULL;

-- Step 2: Check how many occupancy records exist
SELECT COUNT(*) as existing_occupancy_records
FROM peak_date_room_category_occupances
WHERE deleted_at IS NULL;

-- Step 3: Preview what will be migrated
SELECT 
    pd.peak_dates_id,
    pd.title,
    pd.start_date,
    pd.end_date,
    pdrco.peak_date_room_category_occupancy_id,
    pdrco.occupancy_id,
    pdrco.rate,
    pdrco.weekend_rate
FROM peak_dates pd
INNER JOIN peak_date_room_category_occupances pdrco 
    ON pd.peak_dates_id = pdrco.peak_date_id
WHERE pd.deleted_at IS NULL
    AND pdrco.deleted_at IS NULL
    AND pd.start_date IS NOT NULL
    AND pd.end_date IS NOT NULL
LIMIT 10;

-- ==========================================
-- MIGRATION EXECUTION
-- ==========================================

-- Step 4: Migrate date ranges to occupancy records
-- This updates existing occupancy records with their peak date's date range
UPDATE peak_date_room_category_occupances pdrco
INNER JOIN peak_dates pd ON pdrco.peak_date_id = pd.peak_dates_id
SET 
    pdrco.start_date = pd.start_date,
    pdrco.end_date = pd.end_date
WHERE pdrco.deleted_at IS NULL
    AND pd.deleted_at IS NULL
    AND pd.start_date IS NOT NULL
    AND pd.end_date IS NOT NULL
    AND pdrco.start_date IS NULL
    AND pdrco.end_date IS NULL;

-- Step 5: Verify migration
SELECT 
    COUNT(*) as total_occupancy_records,
    COUNT(CASE WHEN start_date IS NOT NULL THEN 1 END) as with_start_date,
    COUNT(CASE WHEN end_date IS NOT NULL THEN 1 END) as with_end_date,
    COUNT(CASE WHEN start_date IS NOT NULL AND end_date IS NOT NULL THEN 1 END) as with_both_dates
FROM peak_date_room_category_occupances
WHERE deleted_at IS NULL;

-- ==========================================
-- POST-MIGRATION CLEANUP (OPTIONAL)
-- ==========================================

-- Option A: Keep date columns in peak_dates table but set to NULL
-- (Safer - allows rollback)
/*
UPDATE peak_dates 
SET start_date = NULL, end_date = NULL 
WHERE deleted_at IS NULL;
*/

-- Option B: Drop date columns from peak_dates table
-- (Permanent - cannot rollback without backup)
/*
ALTER TABLE peak_dates 
DROP COLUMN start_date,
DROP COLUMN end_date;
*/

-- ==========================================
-- VERIFICATION QUERIES
-- ==========================================

-- Check for orphaned records (occupancies without dates)
SELECT 
    pdrco.peak_date_room_category_occupancy_id,
    pdrco.peak_date_id,
    pd.title as peak_date_title,
    pdrco.occupancy_id,
    pdrco.start_date,
    pdrco.end_date
FROM peak_date_room_category_occupances pdrco
LEFT JOIN peak_dates pd ON pdrco.peak_date_id = pd.peak_dates_id
WHERE pdrco.deleted_at IS NULL
    AND (pdrco.start_date IS NULL OR pdrco.end_date IS NULL);

-- Check data integrity
SELECT 
    pd.title as peak_date,
    h.name as hotel,
    rc.title as room_category,
    COUNT(DISTINCT pdrco.occupancy_id) as occupancy_count,
    COUNT(DISTINCT pdrco.start_date) as unique_start_dates,
    COUNT(DISTINCT pdrco.end_date) as unique_end_dates
FROM peak_dates pd
LEFT JOIN hotels h ON pd.hotel_id = h.hotels_id
LEFT JOIN room_categoris rc ON pd.room_category_id = rc.room_categoris_id
LEFT JOIN peak_date_room_category_occupances pdrco ON pd.peak_dates_id = pdrco.peak_date_id
WHERE pd.deleted_at IS NULL
    AND pdrco.deleted_at IS NULL
GROUP BY pd.peak_dates_id, pd.title, h.name, rc.title;

-- ==========================================
-- ROLLBACK SCRIPT (In case of issues)
-- ==========================================

/*
-- Clear migrated dates from occupancy records
UPDATE peak_date_room_category_occupances
SET 
    start_date = NULL,
    end_date = NULL,
    season_id = NULL
WHERE deleted_at IS NULL;

-- Restore dates to peak_dates table from backup
-- (Requires having a backup!)
*/

-- ==========================================
-- NOTES
-- ==========================================
-- 
-- 1. This migration assumes:
--    - All occupancies for a peak date should have the same date range
--    - Season will be assigned manually later via the UI
--    
-- 2. After migration:
--    - Users can edit date ranges per price entry via Peak Date Prices UI
--    - Users can assign seasons via Peak Date Prices UI
--    - Date ranges are now managed at the price level, not peak date level
--    
-- 3. Testing:
--    - Test on a development/staging environment first
--    - Backup production database before running
--    - Verify record counts before and after
--    - Check a few records manually to ensure data is correct
--    
-- 4. Timing:
--    - Run during off-peak hours
--    - Notify users of maintenance window
--    - Have rollback plan ready
