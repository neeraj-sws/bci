-- Add Parks column to hotels table
ALTER TABLE hotels ADD COLUMN park_id INT NULL AFTER marketing_company_id;

-- Add Foreign Key constraint for park_id
ALTER TABLE hotels ADD CONSTRAINT fk_hotels_parks FOREIGN KEY (park_id) REFERENCES parks(park_id) ON DELETE SET NULL;

-- Create index on park_id for better query performance
CREATE INDEX idx_hotels_park_id ON hotels(park_id);
