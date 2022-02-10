<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('countries')->delete();
        
        \DB::table('countries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'AF',
                'name' => 'Afghanistan',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'AL',
                'name' => 'Albania',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'DZ',
                'name' => 'Algeria',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'DS',
                'name' => 'American Samoa',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'AD',
                'name' => 'Andorra',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'AO',
                'name' => 'Angola',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'AI',
                'name' => 'Anguilla',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'AQ',
                'name' => 'Antarctica',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'AG',
                'name' => 'Antigua and Barbuda',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'AR',
                'name' => 'Argentina',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'AM',
                'name' => 'Armenia',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'AW',
                'name' => 'Aruba',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'AU',
                'name' => 'Australia',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'AT',
                'name' => 'Austria',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'AZ',
                'name' => 'Azerbaijan',
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'BS',
                'name' => 'Bahamas',
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'BH',
                'name' => 'Bahrain',
            ),
            17 => 
            array (
                'id' => 18,
                'code' => 'BD',
                'name' => 'Bangladesh',
            ),
            18 => 
            array (
                'id' => 19,
                'code' => 'BB',
                'name' => 'Barbados',
            ),
            19 => 
            array (
                'id' => 20,
                'code' => 'BY',
                'name' => 'Belarus',
            ),
            20 => 
            array (
                'id' => 21,
                'code' => 'BE',
                'name' => 'Belgium',
            ),
            21 => 
            array (
                'id' => 22,
                'code' => 'BZ',
                'name' => 'Belize',
            ),
            22 => 
            array (
                'id' => 23,
                'code' => 'BJ',
                'name' => 'Benin',
            ),
            23 => 
            array (
                'id' => 24,
                'code' => 'BM',
                'name' => 'Bermuda',
            ),
            24 => 
            array (
                'id' => 25,
                'code' => 'BT',
                'name' => 'Bhutan',
            ),
            25 => 
            array (
                'id' => 26,
                'code' => 'BO',
                'name' => 'Bolivia',
            ),
            26 => 
            array (
                'id' => 27,
                'code' => 'BA',
                'name' => 'Bosnia and Herzegovina',
            ),
            27 => 
            array (
                'id' => 28,
                'code' => 'BW',
                'name' => 'Botswana',
            ),
            28 => 
            array (
                'id' => 29,
                'code' => 'BV',
                'name' => 'Bouvet Island',
            ),
            29 => 
            array (
                'id' => 30,
                'code' => 'BR',
                'name' => 'Brazil',
            ),
            30 => 
            array (
                'id' => 31,
                'code' => 'IO',
                'name' => 'British Indian Ocean Territory',
            ),
            31 => 
            array (
                'id' => 32,
                'code' => 'BN',
                'name' => 'Brunei Darussalam',
            ),
            32 => 
            array (
                'id' => 33,
                'code' => 'BG',
                'name' => 'Bulgaria',
            ),
            33 => 
            array (
                'id' => 34,
                'code' => 'BF',
                'name' => 'Burkina Faso',
            ),
            34 => 
            array (
                'id' => 35,
                'code' => 'BI',
                'name' => 'Burundi',
            ),
            35 => 
            array (
                'id' => 36,
                'code' => 'KH',
                'name' => 'Cambodia',
            ),
            36 => 
            array (
                'id' => 37,
                'code' => 'CM',
                'name' => 'Cameroon',
            ),
            37 => 
            array (
                'id' => 38,
                'code' => 'CA',
                'name' => 'Canada',
            ),
            38 => 
            array (
                'id' => 39,
                'code' => 'CV',
                'name' => 'Cape Verde',
            ),
            39 => 
            array (
                'id' => 40,
                'code' => 'KY',
                'name' => 'Cayman Islands',
            ),
            40 => 
            array (
                'id' => 41,
                'code' => 'CF',
                'name' => 'Central African Republic',
            ),
            41 => 
            array (
                'id' => 42,
                'code' => 'TD',
                'name' => 'Chad',
            ),
            42 => 
            array (
                'id' => 43,
                'code' => 'CL',
                'name' => 'Chile',
            ),
            43 => 
            array (
                'id' => 44,
                'code' => 'CN',
                'name' => 'China',
            ),
            44 => 
            array (
                'id' => 45,
                'code' => 'CX',
                'name' => 'Christmas Island',
            ),
            45 => 
            array (
                'id' => 46,
                'code' => 'CC',
            'name' => 'Cocos (Keeling) Islands',
            ),
            46 => 
            array (
                'id' => 47,
                'code' => 'CO',
                'name' => 'Colombia',
            ),
            47 => 
            array (
                'id' => 48,
                'code' => 'KM',
                'name' => 'Comoros',
            ),
            48 => 
            array (
                'id' => 49,
                'code' => 'CD',
                'name' => 'Democratic Republic of the Congo',
            ),
            49 => 
            array (
                'id' => 50,
                'code' => 'CG',
                'name' => 'Republic of Congo',
            ),
            50 => 
            array (
                'id' => 51,
                'code' => 'CK',
                'name' => 'Cook Islands',
            ),
            51 => 
            array (
                'id' => 52,
                'code' => 'CR',
                'name' => 'Costa Rica',
            ),
            52 => 
            array (
                'id' => 53,
                'code' => 'HR',
            'name' => 'Croatia (Hrvatska)',
            ),
            53 => 
            array (
                'id' => 54,
                'code' => 'CU',
                'name' => 'Cuba',
            ),
            54 => 
            array (
                'id' => 55,
                'code' => 'CY',
                'name' => 'Cyprus',
            ),
            55 => 
            array (
                'id' => 56,
                'code' => 'CZ',
                'name' => 'Czech Republic',
            ),
            56 => 
            array (
                'id' => 57,
                'code' => 'DK',
                'name' => 'Denmark',
            ),
            57 => 
            array (
                'id' => 58,
                'code' => 'DJ',
                'name' => 'Djibouti',
            ),
            58 => 
            array (
                'id' => 59,
                'code' => 'DM',
                'name' => 'Dominica',
            ),
            59 => 
            array (
                'id' => 60,
                'code' => 'DO',
                'name' => 'Dominican Republic',
            ),
            60 => 
            array (
                'id' => 61,
                'code' => 'TP',
                'name' => 'East Timor',
            ),
            61 => 
            array (
                'id' => 62,
                'code' => 'EC',
                'name' => 'Ecuador',
            ),
            62 => 
            array (
                'id' => 63,
                'code' => 'EG',
                'name' => 'Egypt',
            ),
            63 => 
            array (
                'id' => 64,
                'code' => 'SV',
                'name' => 'El Salvador',
            ),
            64 => 
            array (
                'id' => 65,
                'code' => 'GQ',
                'name' => 'Equatorial Guinea',
            ),
            65 => 
            array (
                'id' => 66,
                'code' => 'ER',
                'name' => 'Eritrea',
            ),
            66 => 
            array (
                'id' => 67,
                'code' => 'EE',
                'name' => 'Estonia',
            ),
            67 => 
            array (
                'id' => 68,
                'code' => 'ET',
                'name' => 'Ethiopia',
            ),
            68 => 
            array (
                'id' => 69,
                'code' => 'FK',
            'name' => 'Falkland Islands (Malvinas)',
            ),
            69 => 
            array (
                'id' => 70,
                'code' => 'FO',
                'name' => 'Faroe Islands',
            ),
            70 => 
            array (
                'id' => 71,
                'code' => 'FJ',
                'name' => 'Fiji',
            ),
            71 => 
            array (
                'id' => 72,
                'code' => 'FI',
                'name' => 'Finland',
            ),
            72 => 
            array (
                'id' => 73,
                'code' => 'FR',
                'name' => 'France',
            ),
            73 => 
            array (
                'id' => 74,
                'code' => 'FX',
                'name' => 'France, Metropolitan',
            ),
            74 => 
            array (
                'id' => 75,
                'code' => 'GF',
                'name' => 'French Guiana',
            ),
            75 => 
            array (
                'id' => 76,
                'code' => 'PF',
                'name' => 'French Polynesia',
            ),
            76 => 
            array (
                'id' => 77,
                'code' => 'TF',
                'name' => 'French Southern Territories',
            ),
            77 => 
            array (
                'id' => 78,
                'code' => 'GA',
                'name' => 'Gabon',
            ),
            78 => 
            array (
                'id' => 79,
                'code' => 'GM',
                'name' => 'Gambia',
            ),
            79 => 
            array (
                'id' => 80,
                'code' => 'GE',
                'name' => 'Georgia',
            ),
            80 => 
            array (
                'id' => 81,
                'code' => 'DE',
                'name' => 'Germany',
            ),
            81 => 
            array (
                'id' => 82,
                'code' => 'GH',
                'name' => 'Ghana',
            ),
            82 => 
            array (
                'id' => 83,
                'code' => 'GI',
                'name' => 'Gibraltar',
            ),
            83 => 
            array (
                'id' => 84,
                'code' => 'GK',
                'name' => 'Guernsey',
            ),
            84 => 
            array (
                'id' => 85,
                'code' => 'GR',
                'name' => 'Greece',
            ),
            85 => 
            array (
                'id' => 86,
                'code' => 'GL',
                'name' => 'Greenland',
            ),
            86 => 
            array (
                'id' => 87,
                'code' => 'GD',
                'name' => 'Grenada',
            ),
            87 => 
            array (
                'id' => 88,
                'code' => 'GP',
                'name' => 'Guadeloupe',
            ),
            88 => 
            array (
                'id' => 89,
                'code' => 'GU',
                'name' => 'Guam',
            ),
            89 => 
            array (
                'id' => 90,
                'code' => 'GT',
                'name' => 'Guatemala',
            ),
            90 => 
            array (
                'id' => 91,
                'code' => 'GN',
                'name' => 'Guinea',
            ),
            91 => 
            array (
                'id' => 92,
                'code' => 'GW',
                'name' => 'Guinea-Bissau',
            ),
            92 => 
            array (
                'id' => 93,
                'code' => 'GY',
                'name' => 'Guyana',
            ),
            93 => 
            array (
                'id' => 94,
                'code' => 'HT',
                'name' => 'Haiti',
            ),
            94 => 
            array (
                'id' => 95,
                'code' => 'HM',
                'name' => 'Heard and Mc Donald Islands',
            ),
            95 => 
            array (
                'id' => 96,
                'code' => 'HN',
                'name' => 'Honduras',
            ),
            96 => 
            array (
                'id' => 97,
                'code' => 'HK',
                'name' => 'Hong Kong',
            ),
            97 => 
            array (
                'id' => 98,
                'code' => 'HU',
                'name' => 'Hungary',
            ),
            98 => 
            array (
                'id' => 99,
                'code' => 'IS',
                'name' => 'Iceland',
            ),
            99 => 
            array (
                'id' => 100,
                'code' => 'IN',
                'name' => 'India',
            ),
            100 => 
            array (
                'id' => 101,
                'code' => 'IM',
                'name' => 'Isle of Man',
            ),
            101 => 
            array (
                'id' => 102,
                'code' => 'ID',
                'name' => 'Indonesia',
            ),
            102 => 
            array (
                'id' => 103,
                'code' => 'IR',
            'name' => 'Iran (Islamic Republic of)',
            ),
            103 => 
            array (
                'id' => 104,
                'code' => 'IQ',
                'name' => 'Iraq',
            ),
            104 => 
            array (
                'id' => 105,
                'code' => 'IE',
                'name' => 'Ireland',
            ),
            105 => 
            array (
                'id' => 106,
                'code' => 'IL',
                'name' => 'Israel',
            ),
            106 => 
            array (
                'id' => 107,
                'code' => 'IT',
                'name' => 'Italy',
            ),
            107 => 
            array (
                'id' => 108,
                'code' => 'CI',
                'name' => 'Ivory Coast',
            ),
            108 => 
            array (
                'id' => 109,
                'code' => 'JE',
                'name' => 'Jersey',
            ),
            109 => 
            array (
                'id' => 110,
                'code' => 'JM',
                'name' => 'Jamaica',
            ),
            110 => 
            array (
                'id' => 111,
                'code' => 'JP',
                'name' => 'Japan',
            ),
            111 => 
            array (
                'id' => 112,
                'code' => 'JO',
                'name' => 'Jordan',
            ),
            112 => 
            array (
                'id' => 113,
                'code' => 'KZ',
                'name' => 'Kazakhstan',
            ),
            113 => 
            array (
                'id' => 114,
                'code' => 'KE',
                'name' => 'Kenya',
            ),
            114 => 
            array (
                'id' => 115,
                'code' => 'KI',
                'name' => 'Kiribati',
            ),
            115 => 
            array (
                'id' => 116,
                'code' => 'KP',
                'name' => 'Korea, Democratic People\'s Republic of',
            ),
            116 => 
            array (
                'id' => 117,
                'code' => 'KR',
                'name' => 'Korea, Republic of',
            ),
            117 => 
            array (
                'id' => 118,
                'code' => 'XK',
                'name' => 'Kosovo',
            ),
            118 => 
            array (
                'id' => 119,
                'code' => 'KW',
                'name' => 'Kuwait',
            ),
            119 => 
            array (
                'id' => 120,
                'code' => 'KG',
                'name' => 'Kyrgyzstan',
            ),
            120 => 
            array (
                'id' => 121,
                'code' => 'LA',
                'name' => 'Lao People\'s Democratic Republic',
            ),
            121 => 
            array (
                'id' => 122,
                'code' => 'LV',
                'name' => 'Latvia',
            ),
            122 => 
            array (
                'id' => 123,
                'code' => 'LB',
                'name' => 'Lebanon',
            ),
            123 => 
            array (
                'id' => 124,
                'code' => 'LS',
                'name' => 'Lesotho',
            ),
            124 => 
            array (
                'id' => 125,
                'code' => 'LR',
                'name' => 'Liberia',
            ),
            125 => 
            array (
                'id' => 126,
                'code' => 'LY',
                'name' => 'Libyan Arab Jamahiriya',
            ),
            126 => 
            array (
                'id' => 127,
                'code' => 'LI',
                'name' => 'Liechtenstein',
            ),
            127 => 
            array (
                'id' => 128,
                'code' => 'LT',
                'name' => 'Lithuania',
            ),
            128 => 
            array (
                'id' => 129,
                'code' => 'LU',
                'name' => 'Luxembourg',
            ),
            129 => 
            array (
                'id' => 130,
                'code' => 'MO',
                'name' => 'Macau',
            ),
            130 => 
            array (
                'id' => 131,
                'code' => 'MK',
                'name' => 'North Macedonia',
            ),
            131 => 
            array (
                'id' => 132,
                'code' => 'MG',
                'name' => 'Madagascar',
            ),
            132 => 
            array (
                'id' => 133,
                'code' => 'MW',
                'name' => 'Malawi',
            ),
            133 => 
            array (
                'id' => 134,
                'code' => 'MY',
                'name' => 'Malaysia',
            ),
            134 => 
            array (
                'id' => 135,
                'code' => 'MV',
                'name' => 'Maldives',
            ),
            135 => 
            array (
                'id' => 136,
                'code' => 'ML',
                'name' => 'Mali',
            ),
            136 => 
            array (
                'id' => 137,
                'code' => 'MT',
                'name' => 'Malta',
            ),
            137 => 
            array (
                'id' => 138,
                'code' => 'MH',
                'name' => 'Marshall Islands',
            ),
            138 => 
            array (
                'id' => 139,
                'code' => 'MQ',
                'name' => 'Martinique',
            ),
            139 => 
            array (
                'id' => 140,
                'code' => 'MR',
                'name' => 'Mauritania',
            ),
            140 => 
            array (
                'id' => 141,
                'code' => 'MU',
                'name' => 'Mauritius',
            ),
            141 => 
            array (
                'id' => 142,
                'code' => 'TY',
                'name' => 'Mayotte',
            ),
            142 => 
            array (
                'id' => 143,
                'code' => 'MX',
                'name' => 'Mexico',
            ),
            143 => 
            array (
                'id' => 144,
                'code' => 'FM',
                'name' => 'Micronesia, Federated States of',
            ),
            144 => 
            array (
                'id' => 145,
                'code' => 'MD',
                'name' => 'Moldova, Republic of',
            ),
            145 => 
            array (
                'id' => 146,
                'code' => 'MC',
                'name' => 'Monaco',
            ),
            146 => 
            array (
                'id' => 147,
                'code' => 'MN',
                'name' => 'Mongolia',
            ),
            147 => 
            array (
                'id' => 148,
                'code' => 'ME',
                'name' => 'Montenegro',
            ),
            148 => 
            array (
                'id' => 149,
                'code' => 'MS',
                'name' => 'Montserrat',
            ),
            149 => 
            array (
                'id' => 150,
                'code' => 'MA',
                'name' => 'Morocco',
            ),
            150 => 
            array (
                'id' => 151,
                'code' => 'MZ',
                'name' => 'Mozambique',
            ),
            151 => 
            array (
                'id' => 152,
                'code' => 'MM',
                'name' => 'Myanmar',
            ),
            152 => 
            array (
                'id' => 153,
                'code' => 'NA',
                'name' => 'Namibia',
            ),
            153 => 
            array (
                'id' => 154,
                'code' => 'NR',
                'name' => 'Nauru',
            ),
            154 => 
            array (
                'id' => 155,
                'code' => 'NP',
                'name' => 'Nepal',
            ),
            155 => 
            array (
                'id' => 156,
                'code' => 'NL',
                'name' => 'Netherlands',
            ),
            156 => 
            array (
                'id' => 157,
                'code' => 'AN',
                'name' => 'Netherlands Antilles',
            ),
            157 => 
            array (
                'id' => 158,
                'code' => 'NC',
                'name' => 'New Caledonia',
            ),
            158 => 
            array (
                'id' => 159,
                'code' => 'NZ',
                'name' => 'New Zealand',
            ),
            159 => 
            array (
                'id' => 160,
                'code' => 'NI',
                'name' => 'Nicaragua',
            ),
            160 => 
            array (
                'id' => 161,
                'code' => 'NE',
                'name' => 'Niger',
            ),
            161 => 
            array (
                'id' => 162,
                'code' => 'NG',
                'name' => 'Nigeria',
            ),
            162 => 
            array (
                'id' => 163,
                'code' => 'NU',
                'name' => 'Niue',
            ),
            163 => 
            array (
                'id' => 164,
                'code' => 'NF',
                'name' => 'Norfolk Island',
            ),
            164 => 
            array (
                'id' => 165,
                'code' => 'MP',
                'name' => 'Northern Mariana Islands',
            ),
            165 => 
            array (
                'id' => 166,
                'code' => 'NO',
                'name' => 'Norway',
            ),
            166 => 
            array (
                'id' => 167,
                'code' => 'OM',
                'name' => 'Oman',
            ),
            167 => 
            array (
                'id' => 168,
                'code' => 'PK',
                'name' => 'Pakistan',
            ),
            168 => 
            array (
                'id' => 169,
                'code' => 'PW',
                'name' => 'Palau',
            ),
            169 => 
            array (
                'id' => 170,
                'code' => 'PS',
                'name' => 'Palestine',
            ),
            170 => 
            array (
                'id' => 171,
                'code' => 'PA',
                'name' => 'Panama',
            ),
            171 => 
            array (
                'id' => 172,
                'code' => 'PG',
                'name' => 'Papua New Guinea',
            ),
            172 => 
            array (
                'id' => 173,
                'code' => 'PY',
                'name' => 'Paraguay',
            ),
            173 => 
            array (
                'id' => 174,
                'code' => 'PE',
                'name' => 'Peru',
            ),
            174 => 
            array (
                'id' => 175,
                'code' => 'PH',
                'name' => 'Philippines',
            ),
            175 => 
            array (
                'id' => 176,
                'code' => 'PN',
                'name' => 'Pitcairn',
            ),
            176 => 
            array (
                'id' => 177,
                'code' => 'PL',
                'name' => 'Poland',
            ),
            177 => 
            array (
                'id' => 178,
                'code' => 'PT',
                'name' => 'Portugal',
            ),
            178 => 
            array (
                'id' => 179,
                'code' => 'PR',
                'name' => 'Puerto Rico',
            ),
            179 => 
            array (
                'id' => 180,
                'code' => 'QA',
                'name' => 'Qatar',
            ),
            180 => 
            array (
                'id' => 181,
                'code' => 'RE',
                'name' => 'Reunion',
            ),
            181 => 
            array (
                'id' => 182,
                'code' => 'RO',
                'name' => 'Romania',
            ),
            182 => 
            array (
                'id' => 183,
                'code' => 'RU',
                'name' => 'Russian Federation',
            ),
            183 => 
            array (
                'id' => 184,
                'code' => 'RW',
                'name' => 'Rwanda',
            ),
            184 => 
            array (
                'id' => 185,
                'code' => 'KN',
                'name' => 'Saint Kitts and Nevis',
            ),
            185 => 
            array (
                'id' => 186,
                'code' => 'LC',
                'name' => 'Saint Lucia',
            ),
            186 => 
            array (
                'id' => 187,
                'code' => 'VC',
                'name' => 'Saint Vincent and the Grenadines',
            ),
            187 => 
            array (
                'id' => 188,
                'code' => 'WS',
                'name' => 'Samoa',
            ),
            188 => 
            array (
                'id' => 189,
                'code' => 'SM',
                'name' => 'San Marino',
            ),
            189 => 
            array (
                'id' => 190,
                'code' => 'ST',
                'name' => 'Sao Tome and Principe',
            ),
            190 => 
            array (
                'id' => 191,
                'code' => 'SA',
                'name' => 'Saudi Arabia',
            ),
            191 => 
            array (
                'id' => 192,
                'code' => 'SN',
                'name' => 'Senegal',
            ),
            192 => 
            array (
                'id' => 193,
                'code' => 'RS',
                'name' => 'Serbia',
            ),
            193 => 
            array (
                'id' => 194,
                'code' => 'SC',
                'name' => 'Seychelles',
            ),
            194 => 
            array (
                'id' => 195,
                'code' => 'SL',
                'name' => 'Sierra Leone',
            ),
            195 => 
            array (
                'id' => 196,
                'code' => 'SG',
                'name' => 'Singapore',
            ),
            196 => 
            array (
                'id' => 197,
                'code' => 'SK',
                'name' => 'Slovakia',
            ),
            197 => 
            array (
                'id' => 198,
                'code' => 'SI',
                'name' => 'Slovenia',
            ),
            198 => 
            array (
                'id' => 199,
                'code' => 'SB',
                'name' => 'Solomon Islands',
            ),
            199 => 
            array (
                'id' => 200,
                'code' => 'SO',
                'name' => 'Somalia',
            ),
            200 => 
            array (
                'id' => 201,
                'code' => 'ZA',
                'name' => 'South Africa',
            ),
            201 => 
            array (
                'id' => 202,
                'code' => 'GS',
                'name' => 'South Georgia South Sandwich Islands',
            ),
            202 => 
            array (
                'id' => 203,
                'code' => 'SS',
                'name' => 'South Sudan',
            ),
            203 => 
            array (
                'id' => 204,
                'code' => 'ES',
                'name' => 'Spain',
            ),
            204 => 
            array (
                'id' => 205,
                'code' => 'LK',
                'name' => 'Sri Lanka',
            ),
            205 => 
            array (
                'id' => 206,
                'code' => 'SH',
                'name' => 'St. Helena',
            ),
            206 => 
            array (
                'id' => 207,
                'code' => 'PM',
                'name' => 'St. Pierre and Miquelon',
            ),
            207 => 
            array (
                'id' => 208,
                'code' => 'SD',
                'name' => 'Sudan',
            ),
            208 => 
            array (
                'id' => 209,
                'code' => 'SR',
                'name' => 'Suriname',
            ),
            209 => 
            array (
                'id' => 210,
                'code' => 'SJ',
                'name' => 'Svalbard and Jan Mayen Islands',
            ),
            210 => 
            array (
                'id' => 211,
                'code' => 'SZ',
                'name' => 'Swaziland',
            ),
            211 => 
            array (
                'id' => 212,
                'code' => 'SE',
                'name' => 'Sweden',
            ),
            212 => 
            array (
                'id' => 213,
                'code' => 'CH',
                'name' => 'Switzerland',
            ),
            213 => 
            array (
                'id' => 214,
                'code' => 'SY',
                'name' => 'Syrian Arab Republic',
            ),
            214 => 
            array (
                'id' => 215,
                'code' => 'TW',
                'name' => 'Taiwan',
            ),
            215 => 
            array (
                'id' => 216,
                'code' => 'TJ',
                'name' => 'Tajikistan',
            ),
            216 => 
            array (
                'id' => 217,
                'code' => 'TZ',
                'name' => 'Tanzania, United Republic of',
            ),
            217 => 
            array (
                'id' => 218,
                'code' => 'TH',
                'name' => 'Thailand',
            ),
            218 => 
            array (
                'id' => 219,
                'code' => 'TG',
                'name' => 'Togo',
            ),
            219 => 
            array (
                'id' => 220,
                'code' => 'TK',
                'name' => 'Tokelau',
            ),
            220 => 
            array (
                'id' => 221,
                'code' => 'TO',
                'name' => 'Tonga',
            ),
            221 => 
            array (
                'id' => 222,
                'code' => 'TT',
                'name' => 'Trinidad and Tobago',
            ),
            222 => 
            array (
                'id' => 223,
                'code' => 'TN',
                'name' => 'Tunisia',
            ),
            223 => 
            array (
                'id' => 224,
                'code' => 'TR',
                'name' => 'Turkey',
            ),
            224 => 
            array (
                'id' => 225,
                'code' => 'TM',
                'name' => 'Turkmenistan',
            ),
            225 => 
            array (
                'id' => 226,
                'code' => 'TC',
                'name' => 'Turks and Caicos Islands',
            ),
            226 => 
            array (
                'id' => 227,
                'code' => 'TV',
                'name' => 'Tuvalu',
            ),
            227 => 
            array (
                'id' => 228,
                'code' => 'UG',
                'name' => 'Uganda',
            ),
            228 => 
            array (
                'id' => 229,
                'code' => 'UA',
                'name' => 'Ukraine',
            ),
            229 => 
            array (
                'id' => 230,
                'code' => 'AE',
                'name' => 'United Arab Emirates',
            ),
            230 => 
            array (
                'id' => 231,
                'code' => 'GB',
                'name' => 'United Kingdom',
            ),
            231 => 
            array (
                'id' => 232,
                'code' => 'US',
                'name' => 'United States',
            ),
            232 => 
            array (
                'id' => 233,
                'code' => 'UM',
                'name' => 'United States minor outlying islands',
            ),
            233 => 
            array (
                'id' => 234,
                'code' => 'UY',
                'name' => 'Uruguay',
            ),
            234 => 
            array (
                'id' => 235,
                'code' => 'UZ',
                'name' => 'Uzbekistan',
            ),
            235 => 
            array (
                'id' => 236,
                'code' => 'VU',
                'name' => 'Vanuatu',
            ),
            236 => 
            array (
                'id' => 237,
                'code' => 'VA',
                'name' => 'Vatican City State',
            ),
            237 => 
            array (
                'id' => 238,
                'code' => 'VE',
                'name' => 'Venezuela',
            ),
            238 => 
            array (
                'id' => 239,
                'code' => 'VN',
                'name' => 'Vietnam',
            ),
            239 => 
            array (
                'id' => 240,
                'code' => 'VG',
            'name' => 'Virgin Islands (British)',
            ),
            240 => 
            array (
                'id' => 241,
                'code' => 'VI',
            'name' => 'Virgin Islands (U.S.)',
            ),
            241 => 
            array (
                'id' => 242,
                'code' => 'WF',
                'name' => 'Wallis and Futuna Islands',
            ),
            242 => 
            array (
                'id' => 243,
                'code' => 'EH',
                'name' => 'Western Sahara',
            ),
            243 => 
            array (
                'id' => 244,
                'code' => 'YE',
                'name' => 'Yemen',
            ),
            244 => 
            array (
                'id' => 245,
                'code' => 'ZM',
                'name' => 'Zambia',
            ),
            245 => 
            array (
                'id' => 246,
                'code' => 'ZW',
                'name' => 'Zimbabwe',
            ),
        ));
        
        
    }
}