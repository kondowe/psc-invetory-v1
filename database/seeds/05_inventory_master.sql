-- =================================================================
-- Seed Inventory Master Data
-- =================================================================

USE inventory_system;

-- Item Categories
INSERT INTO item_categories (category_name, category_code, is_fuel_category, description) VALUES
('Office Supplies', 'OFF', FALSE, 'Stationery and office consumables'),
('IT Equipment', 'IT', FALSE, 'Computers, laptops, peripherals'),
('Furniture', 'FURN', FALSE, 'Office furniture and fixtures'),
('Cleaning Supplies', 'CLEAN', FALSE, 'Cleaning materials and equipment'),
('Fuel Coupons', 'FUEL', TRUE, 'Fuel coupons for vehicles'),
('Tools & Equipment', 'TOOLS', FALSE, 'Maintenance tools and equipment'),
('Safety Equipment', 'SAFETY', FALSE, 'PPE and safety gear');

-- Units of Measure
INSERT INTO units_of_measure (uom_name, uom_code, description) VALUES
('Piece', 'PCS', 'Individual items'),
('Box', 'BOX', 'Boxed items'),
('Pack', 'PACK', 'Packaged items'),
('Ream', 'REAM', 'Ream of paper (500 sheets)'),
('Liter', 'L', 'Liters'),
('Kilogram', 'KG', 'Kilograms'),
('Meter', 'M', 'Meters'),
('Set', 'SET', 'Set of items'),
('Roll', 'ROLL', 'Rolled items'),
('Bottle', 'BTL', 'Bottles'),
('Carton', 'CTN', 'Cartons'),
('Unit', 'UNIT', 'Generic unit');

-- Stores/Locations
INSERT INTO stores (store_name, store_code, location, store_type, is_active) VALUES
('Main Store', 'MAIN', 'Building A, Ground Floor', 'main', TRUE),
('IT Store', 'IT-STORE', 'Building B, 2nd Floor', 'department', TRUE),
('Maintenance Store', 'MAINT-STORE', 'Workshop Area', 'department', TRUE);

-- Fuel Types
INSERT INTO fuel_types (fuel_type_name, fuel_code, description) VALUES
('Petrol', 'PETROL', 'Regular petrol/gasoline'),
('Diesel', 'DIESEL', 'Diesel fuel'),
('Premium Petrol', 'PREMIUM', 'Premium grade petrol');

-- Suppliers
INSERT INTO suppliers (supplier_name, supplier_code, contact_person, email, phone, supplier_type, is_active) VALUES
('ABC Office Supplies', 'ABC-OFF', 'John Smith', 'john@abcoffice.com', '555-0101', 'general', TRUE),
('TechWorld Ltd', 'TECH-WLD', 'Mary Johnson', 'mary@techworld.com', '555-0102', 'general', TRUE),
('Fuel Station A', 'FUEL-A', 'Robert Brown', 'robert@fuelstation.com', '555-0103', 'fuel_vendor', TRUE),
('Fuel Station B', 'FUEL-B', 'Sarah Davis', 'sarah@fuelstationb.com', '555-0104', 'fuel_vendor', TRUE),
('CleanCo Supplies', 'CLEAN-CO', 'Mike Wilson', 'mike@cleanco.com', '555-0105', 'general', TRUE);

-- Sample Items
INSERT INTO items (sku, item_name, category_id, uom_id, minimum_stock_level, reorder_level, unit_cost, is_active) VALUES
-- Office Supplies
('OFF-001', 'A4 Paper - White', 1, 4, 20, 50, 4.50, TRUE),
('OFF-002', 'Ballpoint Pen - Blue', 1, 1, 100, 200, 0.50, TRUE),
('OFF-003', 'Stapler - Standard', 1, 1, 10, 20, 8.00, TRUE),
('OFF-004', 'File Folder - Manila', 1, 3, 50, 100, 1.20, TRUE),

-- IT Equipment
('IT-001', 'USB Flash Drive - 32GB', 2, 1, 20, 40, 12.00, TRUE),
('IT-002', 'HDMI Cable - 2m', 2, 1, 15, 30, 8.50, TRUE),
('IT-003', 'Wireless Mouse', 2, 1, 10, 25, 15.00, TRUE),

-- Fuel Coupons (Special items)
('FUEL-PETROL-50', 'Petrol Coupon - $50', 5, 12, 0, 0, 50.00, TRUE),
('FUEL-DIESEL-50', 'Diesel Coupon - $50', 5, 12, 0, 0, 50.00, TRUE),

-- Cleaning Supplies
('CLEAN-001', 'All-Purpose Cleaner - 1L', 4, 10, 20, 40, 5.50, TRUE),
('CLEAN-002', 'Trash Bags - Large', 4, 11, 30, 60, 12.00, TRUE),

-- Tools
('TOOL-001', 'Screwdriver Set - 10pcs', 6, 8, 5, 10, 25.00, TRUE),
('TOOL-002', 'Hammer - 500g', 6, 1, 5, 10, 18.00, TRUE);

-- Sample Vehicles
INSERT INTO vehicles (vehicle_number, vehicle_type, fuel_type_id, department_id, status) VALUES
('VEH-001', 'Sedan', 1, 5, 'active'),
('VEH-002', 'Pickup Truck', 2, 8, 'active'),
('VEH-003', 'Van', 2, 5, 'active'),
('GEN-001', 'Generator - 5KVA', 2, 8, 'active');
