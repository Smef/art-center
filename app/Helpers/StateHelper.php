<?php

namespace App\Helpers;

class StateHelper
{
    public const STATES = [
        'AA' => 'Armed Forces Americas',
        'AE' => 'Armed Forces Europe / Canada / Middle East / Africa',
        'AK' => 'Alaska',
        'AL' => 'Alabama',
        'AP' => 'Armed Forces Pacific',
        'AR' => 'Arkansas',
        'AS' => 'American Samoa',
        'AZ' => 'Arizona',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DC' => 'District of Columbia',
        'DE' => 'Delaware',
        'FL' => 'Florida',
        'FM' => 'Federated States of Micronesia',
        'GA' => 'Georgia',
        'GU' => 'Guam GU',
        'HI' => 'Hawaii',
        'IA' => 'Iowa',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'MA' => 'Massachusetts',
        'MD' => 'Maryland',
        'ME' => 'Maine',
        'MH' => 'Marshall Islands',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MO' => 'Missouri',
        'MP' => 'Northern Mariana Islands',
        'MS' => 'Mississippi',
        'MT' => 'Montana',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'NE' => 'Nebraska',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NV' => 'Nevada',
        'NY' => 'New York',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'PR' => 'Puerto Rico',
        'PW' => 'Palau',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VA' => 'Virginia',
        'VI' => 'Virgin Islands',
        'VT' => 'Vermont',
        'WA' => 'Washington',
        'WI' => 'Wisconsin',
        'WV' => 'West Virginia',
        'WY' => 'Wyoming',
    ];

    public static function getStateAbbreviations(): array
    {
        return array_keys(self::STATES);
    }

    public static function getStateNames(): array
    {
        return array_values(self::STATES);
    }

    public static function convertStateName($lookup, $format = null): string
    {
        $states = self::STATES;

        $lookup = strtoupper($lookup);

        if (! isset($states[$lookup])) {
            $states = array_change_key_case(array_flip($states), CASE_UPPER);

            if (! isset($states[$lookup])) {
                return '';
            }
        }

        if ($format == 'lowercase') {
            return strtolower($states[$lookup]);
        } elseif ($format == 'uppercase') {
            return strtoupper($states[$lookup]);
        }

        return $states[$lookup];
    }
}
