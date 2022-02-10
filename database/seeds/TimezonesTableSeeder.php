<?php

use Illuminate\Database\Seeder;

class TimezonesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('timezones')->delete();
        
        \DB::table('timezones')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Pacific/Midway',
                'utc_offset' => '-11:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pacific/Niue',
                'utc_offset' => '-11:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Pacific/Pago_Pago',
                'utc_offset' => '-11:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Pacific/Honolulu',
                'utc_offset' => '-10:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Pacific/Rarotonga',
                'utc_offset' => '-10:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Pacific/Tahiti',
                'utc_offset' => '-10:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Pacific/Marquesas',
                'utc_offset' => '-09:30',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'America/Adak',
                'utc_offset' => '-09:00',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Pacific/Gambier',
                'utc_offset' => '-09:00',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'America/Anchorage',
                'utc_offset' => '-08:00',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'America/Juneau',
                'utc_offset' => '-08:00',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'America/Metlakatla',
                'utc_offset' => '-08:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'America/Nome',
                'utc_offset' => '-08:00',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'America/Sitka',
                'utc_offset' => '-08:00',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'America/Yakutat',
                'utc_offset' => '-08:00',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Pacific/Pitcairn',
                'utc_offset' => '-08:00',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'America/Creston',
                'utc_offset' => '-07:00',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'America/Dawson',
                'utc_offset' => '-07:00',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'America/Dawson_Creek',
                'utc_offset' => '-07:00',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'America/Fort_Nelson',
                'utc_offset' => '-07:00',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'America/Hermosillo',
                'utc_offset' => '-07:00',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'America/Los_Angeles',
                'utc_offset' => '-07:00',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'America/Phoenix',
                'utc_offset' => '-07:00',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'America/Tijuana',
                'utc_offset' => '-07:00',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'America/Vancouver',
                'utc_offset' => '-07:00',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'America/Whitehorse',
                'utc_offset' => '-07:00',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'America/Belize',
                'utc_offset' => '-06:00',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'America/Boise',
                'utc_offset' => '-06:00',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'America/Cambridge_Bay',
                'utc_offset' => '-06:00',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'America/Chihuahua',
                'utc_offset' => '-06:00',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'America/Costa_Rica',
                'utc_offset' => '-06:00',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'America/Denver',
                'utc_offset' => '-06:00',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'America/Edmonton',
                'utc_offset' => '-06:00',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'America/El_Salvador',
                'utc_offset' => '-06:00',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'America/Guatemala',
                'utc_offset' => '-06:00',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'America/Inuvik',
                'utc_offset' => '-06:00',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'America/Managua',
                'utc_offset' => '-06:00',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'America/Mazatlan',
                'utc_offset' => '-06:00',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'America/Ojinaga',
                'utc_offset' => '-06:00',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'America/Regina',
                'utc_offset' => '-06:00',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'America/Swift_Current',
                'utc_offset' => '-06:00',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'America/Tegucigalpa',
                'utc_offset' => '-06:00',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'America/Yellowknife',
                'utc_offset' => '-06:00',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'Pacific/Galapagos',
                'utc_offset' => '-06:00',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'America/Atikokan',
                'utc_offset' => '-05:00',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'America/Bahia_Banderas',
                'utc_offset' => '-05:00',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'America/Bogota',
                'utc_offset' => '-05:00',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'America/Cancun',
                'utc_offset' => '-05:00',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'America/Cayman',
                'utc_offset' => '-05:00',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'America/Chicago',
                'utc_offset' => '-05:00',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'America/Eirunepe',
                'utc_offset' => '-05:00',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'America/Guayaquil',
                'utc_offset' => '-05:00',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'America/Indiana/Knox',
                'utc_offset' => '-05:00',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'America/Indiana/Tell_City',
                'utc_offset' => '-05:00',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'America/Jamaica',
                'utc_offset' => '-05:00',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'America/Lima',
                'utc_offset' => '-05:00',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'America/Matamoros',
                'utc_offset' => '-05:00',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'America/Menominee',
                'utc_offset' => '-05:00',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'America/Merida',
                'utc_offset' => '-05:00',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'America/Mexico_City',
                'utc_offset' => '-05:00',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'America/Monterrey',
                'utc_offset' => '-05:00',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'America/North_Dakota/Beulah',
                'utc_offset' => '-05:00',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'America/North_Dakota/Center',
                'utc_offset' => '-05:00',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'America/North_Dakota/New_Salem',
                'utc_offset' => '-05:00',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'America/Panama',
                'utc_offset' => '-05:00',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'America/Rainy_River',
                'utc_offset' => '-05:00',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'America/Rankin_Inlet',
                'utc_offset' => '-05:00',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'America/Resolute',
                'utc_offset' => '-05:00',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'America/Rio_Branco',
                'utc_offset' => '-05:00',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'America/Winnipeg',
                'utc_offset' => '-05:00',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'Pacific/Easter',
                'utc_offset' => '-05:00',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'America/Anguilla',
                'utc_offset' => '-04:00',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'America/Antigua',
                'utc_offset' => '-04:00',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'America/Aruba',
                'utc_offset' => '-04:00',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'America/Asuncion',
                'utc_offset' => '-04:00',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'America/Barbados',
                'utc_offset' => '-04:00',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'America/Blanc-Sablon',
                'utc_offset' => '-04:00',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'America/Boa_Vista',
                'utc_offset' => '-04:00',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'America/Campo_Grande',
                'utc_offset' => '-04:00',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'America/Caracas',
                'utc_offset' => '-04:00',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'America/Cuiaba',
                'utc_offset' => '-04:00',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'America/Curacao',
                'utc_offset' => '-04:00',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'America/Detroit',
                'utc_offset' => '-04:00',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'America/Dominica',
                'utc_offset' => '-04:00',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'America/Grand_Turk',
                'utc_offset' => '-04:00',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'America/Grenada',
                'utc_offset' => '-04:00',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'America/Guadeloupe',
                'utc_offset' => '-04:00',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'America/Guyana',
                'utc_offset' => '-04:00',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'America/Havana',
                'utc_offset' => '-04:00',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'America/Indiana/Indianapolis',
                'utc_offset' => '-04:00',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'America/Indiana/Marengo',
                'utc_offset' => '-04:00',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'America/Indiana/Petersburg',
                'utc_offset' => '-04:00',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'America/Indiana/Vevay',
                'utc_offset' => '-04:00',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'America/Indiana/Vincennes',
                'utc_offset' => '-04:00',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'America/Indiana/Winamac',
                'utc_offset' => '-04:00',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'America/Iqaluit',
                'utc_offset' => '-04:00',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'America/Kentucky/Louisville',
                'utc_offset' => '-04:00',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'America/Kentucky/Monticello',
                'utc_offset' => '-04:00',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'America/Kralendijk',
                'utc_offset' => '-04:00',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'America/La_Paz',
                'utc_offset' => '-04:00',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'America/Lower_Princes',
                'utc_offset' => '-04:00',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'America/Manaus',
                'utc_offset' => '-04:00',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'America/Marigot',
                'utc_offset' => '-04:00',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'America/Martinique',
                'utc_offset' => '-04:00',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'America/Montserrat',
                'utc_offset' => '-04:00',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'America/Nassau',
                'utc_offset' => '-04:00',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'America/New_York',
                'utc_offset' => '-04:00',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'America/Nipigon',
                'utc_offset' => '-04:00',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'America/Pangnirtung',
                'utc_offset' => '-04:00',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'America/Port-au-Prince',
                'utc_offset' => '-04:00',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'America/Port_of_Spain',
                'utc_offset' => '-04:00',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'America/Porto_Velho',
                'utc_offset' => '-04:00',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'America/Puerto_Rico',
                'utc_offset' => '-04:00',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'America/Santo_Domingo',
                'utc_offset' => '-04:00',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'America/St_Barthelemy',
                'utc_offset' => '-04:00',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'America/St_Kitts',
                'utc_offset' => '-04:00',
            ),
            116 => 
            array (
                'id' => 117,
                'name' => 'America/St_Lucia',
                'utc_offset' => '-04:00',
            ),
            117 => 
            array (
                'id' => 118,
                'name' => 'America/St_Thomas',
                'utc_offset' => '-04:00',
            ),
            118 => 
            array (
                'id' => 119,
                'name' => 'America/St_Vincent',
                'utc_offset' => '-04:00',
            ),
            119 => 
            array (
                'id' => 120,
                'name' => 'America/Thunder_Bay',
                'utc_offset' => '-04:00',
            ),
            120 => 
            array (
                'id' => 121,
                'name' => 'America/Toronto',
                'utc_offset' => '-04:00',
            ),
            121 => 
            array (
                'id' => 122,
                'name' => 'America/Tortola',
                'utc_offset' => '-04:00',
            ),
            122 => 
            array (
                'id' => 123,
                'name' => 'America/Araguaina',
                'utc_offset' => '-03:00',
            ),
            123 => 
            array (
                'id' => 124,
                'name' => 'America/Argentina/Buenos_Aires',
                'utc_offset' => '-03:00',
            ),
            124 => 
            array (
                'id' => 125,
                'name' => 'America/Argentina/Catamarca',
                'utc_offset' => '-03:00',
            ),
            125 => 
            array (
                'id' => 126,
                'name' => 'America/Argentina/Cordoba',
                'utc_offset' => '-03:00',
            ),
            126 => 
            array (
                'id' => 127,
                'name' => 'America/Argentina/Jujuy',
                'utc_offset' => '-03:00',
            ),
            127 => 
            array (
                'id' => 128,
                'name' => 'America/Argentina/La_Rioja',
                'utc_offset' => '-03:00',
            ),
            128 => 
            array (
                'id' => 129,
                'name' => 'America/Argentina/Mendoza',
                'utc_offset' => '-03:00',
            ),
            129 => 
            array (
                'id' => 130,
                'name' => 'America/Argentina/Rio_Gallegos',
                'utc_offset' => '-03:00',
            ),
            130 => 
            array (
                'id' => 131,
                'name' => 'America/Argentina/Salta',
                'utc_offset' => '-03:00',
            ),
            131 => 
            array (
                'id' => 132,
                'name' => 'America/Argentina/San_Juan',
                'utc_offset' => '-03:00',
            ),
            132 => 
            array (
                'id' => 133,
                'name' => 'America/Argentina/San_Luis',
                'utc_offset' => '-03:00',
            ),
            133 => 
            array (
                'id' => 134,
                'name' => 'America/Argentina/Tucuman',
                'utc_offset' => '-03:00',
            ),
            134 => 
            array (
                'id' => 135,
                'name' => 'America/Argentina/Ushuaia',
                'utc_offset' => '-03:00',
            ),
            135 => 
            array (
                'id' => 136,
                'name' => 'America/Bahia',
                'utc_offset' => '-03:00',
            ),
            136 => 
            array (
                'id' => 137,
                'name' => 'America/Belem',
                'utc_offset' => '-03:00',
            ),
            137 => 
            array (
                'id' => 138,
                'name' => 'America/Cayenne',
                'utc_offset' => '-03:00',
            ),
            138 => 
            array (
                'id' => 139,
                'name' => 'America/Fortaleza',
                'utc_offset' => '-03:00',
            ),
            139 => 
            array (
                'id' => 140,
                'name' => 'America/Glace_Bay',
                'utc_offset' => '-03:00',
            ),
            140 => 
            array (
                'id' => 141,
                'name' => 'America/Goose_Bay',
                'utc_offset' => '-03:00',
            ),
            141 => 
            array (
                'id' => 142,
                'name' => 'America/Halifax',
                'utc_offset' => '-03:00',
            ),
            142 => 
            array (
                'id' => 143,
                'name' => 'America/Maceio',
                'utc_offset' => '-03:00',
            ),
            143 => 
            array (
                'id' => 144,
                'name' => 'America/Moncton',
                'utc_offset' => '-03:00',
            ),
            144 => 
            array (
                'id' => 145,
                'name' => 'America/Montevideo',
                'utc_offset' => '-03:00',
            ),
            145 => 
            array (
                'id' => 146,
                'name' => 'America/Paramaribo',
                'utc_offset' => '-03:00',
            ),
            146 => 
            array (
                'id' => 147,
                'name' => 'America/Punta_Arenas',
                'utc_offset' => '-03:00',
            ),
            147 => 
            array (
                'id' => 148,
                'name' => 'America/Recife',
                'utc_offset' => '-03:00',
            ),
            148 => 
            array (
                'id' => 149,
                'name' => 'America/Santarem',
                'utc_offset' => '-03:00',
            ),
            149 => 
            array (
                'id' => 150,
                'name' => 'America/Santiago',
                'utc_offset' => '-03:00',
            ),
            150 => 
            array (
                'id' => 151,
                'name' => 'America/Sao_Paulo',
                'utc_offset' => '-03:00',
            ),
            151 => 
            array (
                'id' => 152,
                'name' => 'America/Thule',
                'utc_offset' => '-03:00',
            ),
            152 => 
            array (
                'id' => 153,
                'name' => 'Antarctica/Palmer',
                'utc_offset' => '-03:00',
            ),
            153 => 
            array (
                'id' => 154,
                'name' => 'Antarctica/Rothera',
                'utc_offset' => '-03:00',
            ),
            154 => 
            array (
                'id' => 155,
                'name' => 'Atlantic/Bermuda',
                'utc_offset' => '-03:00',
            ),
            155 => 
            array (
                'id' => 156,
                'name' => 'Atlantic/Stanley',
                'utc_offset' => '-03:00',
            ),
            156 => 
            array (
                'id' => 157,
                'name' => 'America/St_Johns',
                'utc_offset' => '-02:30',
            ),
            157 => 
            array (
                'id' => 158,
                'name' => 'America/Godthab',
                'utc_offset' => '-02:00',
            ),
            158 => 
            array (
                'id' => 159,
                'name' => 'America/Miquelon',
                'utc_offset' => '-02:00',
            ),
            159 => 
            array (
                'id' => 160,
                'name' => 'America/Noronha',
                'utc_offset' => '-02:00',
            ),
            160 => 
            array (
                'id' => 161,
                'name' => 'Atlantic/South_Georgia',
                'utc_offset' => '-02:00',
            ),
            161 => 
            array (
                'id' => 162,
                'name' => 'Atlantic/Cape_Verde',
                'utc_offset' => '-01:00',
            ),
            162 => 
            array (
                'id' => 163,
                'name' => 'Africa/Abidjan',
                'utc_offset' => '+00:00',
            ),
            163 => 
            array (
                'id' => 164,
                'name' => 'Africa/Accra',
                'utc_offset' => '+00:00',
            ),
            164 => 
            array (
                'id' => 165,
                'name' => 'Africa/Bamako',
                'utc_offset' => '+00:00',
            ),
            165 => 
            array (
                'id' => 166,
                'name' => 'Africa/Banjul',
                'utc_offset' => '+00:00',
            ),
            166 => 
            array (
                'id' => 167,
                'name' => 'Africa/Bissau',
                'utc_offset' => '+00:00',
            ),
            167 => 
            array (
                'id' => 168,
                'name' => 'Africa/Conakry',
                'utc_offset' => '+00:00',
            ),
            168 => 
            array (
                'id' => 169,
                'name' => 'Africa/Dakar',
                'utc_offset' => '+00:00',
            ),
            169 => 
            array (
                'id' => 170,
                'name' => 'Africa/Freetown',
                'utc_offset' => '+00:00',
            ),
            170 => 
            array (
                'id' => 171,
                'name' => 'Africa/Lome',
                'utc_offset' => '+00:00',
            ),
            171 => 
            array (
                'id' => 172,
                'name' => 'Africa/Monrovia',
                'utc_offset' => '+00:00',
            ),
            172 => 
            array (
                'id' => 173,
                'name' => 'Africa/Nouakchott',
                'utc_offset' => '+00:00',
            ),
            173 => 
            array (
                'id' => 174,
                'name' => 'Africa/Ouagadougou',
                'utc_offset' => '+00:00',
            ),
            174 => 
            array (
                'id' => 175,
                'name' => 'Africa/Sao_Tome',
                'utc_offset' => '+00:00',
            ),
            175 => 
            array (
                'id' => 176,
                'name' => 'America/Danmarkshavn',
                'utc_offset' => '+00:00',
            ),
            176 => 
            array (
                'id' => 177,
                'name' => 'America/Scoresbysund',
                'utc_offset' => '+00:00',
            ),
            177 => 
            array (
                'id' => 178,
                'name' => 'Atlantic/Azores',
                'utc_offset' => '+00:00',
            ),
            178 => 
            array (
                'id' => 179,
                'name' => 'Atlantic/Reykjavik',
                'utc_offset' => '+00:00',
            ),
            179 => 
            array (
                'id' => 180,
                'name' => 'Atlantic/St_Helena',
                'utc_offset' => '+00:00',
            ),
            180 => 
            array (
                'id' => 181,
                'name' => 'UTC',
                'utc_offset' => '+00:00',
            ),
            181 => 
            array (
                'id' => 182,
                'name' => 'Africa/Algiers',
                'utc_offset' => '+01:00',
            ),
            182 => 
            array (
                'id' => 183,
                'name' => 'Africa/Bangui',
                'utc_offset' => '+01:00',
            ),
            183 => 
            array (
                'id' => 184,
                'name' => 'Africa/Brazzaville',
                'utc_offset' => '+01:00',
            ),
            184 => 
            array (
                'id' => 185,
                'name' => 'Africa/Casablanca',
                'utc_offset' => '+01:00',
            ),
            185 => 
            array (
                'id' => 186,
                'name' => 'Africa/Douala',
                'utc_offset' => '+01:00',
            ),
            186 => 
            array (
                'id' => 187,
                'name' => 'Africa/El_Aaiun',
                'utc_offset' => '+01:00',
            ),
            187 => 
            array (
                'id' => 188,
                'name' => 'Africa/Kinshasa',
                'utc_offset' => '+01:00',
            ),
            188 => 
            array (
                'id' => 189,
                'name' => 'Africa/Lagos',
                'utc_offset' => '+01:00',
            ),
            189 => 
            array (
                'id' => 190,
                'name' => 'Africa/Libreville',
                'utc_offset' => '+01:00',
            ),
            190 => 
            array (
                'id' => 191,
                'name' => 'Africa/Luanda',
                'utc_offset' => '+01:00',
            ),
            191 => 
            array (
                'id' => 192,
                'name' => 'Africa/Malabo',
                'utc_offset' => '+01:00',
            ),
            192 => 
            array (
                'id' => 193,
                'name' => 'Africa/Ndjamena',
                'utc_offset' => '+01:00',
            ),
            193 => 
            array (
                'id' => 194,
                'name' => 'Africa/Niamey',
                'utc_offset' => '+01:00',
            ),
            194 => 
            array (
                'id' => 195,
                'name' => 'Africa/Porto-Novo',
                'utc_offset' => '+01:00',
            ),
            195 => 
            array (
                'id' => 196,
                'name' => 'Africa/Tunis',
                'utc_offset' => '+01:00',
            ),
            196 => 
            array (
                'id' => 197,
                'name' => 'Atlantic/Canary',
                'utc_offset' => '+01:00',
            ),
            197 => 
            array (
                'id' => 198,
                'name' => 'Atlantic/Faroe',
                'utc_offset' => '+01:00',
            ),
            198 => 
            array (
                'id' => 199,
                'name' => 'Atlantic/Madeira',
                'utc_offset' => '+01:00',
            ),
            199 => 
            array (
                'id' => 200,
                'name' => 'Europe/Dublin',
                'utc_offset' => '+01:00',
            ),
            200 => 
            array (
                'id' => 201,
                'name' => 'Europe/Guernsey',
                'utc_offset' => '+01:00',
            ),
            201 => 
            array (
                'id' => 202,
                'name' => 'Europe/Isle_of_Man',
                'utc_offset' => '+01:00',
            ),
            202 => 
            array (
                'id' => 203,
                'name' => 'Europe/Jersey',
                'utc_offset' => '+01:00',
            ),
            203 => 
            array (
                'id' => 204,
                'name' => 'Europe/Lisbon',
                'utc_offset' => '+01:00',
            ),
            204 => 
            array (
                'id' => 205,
                'name' => 'Europe/London',
                'utc_offset' => '+01:00',
            ),
            205 => 
            array (
                'id' => 206,
                'name' => 'Africa/Blantyre',
                'utc_offset' => '+02:00',
            ),
            206 => 
            array (
                'id' => 207,
                'name' => 'Africa/Bujumbura',
                'utc_offset' => '+02:00',
            ),
            207 => 
            array (
                'id' => 208,
                'name' => 'Africa/Cairo',
                'utc_offset' => '+02:00',
            ),
            208 => 
            array (
                'id' => 209,
                'name' => 'Africa/Ceuta',
                'utc_offset' => '+02:00',
            ),
            209 => 
            array (
                'id' => 210,
                'name' => 'Africa/Gaborone',
                'utc_offset' => '+02:00',
            ),
            210 => 
            array (
                'id' => 211,
                'name' => 'Africa/Harare',
                'utc_offset' => '+02:00',
            ),
            211 => 
            array (
                'id' => 212,
                'name' => 'Africa/Johannesburg',
                'utc_offset' => '+02:00',
            ),
            212 => 
            array (
                'id' => 213,
                'name' => 'Africa/Khartoum',
                'utc_offset' => '+02:00',
            ),
            213 => 
            array (
                'id' => 214,
                'name' => 'Africa/Kigali',
                'utc_offset' => '+02:00',
            ),
            214 => 
            array (
                'id' => 215,
                'name' => 'Africa/Lubumbashi',
                'utc_offset' => '+02:00',
            ),
            215 => 
            array (
                'id' => 216,
                'name' => 'Africa/Lusaka',
                'utc_offset' => '+02:00',
            ),
            216 => 
            array (
                'id' => 217,
                'name' => 'Africa/Maputo',
                'utc_offset' => '+02:00',
            ),
            217 => 
            array (
                'id' => 218,
                'name' => 'Africa/Maseru',
                'utc_offset' => '+02:00',
            ),
            218 => 
            array (
                'id' => 219,
                'name' => 'Africa/Mbabane',
                'utc_offset' => '+02:00',
            ),
            219 => 
            array (
                'id' => 220,
                'name' => 'Africa/Tripoli',
                'utc_offset' => '+02:00',
            ),
            220 => 
            array (
                'id' => 221,
                'name' => 'Africa/Windhoek',
                'utc_offset' => '+02:00',
            ),
            221 => 
            array (
                'id' => 222,
                'name' => 'Antarctica/Troll',
                'utc_offset' => '+02:00',
            ),
            222 => 
            array (
                'id' => 223,
                'name' => 'Arctic/Longyearbyen',
                'utc_offset' => '+02:00',
            ),
            223 => 
            array (
                'id' => 224,
                'name' => 'Europe/Amsterdam',
                'utc_offset' => '+02:00',
            ),
            224 => 
            array (
                'id' => 225,
                'name' => 'Europe/Andorra',
                'utc_offset' => '+02:00',
            ),
            225 => 
            array (
                'id' => 226,
                'name' => 'Europe/Belgrade',
                'utc_offset' => '+02:00',
            ),
            226 => 
            array (
                'id' => 227,
                'name' => 'Europe/Berlin',
                'utc_offset' => '+02:00',
            ),
            227 => 
            array (
                'id' => 228,
                'name' => 'Europe/Bratislava',
                'utc_offset' => '+02:00',
            ),
            228 => 
            array (
                'id' => 229,
                'name' => 'Europe/Brussels',
                'utc_offset' => '+02:00',
            ),
            229 => 
            array (
                'id' => 230,
                'name' => 'Europe/Budapest',
                'utc_offset' => '+02:00',
            ),
            230 => 
            array (
                'id' => 231,
                'name' => 'Europe/Busingen',
                'utc_offset' => '+02:00',
            ),
            231 => 
            array (
                'id' => 232,
                'name' => 'Europe/Copenhagen',
                'utc_offset' => '+02:00',
            ),
            232 => 
            array (
                'id' => 233,
                'name' => 'Europe/Gibraltar',
                'utc_offset' => '+02:00',
            ),
            233 => 
            array (
                'id' => 234,
                'name' => 'Europe/Kaliningrad',
                'utc_offset' => '+02:00',
            ),
            234 => 
            array (
                'id' => 235,
                'name' => 'Europe/Ljubljana',
                'utc_offset' => '+02:00',
            ),
            235 => 
            array (
                'id' => 236,
                'name' => 'Europe/Luxembourg',
                'utc_offset' => '+02:00',
            ),
            236 => 
            array (
                'id' => 237,
                'name' => 'Europe/Madrid',
                'utc_offset' => '+02:00',
            ),
            237 => 
            array (
                'id' => 238,
                'name' => 'Europe/Malta',
                'utc_offset' => '+02:00',
            ),
            238 => 
            array (
                'id' => 239,
                'name' => 'Europe/Monaco',
                'utc_offset' => '+02:00',
            ),
            239 => 
            array (
                'id' => 240,
                'name' => 'Europe/Oslo',
                'utc_offset' => '+02:00',
            ),
            240 => 
            array (
                'id' => 241,
                'name' => 'Europe/Paris',
                'utc_offset' => '+02:00',
            ),
            241 => 
            array (
                'id' => 242,
                'name' => 'Europe/Podgorica',
                'utc_offset' => '+02:00',
            ),
            242 => 
            array (
                'id' => 243,
                'name' => 'Europe/Prague',
                'utc_offset' => '+02:00',
            ),
            243 => 
            array (
                'id' => 244,
                'name' => 'Europe/Rome',
                'utc_offset' => '+02:00',
            ),
            244 => 
            array (
                'id' => 245,
                'name' => 'Europe/San_Marino',
                'utc_offset' => '+02:00',
            ),
            245 => 
            array (
                'id' => 246,
                'name' => 'Europe/Sarajevo',
                'utc_offset' => '+02:00',
            ),
            246 => 
            array (
                'id' => 247,
                'name' => 'Europe/Skopje',
                'utc_offset' => '+02:00',
            ),
            247 => 
            array (
                'id' => 248,
                'name' => 'Europe/Stockholm',
                'utc_offset' => '+02:00',
            ),
            248 => 
            array (
                'id' => 249,
                'name' => 'Europe/Tirane',
                'utc_offset' => '+02:00',
            ),
            249 => 
            array (
                'id' => 250,
                'name' => 'Europe/Vaduz',
                'utc_offset' => '+02:00',
            ),
            250 => 
            array (
                'id' => 251,
                'name' => 'Europe/Vatican',
                'utc_offset' => '+02:00',
            ),
            251 => 
            array (
                'id' => 252,
                'name' => 'Europe/Vienna',
                'utc_offset' => '+02:00',
            ),
            252 => 
            array (
                'id' => 253,
                'name' => 'Europe/Warsaw',
                'utc_offset' => '+02:00',
            ),
            253 => 
            array (
                'id' => 254,
                'name' => 'Europe/Zagreb',
                'utc_offset' => '+02:00',
            ),
            254 => 
            array (
                'id' => 255,
                'name' => 'Europe/Zurich',
                'utc_offset' => '+02:00',
            ),
            255 => 
            array (
                'id' => 256,
                'name' => 'Africa/Addis_Ababa',
                'utc_offset' => '+03:00',
            ),
            256 => 
            array (
                'id' => 257,
                'name' => 'Africa/Asmara',
                'utc_offset' => '+03:00',
            ),
            257 => 
            array (
                'id' => 258,
                'name' => 'Africa/Dar_es_Salaam',
                'utc_offset' => '+03:00',
            ),
            258 => 
            array (
                'id' => 259,
                'name' => 'Africa/Djibouti',
                'utc_offset' => '+03:00',
            ),
            259 => 
            array (
                'id' => 260,
                'name' => 'Africa/Juba',
                'utc_offset' => '+03:00',
            ),
            260 => 
            array (
                'id' => 261,
                'name' => 'Africa/Kampala',
                'utc_offset' => '+03:00',
            ),
            261 => 
            array (
                'id' => 262,
                'name' => 'Africa/Mogadishu',
                'utc_offset' => '+03:00',
            ),
            262 => 
            array (
                'id' => 263,
                'name' => 'Africa/Nairobi',
                'utc_offset' => '+03:00',
            ),
            263 => 
            array (
                'id' => 264,
                'name' => 'Antarctica/Syowa',
                'utc_offset' => '+03:00',
            ),
            264 => 
            array (
                'id' => 265,
                'name' => 'Asia/Aden',
                'utc_offset' => '+03:00',
            ),
            265 => 
            array (
                'id' => 266,
                'name' => 'Asia/Amman',
                'utc_offset' => '+03:00',
            ),
            266 => 
            array (
                'id' => 267,
                'name' => 'Asia/Baghdad',
                'utc_offset' => '+03:00',
            ),
            267 => 
            array (
                'id' => 268,
                'name' => 'Asia/Bahrain',
                'utc_offset' => '+03:00',
            ),
            268 => 
            array (
                'id' => 269,
                'name' => 'Asia/Beirut',
                'utc_offset' => '+03:00',
            ),
            269 => 
            array (
                'id' => 270,
                'name' => 'Asia/Damascus',
                'utc_offset' => '+03:00',
            ),
            270 => 
            array (
                'id' => 271,
                'name' => 'Asia/Famagusta',
                'utc_offset' => '+03:00',
            ),
            271 => 
            array (
                'id' => 272,
                'name' => 'Asia/Gaza',
                'utc_offset' => '+03:00',
            ),
            272 => 
            array (
                'id' => 273,
                'name' => 'Asia/Hebron',
                'utc_offset' => '+03:00',
            ),
            273 => 
            array (
                'id' => 274,
                'name' => 'Asia/Jerusalem',
                'utc_offset' => '+03:00',
            ),
            274 => 
            array (
                'id' => 275,
                'name' => 'Asia/Kuwait',
                'utc_offset' => '+03:00',
            ),
            275 => 
            array (
                'id' => 276,
                'name' => 'Asia/Nicosia',
                'utc_offset' => '+03:00',
            ),
            276 => 
            array (
                'id' => 277,
                'name' => 'Asia/Qatar',
                'utc_offset' => '+03:00',
            ),
            277 => 
            array (
                'id' => 278,
                'name' => 'Asia/Riyadh',
                'utc_offset' => '+03:00',
            ),
            278 => 
            array (
                'id' => 279,
                'name' => 'Europe/Athens',
                'utc_offset' => '+03:00',
            ),
            279 => 
            array (
                'id' => 280,
                'name' => 'Europe/Bucharest',
                'utc_offset' => '+03:00',
            ),
            280 => 
            array (
                'id' => 281,
                'name' => 'Europe/Chisinau',
                'utc_offset' => '+03:00',
            ),
            281 => 
            array (
                'id' => 282,
                'name' => 'Europe/Helsinki',
                'utc_offset' => '+03:00',
            ),
            282 => 
            array (
                'id' => 283,
                'name' => 'Europe/Istanbul',
                'utc_offset' => '+03:00',
            ),
            283 => 
            array (
                'id' => 284,
                'name' => 'Europe/Kiev',
                'utc_offset' => '+03:00',
            ),
            284 => 
            array (
                'id' => 285,
                'name' => 'Europe/Kirov',
                'utc_offset' => '+03:00',
            ),
            285 => 
            array (
                'id' => 286,
                'name' => 'Europe/Mariehamn',
                'utc_offset' => '+03:00',
            ),
            286 => 
            array (
                'id' => 287,
                'name' => 'Europe/Minsk',
                'utc_offset' => '+03:00',
            ),
            287 => 
            array (
                'id' => 288,
                'name' => 'Europe/Moscow',
                'utc_offset' => '+03:00',
            ),
            288 => 
            array (
                'id' => 289,
                'name' => 'Europe/Riga',
                'utc_offset' => '+03:00',
            ),
            289 => 
            array (
                'id' => 290,
                'name' => 'Europe/Simferopol',
                'utc_offset' => '+03:00',
            ),
            290 => 
            array (
                'id' => 291,
                'name' => 'Europe/Sofia',
                'utc_offset' => '+03:00',
            ),
            291 => 
            array (
                'id' => 292,
                'name' => 'Europe/Tallinn',
                'utc_offset' => '+03:00',
            ),
            292 => 
            array (
                'id' => 293,
                'name' => 'Europe/Uzhgorod',
                'utc_offset' => '+03:00',
            ),
            293 => 
            array (
                'id' => 294,
                'name' => 'Europe/Vilnius',
                'utc_offset' => '+03:00',
            ),
            294 => 
            array (
                'id' => 295,
                'name' => 'Europe/Zaporozhye',
                'utc_offset' => '+03:00',
            ),
            295 => 
            array (
                'id' => 296,
                'name' => 'Indian/Antananarivo',
                'utc_offset' => '+03:00',
            ),
            296 => 
            array (
                'id' => 297,
                'name' => 'Indian/Comoro',
                'utc_offset' => '+03:00',
            ),
            297 => 
            array (
                'id' => 298,
                'name' => 'Indian/Mayotte',
                'utc_offset' => '+03:00',
            ),
            298 => 
            array (
                'id' => 299,
                'name' => 'Asia/Baku',
                'utc_offset' => '+04:00',
            ),
            299 => 
            array (
                'id' => 300,
                'name' => 'Asia/Dubai',
                'utc_offset' => '+04:00',
            ),
            300 => 
            array (
                'id' => 301,
                'name' => 'Asia/Muscat',
                'utc_offset' => '+04:00',
            ),
            301 => 
            array (
                'id' => 302,
                'name' => 'Asia/Tbilisi',
                'utc_offset' => '+04:00',
            ),
            302 => 
            array (
                'id' => 303,
                'name' => 'Asia/Yerevan',
                'utc_offset' => '+04:00',
            ),
            303 => 
            array (
                'id' => 304,
                'name' => 'Europe/Astrakhan',
                'utc_offset' => '+04:00',
            ),
            304 => 
            array (
                'id' => 305,
                'name' => 'Europe/Samara',
                'utc_offset' => '+04:00',
            ),
            305 => 
            array (
                'id' => 306,
                'name' => 'Europe/Saratov',
                'utc_offset' => '+04:00',
            ),
            306 => 
            array (
                'id' => 307,
                'name' => 'Europe/Ulyanovsk',
                'utc_offset' => '+04:00',
            ),
            307 => 
            array (
                'id' => 308,
                'name' => 'Europe/Volgograd',
                'utc_offset' => '+04:00',
            ),
            308 => 
            array (
                'id' => 309,
                'name' => 'Indian/Mahe',
                'utc_offset' => '+04:00',
            ),
            309 => 
            array (
                'id' => 310,
                'name' => 'Indian/Mauritius',
                'utc_offset' => '+04:00',
            ),
            310 => 
            array (
                'id' => 311,
                'name' => 'Indian/Reunion',
                'utc_offset' => '+04:00',
            ),
            311 => 
            array (
                'id' => 312,
                'name' => 'Asia/Kabul',
                'utc_offset' => '+04:30',
            ),
            312 => 
            array (
                'id' => 313,
                'name' => 'Asia/Tehran',
                'utc_offset' => '+04:30',
            ),
            313 => 
            array (
                'id' => 314,
                'name' => 'Antarctica/Mawson',
                'utc_offset' => '+05:00',
            ),
            314 => 
            array (
                'id' => 315,
                'name' => 'Asia/Aqtau',
                'utc_offset' => '+05:00',
            ),
            315 => 
            array (
                'id' => 316,
                'name' => 'Asia/Aqtobe',
                'utc_offset' => '+05:00',
            ),
            316 => 
            array (
                'id' => 317,
                'name' => 'Asia/Ashgabat',
                'utc_offset' => '+05:00',
            ),
            317 => 
            array (
                'id' => 318,
                'name' => 'Asia/Atyrau',
                'utc_offset' => '+05:00',
            ),
            318 => 
            array (
                'id' => 319,
                'name' => 'Asia/Dushanbe',
                'utc_offset' => '+05:00',
            ),
            319 => 
            array (
                'id' => 320,
                'name' => 'Asia/Karachi',
                'utc_offset' => '+05:00',
            ),
            320 => 
            array (
                'id' => 321,
                'name' => 'Asia/Oral',
                'utc_offset' => '+05:00',
            ),
            321 => 
            array (
                'id' => 322,
                'name' => 'Asia/Qyzylorda',
                'utc_offset' => '+05:00',
            ),
            322 => 
            array (
                'id' => 323,
                'name' => 'Asia/Samarkand',
                'utc_offset' => '+05:00',
            ),
            323 => 
            array (
                'id' => 324,
                'name' => 'Asia/Tashkent',
                'utc_offset' => '+05:00',
            ),
            324 => 
            array (
                'id' => 325,
                'name' => 'Asia/Yekaterinburg',
                'utc_offset' => '+05:00',
            ),
            325 => 
            array (
                'id' => 326,
                'name' => 'Indian/Kerguelen',
                'utc_offset' => '+05:00',
            ),
            326 => 
            array (
                'id' => 327,
                'name' => 'Indian/Maldives',
                'utc_offset' => '+05:00',
            ),
            327 => 
            array (
                'id' => 328,
                'name' => 'Asia/Colombo',
                'utc_offset' => '+05:30',
            ),
            328 => 
            array (
                'id' => 329,
                'name' => 'Asia/Kolkata',
                'utc_offset' => '+05:30',
            ),
            329 => 
            array (
                'id' => 330,
                'name' => 'Asia/Kathmandu',
                'utc_offset' => '+05:45',
            ),
            330 => 
            array (
                'id' => 331,
                'name' => 'Antarctica/Vostok',
                'utc_offset' => '+06:00',
            ),
            331 => 
            array (
                'id' => 332,
                'name' => 'Asia/Almaty',
                'utc_offset' => '+06:00',
            ),
            332 => 
            array (
                'id' => 333,
                'name' => 'Asia/Bishkek',
                'utc_offset' => '+06:00',
            ),
            333 => 
            array (
                'id' => 334,
                'name' => 'Asia/Dhaka',
                'utc_offset' => '+06:00',
            ),
            334 => 
            array (
                'id' => 335,
                'name' => 'Asia/Omsk',
                'utc_offset' => '+06:00',
            ),
            335 => 
            array (
                'id' => 336,
                'name' => 'Asia/Qostanay',
                'utc_offset' => '+06:00',
            ),
            336 => 
            array (
                'id' => 337,
                'name' => 'Asia/Thimphu',
                'utc_offset' => '+06:00',
            ),
            337 => 
            array (
                'id' => 338,
                'name' => 'Asia/Urumqi',
                'utc_offset' => '+06:00',
            ),
            338 => 
            array (
                'id' => 339,
                'name' => 'Indian/Chagos',
                'utc_offset' => '+06:00',
            ),
            339 => 
            array (
                'id' => 340,
                'name' => 'Asia/Yangon',
                'utc_offset' => '+06:30',
            ),
            340 => 
            array (
                'id' => 341,
                'name' => 'Indian/Cocos',
                'utc_offset' => '+06:30',
            ),
            341 => 
            array (
                'id' => 342,
                'name' => 'Antarctica/Davis',
                'utc_offset' => '+07:00',
            ),
            342 => 
            array (
                'id' => 343,
                'name' => 'Asia/Bangkok',
                'utc_offset' => '+07:00',
            ),
            343 => 
            array (
                'id' => 344,
                'name' => 'Asia/Barnaul',
                'utc_offset' => '+07:00',
            ),
            344 => 
            array (
                'id' => 345,
                'name' => 'Asia/Ho_Chi_Minh',
                'utc_offset' => '+07:00',
            ),
            345 => 
            array (
                'id' => 346,
                'name' => 'Asia/Hovd',
                'utc_offset' => '+07:00',
            ),
            346 => 
            array (
                'id' => 347,
                'name' => 'Asia/Jakarta',
                'utc_offset' => '+07:00',
            ),
            347 => 
            array (
                'id' => 348,
                'name' => 'Asia/Krasnoyarsk',
                'utc_offset' => '+07:00',
            ),
            348 => 
            array (
                'id' => 349,
                'name' => 'Asia/Novokuznetsk',
                'utc_offset' => '+07:00',
            ),
            349 => 
            array (
                'id' => 350,
                'name' => 'Asia/Novosibirsk',
                'utc_offset' => '+07:00',
            ),
            350 => 
            array (
                'id' => 351,
                'name' => 'Asia/Phnom_Penh',
                'utc_offset' => '+07:00',
            ),
            351 => 
            array (
                'id' => 352,
                'name' => 'Asia/Pontianak',
                'utc_offset' => '+07:00',
            ),
            352 => 
            array (
                'id' => 353,
                'name' => 'Asia/Tomsk',
                'utc_offset' => '+07:00',
            ),
            353 => 
            array (
                'id' => 354,
                'name' => 'Asia/Vientiane',
                'utc_offset' => '+07:00',
            ),
            354 => 
            array (
                'id' => 355,
                'name' => 'Indian/Christmas',
                'utc_offset' => '+07:00',
            ),
            355 => 
            array (
                'id' => 356,
                'name' => 'Antarctica/Casey',
                'utc_offset' => '+08:00',
            ),
            356 => 
            array (
                'id' => 357,
                'name' => 'Asia/Brunei',
                'utc_offset' => '+08:00',
            ),
            357 => 
            array (
                'id' => 358,
                'name' => 'Asia/Choibalsan',
                'utc_offset' => '+08:00',
            ),
            358 => 
            array (
                'id' => 359,
                'name' => 'Asia/Hong_Kong',
                'utc_offset' => '+08:00',
            ),
            359 => 
            array (
                'id' => 360,
                'name' => 'Asia/Irkutsk',
                'utc_offset' => '+08:00',
            ),
            360 => 
            array (
                'id' => 361,
                'name' => 'Asia/Kuala_Lumpur',
                'utc_offset' => '+08:00',
            ),
            361 => 
            array (
                'id' => 362,
                'name' => 'Asia/Kuching',
                'utc_offset' => '+08:00',
            ),
            362 => 
            array (
                'id' => 363,
                'name' => 'Asia/Macau',
                'utc_offset' => '+08:00',
            ),
            363 => 
            array (
                'id' => 364,
                'name' => 'Asia/Makassar',
                'utc_offset' => '+08:00',
            ),
            364 => 
            array (
                'id' => 365,
                'name' => 'Asia/Manila',
                'utc_offset' => '+08:00',
            ),
            365 => 
            array (
                'id' => 366,
                'name' => 'Asia/Shanghai',
                'utc_offset' => '+08:00',
            ),
            366 => 
            array (
                'id' => 367,
                'name' => 'Asia/Singapore',
                'utc_offset' => '+08:00',
            ),
            367 => 
            array (
                'id' => 368,
                'name' => 'Asia/Taipei',
                'utc_offset' => '+08:00',
            ),
            368 => 
            array (
                'id' => 369,
                'name' => 'Asia/Ulaanbaatar',
                'utc_offset' => '+08:00',
            ),
            369 => 
            array (
                'id' => 370,
                'name' => 'Australia/Perth',
                'utc_offset' => '+08:00',
            ),
            370 => 
            array (
                'id' => 371,
                'name' => 'Australia/Eucla',
                'utc_offset' => '+08:45',
            ),
            371 => 
            array (
                'id' => 372,
                'name' => 'Asia/Chita',
                'utc_offset' => '+09:00',
            ),
            372 => 
            array (
                'id' => 373,
                'name' => 'Asia/Dili',
                'utc_offset' => '+09:00',
            ),
            373 => 
            array (
                'id' => 374,
                'name' => 'Asia/Jayapura',
                'utc_offset' => '+09:00',
            ),
            374 => 
            array (
                'id' => 375,
                'name' => 'Asia/Khandyga',
                'utc_offset' => '+09:00',
            ),
            375 => 
            array (
                'id' => 376,
                'name' => 'Asia/Pyongyang',
                'utc_offset' => '+09:00',
            ),
            376 => 
            array (
                'id' => 377,
                'name' => 'Asia/Seoul',
                'utc_offset' => '+09:00',
            ),
            377 => 
            array (
                'id' => 378,
                'name' => 'Asia/Tokyo',
                'utc_offset' => '+09:00',
            ),
            378 => 
            array (
                'id' => 379,
                'name' => 'Asia/Yakutsk',
                'utc_offset' => '+09:00',
            ),
            379 => 
            array (
                'id' => 380,
                'name' => 'Pacific/Palau',
                'utc_offset' => '+09:00',
            ),
            380 => 
            array (
                'id' => 381,
                'name' => 'Australia/Adelaide',
                'utc_offset' => '+09:30',
            ),
            381 => 
            array (
                'id' => 382,
                'name' => 'Australia/Broken_Hill',
                'utc_offset' => '+09:30',
            ),
            382 => 
            array (
                'id' => 383,
                'name' => 'Australia/Darwin',
                'utc_offset' => '+09:30',
            ),
            383 => 
            array (
                'id' => 384,
                'name' => 'Antarctica/DumontDUrville',
                'utc_offset' => '+10:00',
            ),
            384 => 
            array (
                'id' => 385,
                'name' => 'Asia/Ust-Nera',
                'utc_offset' => '+10:00',
            ),
            385 => 
            array (
                'id' => 386,
                'name' => 'Asia/Vladivostok',
                'utc_offset' => '+10:00',
            ),
            386 => 
            array (
                'id' => 387,
                'name' => 'Australia/Brisbane',
                'utc_offset' => '+10:00',
            ),
            387 => 
            array (
                'id' => 388,
                'name' => 'Australia/Currie',
                'utc_offset' => '+10:00',
            ),
            388 => 
            array (
                'id' => 389,
                'name' => 'Australia/Hobart',
                'utc_offset' => '+10:00',
            ),
            389 => 
            array (
                'id' => 390,
                'name' => 'Australia/Lindeman',
                'utc_offset' => '+10:00',
            ),
            390 => 
            array (
                'id' => 391,
                'name' => 'Australia/Melbourne',
                'utc_offset' => '+10:00',
            ),
            391 => 
            array (
                'id' => 392,
                'name' => 'Australia/Sydney',
                'utc_offset' => '+10:00',
            ),
            392 => 
            array (
                'id' => 393,
                'name' => 'Pacific/Chuuk',
                'utc_offset' => '+10:00',
            ),
            393 => 
            array (
                'id' => 394,
                'name' => 'Pacific/Guam',
                'utc_offset' => '+10:00',
            ),
            394 => 
            array (
                'id' => 395,
                'name' => 'Pacific/Port_Moresby',
                'utc_offset' => '+10:00',
            ),
            395 => 
            array (
                'id' => 396,
                'name' => 'Pacific/Saipan',
                'utc_offset' => '+10:00',
            ),
            396 => 
            array (
                'id' => 397,
                'name' => 'Australia/Lord_Howe',
                'utc_offset' => '+10:30',
            ),
            397 => 
            array (
                'id' => 398,
                'name' => 'Antarctica/Macquarie',
                'utc_offset' => '+11:00',
            ),
            398 => 
            array (
                'id' => 399,
                'name' => 'Asia/Magadan',
                'utc_offset' => '+11:00',
            ),
            399 => 
            array (
                'id' => 400,
                'name' => 'Asia/Sakhalin',
                'utc_offset' => '+11:00',
            ),
            400 => 
            array (
                'id' => 401,
                'name' => 'Asia/Srednekolymsk',
                'utc_offset' => '+11:00',
            ),
            401 => 
            array (
                'id' => 402,
                'name' => 'Pacific/Bougainville',
                'utc_offset' => '+11:00',
            ),
            402 => 
            array (
                'id' => 403,
                'name' => 'Pacific/Efate',
                'utc_offset' => '+11:00',
            ),
            403 => 
            array (
                'id' => 404,
                'name' => 'Pacific/Guadalcanal',
                'utc_offset' => '+11:00',
            ),
            404 => 
            array (
                'id' => 405,
                'name' => 'Pacific/Kosrae',
                'utc_offset' => '+11:00',
            ),
            405 => 
            array (
                'id' => 406,
                'name' => 'Pacific/Norfolk',
                'utc_offset' => '+11:00',
            ),
            406 => 
            array (
                'id' => 407,
                'name' => 'Pacific/Noumea',
                'utc_offset' => '+11:00',
            ),
            407 => 
            array (
                'id' => 408,
                'name' => 'Pacific/Pohnpei',
                'utc_offset' => '+11:00',
            ),
            408 => 
            array (
                'id' => 409,
                'name' => 'Antarctica/McMurdo',
                'utc_offset' => '+12:00',
            ),
            409 => 
            array (
                'id' => 410,
                'name' => 'Asia/Anadyr',
                'utc_offset' => '+12:00',
            ),
            410 => 
            array (
                'id' => 411,
                'name' => 'Asia/Kamchatka',
                'utc_offset' => '+12:00',
            ),
            411 => 
            array (
                'id' => 412,
                'name' => 'Pacific/Auckland',
                'utc_offset' => '+12:00',
            ),
            412 => 
            array (
                'id' => 413,
                'name' => 'Pacific/Fiji',
                'utc_offset' => '+12:00',
            ),
            413 => 
            array (
                'id' => 414,
                'name' => 'Pacific/Funafuti',
                'utc_offset' => '+12:00',
            ),
            414 => 
            array (
                'id' => 415,
                'name' => 'Pacific/Kwajalein',
                'utc_offset' => '+12:00',
            ),
            415 => 
            array (
                'id' => 416,
                'name' => 'Pacific/Majuro',
                'utc_offset' => '+12:00',
            ),
            416 => 
            array (
                'id' => 417,
                'name' => 'Pacific/Nauru',
                'utc_offset' => '+12:00',
            ),
            417 => 
            array (
                'id' => 418,
                'name' => 'Pacific/Tarawa',
                'utc_offset' => '+12:00',
            ),
            418 => 
            array (
                'id' => 419,
                'name' => 'Pacific/Wake',
                'utc_offset' => '+12:00',
            ),
            419 => 
            array (
                'id' => 420,
                'name' => 'Pacific/Wallis',
                'utc_offset' => '+12:00',
            ),
            420 => 
            array (
                'id' => 421,
                'name' => 'Pacific/Chatham',
                'utc_offset' => '+12:45',
            ),
            421 => 
            array (
                'id' => 422,
                'name' => 'Pacific/Apia',
                'utc_offset' => '+13:00',
            ),
            422 => 
            array (
                'id' => 423,
                'name' => 'Pacific/Enderbury',
                'utc_offset' => '+13:00',
            ),
            423 => 
            array (
                'id' => 424,
                'name' => 'Pacific/Fakaofo',
                'utc_offset' => '+13:00',
            ),
            424 => 
            array (
                'id' => 425,
                'name' => 'Pacific/Tongatapu',
                'utc_offset' => '+13:00',
            ),
            425 => 
            array (
                'id' => 426,
                'name' => 'Pacific/Kiritimati',
                'utc_offset' => '+14:00',
            ),
        ));
        
        
    }
}