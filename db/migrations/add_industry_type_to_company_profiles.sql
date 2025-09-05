-- Add industry_type field to company_profiles table
ALTER TABLE company_profiles 
ADD COLUMN industry_type VARCHAR(50) AFTER company_name;

-- Add phone_number field to company_profiles table
ALTER TABLE company_profiles 
ADD COLUMN phone_number VARCHAR(20) AFTER address;
