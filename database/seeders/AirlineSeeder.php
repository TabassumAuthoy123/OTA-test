<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            // Bangladesh
            ['name' => 'Biman Bangladesh Airlines', 'iata' => 'BG', 'icao' => 'BBC', 'active' => 'Y'],
            ['name' => 'US-Bangla Airlines', 'iata' => 'BS', 'icao' => 'UBG', 'active' => 'Y'],
            ['name' => 'NovoAir', 'iata' => 'VQ', 'icao' => 'NVQ', 'active' => 'Y'],

            // Middle East
            ['name' => 'Emirates', 'iata' => 'EK', 'icao' => 'UAE', 'active' => 'Y'],
            ['name' => 'Qatar Airways', 'iata' => 'QR', 'icao' => 'QTR', 'active' => 'Y'],
            ['name' => 'Etihad Airways', 'iata' => 'EY', 'icao' => 'ETD', 'active' => 'Y'],
            ['name' => 'flydubai', 'iata' => 'FZ', 'icao' => 'FDB', 'active' => 'Y'],
            ['name' => 'Air Arabia', 'iata' => 'G9', 'icao' => 'ABY', 'active' => 'Y'],
            ['name' => 'Kuwait Airways', 'iata' => 'KU', 'icao' => 'KAC', 'active' => 'Y'],
            ['name' => 'Gulf Air', 'iata' => 'GF', 'icao' => 'GFA', 'active' => 'Y'],
            ['name' => 'Oman Air', 'iata' => 'WY', 'icao' => 'OMA', 'active' => 'Y'],
            ['name' => 'SaudiA (Saudi Arabian Airlines)', 'iata' => 'SV', 'icao' => 'SVA', 'active' => 'Y'],
            ['name' => 'Flynas', 'iata' => 'XY', 'icao' => 'KNE', 'active' => 'Y'],

            // South Asia
            ['name' => 'IndiGo', 'iata' => '6E', 'icao' => 'IGO', 'active' => 'Y'],
            ['name' => 'Air India', 'iata' => 'AI', 'icao' => 'AIC', 'active' => 'Y'],
            ['name' => 'SpiceJet', 'iata' => 'SG', 'icao' => 'SEJ', 'active' => 'Y'],
            ['name' => 'Vistara', 'iata' => 'UK', 'icao' => 'VTI', 'active' => 'Y'],
            ['name' => 'Pakistan International Airlines', 'iata' => 'PK', 'icao' => 'PIA', 'active' => 'Y'],
            ['name' => 'SriLankan Airlines', 'iata' => 'UL', 'icao' => 'ALK', 'active' => 'Y'],
            ['name' => 'Nepal Airlines', 'iata' => 'RA', 'icao' => 'RNA', 'active' => 'Y'],

            // Southeast Asia
            ['name' => 'Malaysia Airlines', 'iata' => 'MH', 'icao' => 'MAS', 'active' => 'Y'],
            ['name' => 'AirAsia', 'iata' => 'AK', 'icao' => 'AXM', 'active' => 'Y'],
            ['name' => 'Singapore Airlines', 'iata' => 'SQ', 'icao' => 'SIA', 'active' => 'Y'],
            ['name' => 'Thai Airways', 'iata' => 'TG', 'icao' => 'THA', 'active' => 'Y'],
            ['name' => 'Bangkok Airways', 'iata' => 'PG', 'icao' => 'BKP', 'active' => 'Y'],
            ['name' => 'Philippine Airlines', 'iata' => 'PR', 'icao' => 'PAL', 'active' => 'Y'],
            ['name' => 'Garuda Indonesia', 'iata' => 'GA', 'icao' => 'GIA', 'active' => 'Y'],

            // East Asia
            ['name' => 'China Eastern Airlines', 'iata' => 'MU', 'icao' => 'CES', 'active' => 'Y'],
            ['name' => 'China Southern Airlines', 'iata' => 'CZ', 'icao' => 'CSN', 'active' => 'Y'],
            ['name' => 'Air China', 'iata' => 'CA', 'icao' => 'CCA', 'active' => 'Y'],
            ['name' => 'Korean Air', 'iata' => 'KE', 'icao' => 'KAL', 'active' => 'Y'],
            ['name' => 'Asiana Airlines', 'iata' => 'OZ', 'icao' => 'AAR', 'active' => 'Y'],
            ['name' => 'Japan Airlines', 'iata' => 'JL', 'icao' => 'JAL', 'active' => 'Y'],
            ['name' => 'All Nippon Airways', 'iata' => 'NH', 'icao' => 'ANA', 'active' => 'Y'],

            // Europe
            ['name' => 'Turkish Airlines', 'iata' => 'TK', 'icao' => 'THY', 'active' => 'Y'],
            ['name' => 'British Airways', 'iata' => 'BA', 'icao' => 'BAW', 'active' => 'Y'],
            ['name' => 'Lufthansa', 'iata' => 'LH', 'icao' => 'DLH', 'active' => 'Y'],
            ['name' => 'Air France', 'iata' => 'AF', 'icao' => 'AFR', 'active' => 'Y'],
            ['name' => 'KLM Royal Dutch Airlines', 'iata' => 'KL', 'icao' => 'KLM', 'active' => 'Y'],

            // Africa
            ['name' => 'Ethiopian Airlines', 'iata' => 'ET', 'icao' => 'ETH', 'active' => 'Y'],
            ['name' => 'EgyptAir', 'iata' => 'MS', 'icao' => 'MSR', 'active' => 'Y'],
            ['name' => 'Royal Jordanian', 'iata' => 'RJ', 'icao' => 'RJA', 'active' => 'Y'],

            // North America / Oceania
            ['name' => 'United Airlines', 'iata' => 'UA', 'icao' => 'UAL', 'active' => 'Y'],
            ['name' => 'American Airlines', 'iata' => 'AA', 'icao' => 'AAL', 'active' => 'Y'],
            ['name' => 'Air Canada', 'iata' => 'AC', 'icao' => 'ACA', 'active' => 'Y'],
            ['name' => 'Qantas', 'iata' => 'QF', 'icao' => 'QFA', 'active' => 'Y'],
        ];

        DB::table('airlines')->insert($airlines);
    }
}
