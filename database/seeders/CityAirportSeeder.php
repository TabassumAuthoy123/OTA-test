<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityAirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            // Bangladesh
            ['city_name' => 'Dhaka', 'city_code' => 'DAC', 'airport_name' => 'Hazrat Shahjalal International Airport', 'airport_code' => 'DAC', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Chittagong', 'city_code' => 'CGP', 'airport_name' => 'Shah Amanat International Airport', 'airport_code' => 'CGP', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Sylhet', 'city_code' => 'ZYL', 'airport_name' => 'Osmani International Airport', 'airport_code' => 'ZYL', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Cox\'s Bazar', 'city_code' => 'CXB', 'airport_name' => 'Cox\'s Bazar Airport', 'airport_code' => 'CXB', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Jessore', 'city_code' => 'JSR', 'airport_name' => 'Jessore Airport', 'airport_code' => 'JSR', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Barisal', 'city_code' => 'BZL', 'airport_name' => 'Barisal Airport', 'airport_code' => 'BZL', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],
            ['city_name' => 'Rajshahi', 'city_code' => 'RJH', 'airport_name' => 'Shah Makhdum Airport', 'airport_code' => 'RJH', 'country_name' => 'Bangladesh', 'country_code' => 'BD'],

            // India
            ['city_name' => 'Delhi', 'city_code' => 'DEL', 'airport_name' => 'Indira Gandhi International Airport', 'airport_code' => 'DEL', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Mumbai', 'city_code' => 'BOM', 'airport_name' => 'Chhatrapati Shivaji Maharaj International Airport', 'airport_code' => 'BOM', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Kolkata', 'city_code' => 'CCU', 'airport_name' => 'Netaji Subhas Chandra Bose International Airport', 'airport_code' => 'CCU', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Chennai', 'city_code' => 'MAA', 'airport_name' => 'Chennai International Airport', 'airport_code' => 'MAA', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Bangalore', 'city_code' => 'BLR', 'airport_name' => 'Kempegowda International Airport', 'airport_code' => 'BLR', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Hyderabad', 'city_code' => 'HYD', 'airport_name' => 'Rajiv Gandhi International Airport', 'airport_code' => 'HYD', 'country_name' => 'India', 'country_code' => 'IN'],
            ['city_name' => 'Goa', 'city_code' => 'GOI', 'airport_name' => 'Goa International Airport', 'airport_code' => 'GOI', 'country_name' => 'India', 'country_code' => 'IN'],

            // UAE
            ['city_name' => 'Dubai', 'city_code' => 'DXB', 'airport_name' => 'Dubai International Airport', 'airport_code' => 'DXB', 'country_name' => 'United Arab Emirates', 'country_code' => 'AE'],
            ['city_name' => 'Abu Dhabi', 'city_code' => 'AUH', 'airport_name' => 'Abu Dhabi International Airport', 'airport_code' => 'AUH', 'country_name' => 'United Arab Emirates', 'country_code' => 'AE'],
            ['city_name' => 'Sharjah', 'city_code' => 'SHJ', 'airport_name' => 'Sharjah International Airport', 'airport_code' => 'SHJ', 'country_name' => 'United Arab Emirates', 'country_code' => 'AE'],

            // Saudi Arabia
            ['city_name' => 'Riyadh', 'city_code' => 'RUH', 'airport_name' => 'King Khalid International Airport', 'airport_code' => 'RUH', 'country_name' => 'Saudi Arabia', 'country_code' => 'SA'],
            ['city_name' => 'Jeddah', 'city_code' => 'JED', 'airport_name' => 'King Abdulaziz International Airport', 'airport_code' => 'JED', 'country_name' => 'Saudi Arabia', 'country_code' => 'SA'],
            ['city_name' => 'Dammam', 'city_code' => 'DMM', 'airport_name' => 'King Fahd International Airport', 'airport_code' => 'DMM', 'country_name' => 'Saudi Arabia', 'country_code' => 'SA'],
            ['city_name' => 'Madinah', 'city_code' => 'MED', 'airport_name' => 'Prince Mohammed bin Abdulaziz Airport', 'airport_code' => 'MED', 'country_name' => 'Saudi Arabia', 'country_code' => 'SA'],

            // Qatar
            ['city_name' => 'Doha', 'city_code' => 'DOH', 'airport_name' => 'Hamad International Airport', 'airport_code' => 'DOH', 'country_name' => 'Qatar', 'country_code' => 'QA'],

            // Kuwait
            ['city_name' => 'Kuwait City', 'city_code' => 'KWI', 'airport_name' => 'Kuwait International Airport', 'airport_code' => 'KWI', 'country_name' => 'Kuwait', 'country_code' => 'KW'],

            // Bahrain
            ['city_name' => 'Manama', 'city_code' => 'BAH', 'airport_name' => 'Bahrain International Airport', 'airport_code' => 'BAH', 'country_name' => 'Bahrain', 'country_code' => 'BH'],

            // Oman
            ['city_name' => 'Muscat', 'city_code' => 'MCT', 'airport_name' => 'Muscat International Airport', 'airport_code' => 'MCT', 'country_name' => 'Oman', 'country_code' => 'OM'],

            // Malaysia
            ['city_name' => 'Kuala Lumpur', 'city_code' => 'KUL', 'airport_name' => 'Kuala Lumpur International Airport', 'airport_code' => 'KUL', 'country_name' => 'Malaysia', 'country_code' => 'MY'],

            // Singapore
            ['city_name' => 'Singapore', 'city_code' => 'SIN', 'airport_name' => 'Singapore Changi Airport', 'airport_code' => 'SIN', 'country_name' => 'Singapore', 'country_code' => 'SG'],

            // Thailand
            ['city_name' => 'Bangkok', 'city_code' => 'BKK', 'airport_name' => 'Suvarnabhumi Airport', 'airport_code' => 'BKK', 'country_name' => 'Thailand', 'country_code' => 'TH'],
            ['city_name' => 'Bangkok', 'city_code' => 'DMK', 'airport_name' => 'Don Mueang International Airport', 'airport_code' => 'DMK', 'country_name' => 'Thailand', 'country_code' => 'TH'],
            ['city_name' => 'Phuket', 'city_code' => 'HKT', 'airport_name' => 'Phuket International Airport', 'airport_code' => 'HKT', 'country_name' => 'Thailand', 'country_code' => 'TH'],

            // Nepal
            ['city_name' => 'Kathmandu', 'city_code' => 'KTM', 'airport_name' => 'Tribhuvan International Airport', 'airport_code' => 'KTM', 'country_name' => 'Nepal', 'country_code' => 'NP'],

            // Pakistan
            ['city_name' => 'Karachi', 'city_code' => 'KHI', 'airport_name' => 'Jinnah International Airport', 'airport_code' => 'KHI', 'country_name' => 'Pakistan', 'country_code' => 'PK'],
            ['city_name' => 'Lahore', 'city_code' => 'LHE', 'airport_name' => 'Allama Iqbal International Airport', 'airport_code' => 'LHE', 'country_name' => 'Pakistan', 'country_code' => 'PK'],
            ['city_name' => 'Islamabad', 'city_code' => 'ISB', 'airport_name' => 'Islamabad International Airport', 'airport_code' => 'ISB', 'country_name' => 'Pakistan', 'country_code' => 'PK'],

            // Sri Lanka
            ['city_name' => 'Colombo', 'city_code' => 'CMB', 'airport_name' => 'Bandaranaike International Airport', 'airport_code' => 'CMB', 'country_name' => 'Sri Lanka', 'country_code' => 'LK'],

            // Turkey
            ['city_name' => 'Istanbul', 'city_code' => 'IST', 'airport_name' => 'Istanbul Airport', 'airport_code' => 'IST', 'country_name' => 'Turkey', 'country_code' => 'TR'],

            // UK
            ['city_name' => 'London', 'city_code' => 'LHR', 'airport_name' => 'Heathrow Airport', 'airport_code' => 'LHR', 'country_name' => 'United Kingdom', 'country_code' => 'GB'],
            ['city_name' => 'London', 'city_code' => 'LGW', 'airport_name' => 'Gatwick Airport', 'airport_code' => 'LGW', 'country_name' => 'United Kingdom', 'country_code' => 'GB'],
            ['city_name' => 'London', 'city_code' => 'STN', 'airport_name' => 'Stansted Airport', 'airport_code' => 'STN', 'country_name' => 'United Kingdom', 'country_code' => 'GB'],

            // USA
            ['city_name' => 'New York', 'city_code' => 'JFK', 'airport_name' => 'John F. Kennedy International Airport', 'airport_code' => 'JFK', 'country_name' => 'United States', 'country_code' => 'US'],
            ['city_name' => 'New York', 'city_code' => 'EWR', 'airport_name' => 'Newark Liberty International Airport', 'airport_code' => 'EWR', 'country_name' => 'United States', 'country_code' => 'US'],
            ['city_name' => 'Los Angeles', 'city_code' => 'LAX', 'airport_name' => 'Los Angeles International Airport', 'airport_code' => 'LAX', 'country_name' => 'United States', 'country_code' => 'US'],
            ['city_name' => 'Chicago', 'city_code' => 'ORD', 'airport_name' => 'O\'Hare International Airport', 'airport_code' => 'ORD', 'country_name' => 'United States', 'country_code' => 'US'],
            ['city_name' => 'Washington', 'city_code' => 'IAD', 'airport_name' => 'Washington Dulles International Airport', 'airport_code' => 'IAD', 'country_name' => 'United States', 'country_code' => 'US'],

            // Canada
            ['city_name' => 'Toronto', 'city_code' => 'YYZ', 'airport_name' => 'Toronto Pearson International Airport', 'airport_code' => 'YYZ', 'country_name' => 'Canada', 'country_code' => 'CA'],

            // Australia
            ['city_name' => 'Sydney', 'city_code' => 'SYD', 'airport_name' => 'Sydney Airport', 'airport_code' => 'SYD', 'country_name' => 'Australia', 'country_code' => 'AU'],
            ['city_name' => 'Melbourne', 'city_code' => 'MEL', 'airport_name' => 'Melbourne Airport', 'airport_code' => 'MEL', 'country_name' => 'Australia', 'country_code' => 'AU'],

            // China
            ['city_name' => 'Beijing', 'city_code' => 'PEK', 'airport_name' => 'Beijing Capital International Airport', 'airport_code' => 'PEK', 'country_name' => 'China', 'country_code' => 'CN'],
            ['city_name' => 'Shanghai', 'city_code' => 'PVG', 'airport_name' => 'Shanghai Pudong International Airport', 'airport_code' => 'PVG', 'country_name' => 'China', 'country_code' => 'CN'],
            ['city_name' => 'Guangzhou', 'city_code' => 'CAN', 'airport_name' => 'Guangzhou Baiyun International Airport', 'airport_code' => 'CAN', 'country_name' => 'China', 'country_code' => 'CN'],

            // Japan
            ['city_name' => 'Tokyo', 'city_code' => 'NRT', 'airport_name' => 'Narita International Airport', 'airport_code' => 'NRT', 'country_name' => 'Japan', 'country_code' => 'JP'],
            ['city_name' => 'Tokyo', 'city_code' => 'HND', 'airport_name' => 'Haneda Airport', 'airport_code' => 'HND', 'country_name' => 'Japan', 'country_code' => 'JP'],

            // South Korea
            ['city_name' => 'Seoul', 'city_code' => 'ICN', 'airport_name' => 'Incheon International Airport', 'airport_code' => 'ICN', 'country_name' => 'South Korea', 'country_code' => 'KR'],

            // Indonesia
            ['city_name' => 'Jakarta', 'city_code' => 'CGK', 'airport_name' => 'Soekarno-Hatta International Airport', 'airport_code' => 'CGK', 'country_name' => 'Indonesia', 'country_code' => 'ID'],

            // Philippines
            ['city_name' => 'Manila', 'city_code' => 'MNL', 'airport_name' => 'Ninoy Aquino International Airport', 'airport_code' => 'MNL', 'country_name' => 'Philippines', 'country_code' => 'PH'],

            // Myanmar
            ['city_name' => 'Yangon', 'city_code' => 'RGN', 'airport_name' => 'Yangon International Airport', 'airport_code' => 'RGN', 'country_name' => 'Myanmar', 'country_code' => 'MM'],

            // Maldives
            ['city_name' => 'Male', 'city_code' => 'MLE', 'airport_name' => 'Velana International Airport', 'airport_code' => 'MLE', 'country_name' => 'Maldives', 'country_code' => 'MV'],

            // Bhutan
            ['city_name' => 'Paro', 'city_code' => 'PBH', 'airport_name' => 'Paro Airport', 'airport_code' => 'PBH', 'country_name' => 'Bhutan', 'country_code' => 'BT'],

            // Germany
            ['city_name' => 'Frankfurt', 'city_code' => 'FRA', 'airport_name' => 'Frankfurt Airport', 'airport_code' => 'FRA', 'country_name' => 'Germany', 'country_code' => 'DE'],

            // France
            ['city_name' => 'Paris', 'city_code' => 'CDG', 'airport_name' => 'Charles de Gaulle Airport', 'airport_code' => 'CDG', 'country_name' => 'France', 'country_code' => 'FR'],

            // Italy
            ['city_name' => 'Rome', 'city_code' => 'FCO', 'airport_name' => 'Leonardo da Vinci International Airport', 'airport_code' => 'FCO', 'country_name' => 'Italy', 'country_code' => 'IT'],

            // Netherlands
            ['city_name' => 'Amsterdam', 'city_code' => 'AMS', 'airport_name' => 'Amsterdam Airport Schiphol', 'airport_code' => 'AMS', 'country_name' => 'Netherlands', 'country_code' => 'NL'],

            // Ethiopia
            ['city_name' => 'Addis Ababa', 'city_code' => 'ADD', 'airport_name' => 'Addis Ababa Bole International Airport', 'airport_code' => 'ADD', 'country_name' => 'Ethiopia', 'country_code' => 'ET'],

            // Egypt
            ['city_name' => 'Cairo', 'city_code' => 'CAI', 'airport_name' => 'Cairo International Airport', 'airport_code' => 'CAI', 'country_name' => 'Egypt', 'country_code' => 'EG'],

            // Jordan
            ['city_name' => 'Amman', 'city_code' => 'AMM', 'airport_name' => 'Queen Alia International Airport', 'airport_code' => 'AMM', 'country_name' => 'Jordan', 'country_code' => 'JO'],
        ];

        DB::table('city_airports')->insert($airports);
    }
}
