-- Add season_id, start_date, and end_date columns to peak_date_room_category_occupances table
-- This allows season-wise pricing with date ranges per price entry

ALTER TABLE `peak_date_room_category_occupances`
ADD COLUMN `season_id` INT UNSIGNED NULL AFTER `peak_date_id`,
ADD COLUMN `start_date` DATE NULL AFTER `weekend_rate`,
ADD COLUMN `end_date` DATE NULL AFTER `start_date`,
ADD INDEX `idx_season_id` (`season_id`),
ADD INDEX `idx_date_range` (`start_date`, `end_date`);

-- Add foreign key constraint if seasons table exists
-- Uncomment below if you have a seasons table with season_id as primary key
-- ALTER TABLE `peak_date_room_category_occupances`
-- ADD CONSTRAINT `fk_peak_date_prices_season`
-- FOREIGN KEY (`season_id`) REFERENCES `seasons`(`season_id`)
-- ON DELETE SET NULL ON UPDATE CASCADE;
