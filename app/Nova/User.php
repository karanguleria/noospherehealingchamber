<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne As HasOneRelation;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo as FieldsBelongsTo;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $user = Auth::user();
        if ($user && $user->type_id == 3) {
            return [
                ID::make()->sortable(),

                //  Gravatar::make()->maxWidth(50),

                /*Text::make('Name')
                    ->sortable()
                    ->rules('required', 'max:255'),*/ 
                Select::make('Type Id')
                    ->options([
                        1 => 'Client',
                        2 => 'Practitioner',
                        3 => 'Admin',
                    ])
                    ->displayUsingLabels()
                    ->rules('required')
                    ->sortable()
                    ->hideWhenCreating(false), // always show
                
                Text::make('First Name')
                    ->sortable()
                    ->rules('max:255'),
                Text::make('Last Name')
                    ->sortable()
                    ->rules('max:255'),

                Text::make('Email')
                    ->sortable()
                    ->rules('required', 'email', 'max:254')
                    ->creationRules('unique:users,email')
                    ->updateRules('unique:users,email,{{resourceId}}'),
                              
                Password::make('Password')
                    ->onlyOnForms()
                    ->dependsOn(['type_id'], function (Password $field, NovaRequest $request, FormData $formData) {
                        // Hide password field when Type Id is "Client" (value = 1)
                        if ($formData->type_id == 1) {
                            $field->hide();
                            $field->creationRules([]); // remove required
                        } else {
                            $field->creationRules(['required', Rules\Password::defaults()]);
                        }
                    })
                    ->updateRules('nullable', Rules\Password::defaults()),
                
                //HasOneRelation::make('Profile'),
                //HasMany::make('Results'),
                //HasMany::make('Answers'),
                //HasMany::make('Invitations'),
                //HasMany::make('Users'),
                HasMany::make('Client Session', 'userSession', \App\Nova\UserSession::class),
                //BelongsTo::make('Practitioner','practitioner' ,User::class),
                
                Text::make('Company Name')
                    ->onlyOnForms()
                    ->dependsOn(['type_id'], function ($field, $value, $formData) {
                        // Access 'type_id' directly from the formData, not from $value
                        if (isset($formData['type_id']) && $formData['type_id'] == 3) {
                            $field->hide();
                            $field->creationRules([]); // Remove required
                        } else {
                            $field->show();
                            $field->sortable();
                            $field->rules('max:255');
                        }
                    }),
                   
                    
                Date::make('Date of Birth', 'date_of_birth')
                    ->onlyOnForms()
                    ->dependsOn(['type_id'], function ($field, $value, $formData) {
                        // Access 'type_id' directly from the formData, not from $value
                        if (isset($formData['type_id']) && ($formData['type_id'] == 2 || $formData['type_id'] == 3)) {
                            $field->hide();
                            $field->creationRules([]); // Remove required
                        } else {
                            $field->show();
                            $field->rules('required', 'date', 'before:today');
                        }
                    })
                    ->hideFromIndex(),
                    
                
                Text::make('Wellness Goals')
                    ->onlyOnForms()
                    ->dependsOn(['type_id'], function ($field, $value, $formData) {
                        // Access 'type_id' directly from the formData, not from $value
                        if (isset($formData['type_id']) && $formData['type_id'] == 3) {
                            $field->hide();
                            $field->creationRules([]); // Remove required
                        } else {
                            $field->show();
                            $field->sortable();
                            $field->rules('max:255');
                        }
                    }),


                Select::make('Country')
                    ->options([
                        'AF' => 'Afghanistan',
                        'AX' => 'Åland Islands',
                        'AL' => 'Albania',
                        'DZ' => 'Algeria',
                        'AS' => 'American Samoa',
                        'AD' => 'Andorra',
                        'AO' => 'Angola',
                        'AI' => 'Anguilla',
                        'AQ' => 'Antarctica',
                        'AG' => 'Antigua and Barbuda',
                        'AR' => 'Argentina',
                        'AM' => 'Armenia',
                        'AW' => 'Aruba',
                        'AU' => 'Australia',
                        'AT' => 'Austria',
                        'AZ' => 'Azerbaijan',
                        'BS' => 'Bahamas',
                        'BH' => 'Bahrain',
                        'BD' => 'Bangladesh',
                        'BB' => 'Barbados',
                        'BY' => 'Belarus',
                        'BE' => 'Belgium',
                        'BZ' => 'Belize',
                        'BJ' => 'Benin',
                        'BM' => 'Bermuda',
                        'BT' => 'Bhutan',
                        'BO' => 'Bolivia',
                        'BQ' => 'Bonaire, Sint Eustatius and Saba',
                        'BA' => 'Bosnia and Herzegovina',
                        'BW' => 'Botswana',
                        'BV' => 'Bouvet Island',
                        'BR' => 'Brazil',
                        'IO' => 'British Indian Ocean Territory',
                        'BN' => 'Brunei Darussalam',
                        'BG' => 'Bulgaria',
                        'BF' => 'Burkina Faso',
                        'BI' => 'Burundi',
                        'KH' => 'Cambodia',
                        'CM' => 'Cameroon',
                        'CA' => 'Canada',
                        'CV' => 'Cape Verde',
                        'KY' => 'Cayman Islands',
                        'CF' => 'Central African Republic',
                        'TD' => 'Chad',
                        'CL' => 'Chile',
                        'CN' => 'China',
                        'CX' => 'Christmas Island',
                        'CC' => 'Cocos (Keeling) Islands',
                        'CO' => 'Colombia',
                        'KM' => 'Comoros',
                        'CG' => 'Congo',
                        'CD' => 'Congo, Democratic Republic of the',
                        'CK' => 'Cook Islands',
                        'CR' => 'Costa Rica',
                        'CI' => "Côte d'Ivoire",
                        'HR' => 'Croatia',
                        'CU' => 'Cuba',
                        'CW' => 'Curaçao',
                        'CY' => 'Cyprus',
                        'CZ' => 'Czech Republic',
                        'DK' => 'Denmark',
                        'DJ' => 'Djibouti',
                        'DM' => 'Dominica',
                        'DO' => 'Dominican Republic',
                        'EC' => 'Ecuador',
                        'EG' => 'Egypt',
                        'SV' => 'El Salvador',
                        'GQ' => 'Equatorial Guinea',
                        'ER' => 'Eritrea',
                        'EE' => 'Estonia',
                        'ET' => 'Ethiopia',
                        'FK' => 'Falkland Islands (Malvinas)',
                        'FO' => 'Faroe Islands',
                        'FJ' => 'Fiji',
                        'FI' => 'Finland',
                        'FR' => 'France',
                        'GF' => 'French Guiana',
                        'PF' => 'French Polynesia',
                        'TF' => 'French Southern Territories',
                        'GA' => 'Gabon',
                        'GM' => 'Gambia',
                        'GE' => 'Georgia',
                        'DE' => 'Germany',
                        'GH' => 'Ghana',
                        'GI' => 'Gibraltar',
                        'GR' => 'Greece',
                        'GL' => 'Greenland',
                        'GD' => 'Grenada',
                        'GP' => 'Guadeloupe',
                        'GU' => 'Guam',
                        'GT' => 'Guatemala',
                        'GG' => 'Guernsey',
                        'GN' => 'Guinea',
                        'GW' => 'Guinea-Bissau',
                        'GY' => 'Guyana',
                        'HT' => 'Haiti',
                        'HM' => 'Heard Island and McDonald Islands',
                        'VA' => 'Holy See (Vatican City State)',
                        'HN' => 'Honduras',
                        'HK' => 'Hong Kong',
                        'HU' => 'Hungary',
                        'IS' => 'Iceland',
                        'IN' => 'India',
                        'ID' => 'Indonesia',
                        'IR' => 'Iran, Islamic Republic of',
                        'IQ' => 'Iraq',
                        'IE' => 'Ireland',
                        'IM' => 'Isle of Man',
                        'IL' => 'Israel',
                        'IT' => 'Italy',
                        'JM' => 'Jamaica',
                        'JP' => 'Japan',
                        'JE' => 'Jersey',
                        'JO' => 'Jordan',
                        'KZ' => 'Kazakhstan',
                        'KE' => 'Kenya',
                        'KI' => 'Kiribati',
                        'KP' => "Korea, Democratic People's Republic of",
                        'KR' => 'Korea, Republic of',
                        'KW' => 'Kuwait',
                        'KG' => 'Kyrgyzstan',
                        'LA' => "Lao People's Democratic Republic",
                        'LV' => 'Latvia',
                        'LB' => 'Lebanon',
                        'LS' => 'Lesotho',
                        'LR' => 'Liberia',
                        'LY' => 'Libya',
                        'LI' => 'Liechtenstein',
                        'LT' => 'Lithuania',
                        'LU' => 'Luxembourg',
                        'MO' => 'Macao',
                        'MK' => 'North Macedonia',
                        'MG' => 'Madagascar',
                        'MW' => 'Malawi',
                        'MY' => 'Malaysia',
                        'MV' => 'Maldives',
                        'ML' => 'Mali',
                        'MT' => 'Malta',
                        'MH' => 'Marshall Islands',
                        'MQ' => 'Martinique',
                        'MR' => 'Mauritania',
                        'MU' => 'Mauritius',
                        'YT' => 'Mayotte',
                        'MX' => 'Mexico',
                        'FM' => 'Micronesia, Federated States of',
                        'MD' => 'Moldova, Republic of',
                        'MC' => 'Monaco',
                        'MN' => 'Mongolia',
                        'ME' => 'Montenegro',
                        'MS' => 'Montserrat',
                        'MA' => 'Morocco',
                        'MZ' => 'Mozambique',
                        'MM' => 'Myanmar',
                        'NA' => 'Namibia',
                        'NR' => 'Nauru',
                        'NP' => 'Nepal',
                        'NL' => 'Netherlands',
                        'NC' => 'New Caledonia',
                        'NZ' => 'New Zealand',
                        'NI' => 'Nicaragua',
                        'NE' => 'Niger',
                        'NG' => 'Nigeria',
                        'NU' => 'Niue',
                        'NF' => 'Norfolk Island',
                        'MP' => 'Northern Mariana Islands',
                        'NO' => 'Norway',
                        'OM' => 'Oman',
                        'PK' => 'Pakistan',
                        'PW' => 'Palau',
                        'PS' => 'Palestine, State of',
                        'PA' => 'Panama',
                        'PG' => 'Papua New Guinea',
                        'PY' => 'Paraguay',
                        'PE' => 'Peru',
                        'PH' => 'Philippines',
                        'PN' => 'Pitcairn',
                        'PL' => 'Poland',
                        'PT' => 'Portugal',
                        'PR' => 'Puerto Rico',
                        'QA' => 'Qatar',
                        'RE' => 'Réunion',
                        'RO' => 'Romania',
                        'RU' => 'Russian Federation',
                        'RW' => 'Rwanda',
                        'BL' => 'Saint Barthélemy',
                        'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
                        'KN' => 'Saint Kitts and Nevis',
                        'LC' => 'Saint Lucia',
                        'MF' => 'Saint Martin (French part)',
                        'PM' => 'Saint Pierre and Miquelon',
                        'VC' => 'Saint Vincent and the Grenadines',
                        'WS' => 'Samoa',
                        'SM' => 'San Marino',
                        'ST' => 'Sao Tome and Principe',
                        'SA' => 'Saudi Arabia',
                        'SN' => 'Senegal',
                        'RS' => 'Serbia',
                        'SC' => 'Seychelles',
                        'SL' => 'Sierra Leone',
                        'SG' => 'Singapore',
                        'SX' => 'Sint Maarten (Dutch part)',
                        'SK' => 'Slovakia',
                        'SI' => 'Slovenia',
                        'SB' => 'Solomon Islands',
                        'SO' => 'Somalia',
                        'ZA' => 'South Africa',
                        'GS' => 'South Georgia and the South Sandwich Islands',
                        'SS' => 'South Sudan',
                        'ES' => 'Spain',
                        'LK' => 'Sri Lanka',
                        'SD' => 'Sudan',
                        'SR' => 'Suriname',
                        'SJ' => 'Svalbard and Jan Mayen',
                        'SE' => 'Sweden',
                        'CH' => 'Switzerland',
                        'SY' => 'Syrian Arab Republic',
                        'TW' => 'Taiwan',
                        'TJ' => 'Tajikistan',
                        'TZ' => 'Tanzania, United Republic of',
                        'TH' => 'Thailand',
                        'TL' => 'Timor-Leste',
                        'TG' => 'Togo',
                        'TK' => 'Tokelau',
                        'TO' => 'Tonga',
                        'TT' => 'Trinidad and Tobago',
                        'TN' => 'Tunisia',
                        'TR' => 'Turkey',
                        'TM' => 'Turkmenistan',
                        'TC' => 'Turks and Caicos Islands',
                        'TV' => 'Tuvalu',
                        'UG' => 'Uganda',
                        'UA' => 'Ukraine',
                        'AE' => 'United Arab Emirates',
                        'GB' => 'United Kingdom',
                        'US' => 'United States',
                        'UM' => 'United States Minor Outlying Islands',
                        'UY' => 'Uruguay',
                        'UZ' => 'Uzbekistan',
                        'VU' => 'Vanuatu',
                        'VE' => 'Venezuela',
                        'VN' => 'Viet Nam',
                        'VG' => 'Virgin Islands, British',
                        'VI' => 'Virgin Islands, U.S.',
                        'WF' => 'Wallis and Futuna',
                        'EH' => 'Western Sahara',
                        'YE' => 'Yemen',
                        'ZM' => 'Zambia',
                        'ZW' => 'Zimbabwe',
                    ])
                    ->displayUsingLabels()
                    ->onlyOnForms()
                    ->dependsOn(['type_id'], function ($field, $value, $formData) {
                        if (isset($formData['type_id']) && $formData['type_id'] == 3) {
                            $field->hide();
                            $field->creationRules([]);
                            $field->updateRules([]);
                        } else {
                            $field->show();
                            $field->creationRules(['required']);
                            $field->updateRules(['required']);
                        }
                    }),

                Text::make('URL', function () {
                    return '<a href="' . config('app.url') . '/start-session/' . $this->id . '" 
                                class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 
                                inline-flex items-center font-bold shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center 
                                font-bold px-4 h-9 text-sm shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                text-white dark:text-gray-800 inline-flex items-center font-bold" 
                                target="_blank">
                                Start New Session
                            </a>';
                })->asHtml(),
            ];
        } else{

            return [
                ID::make()->sortable(),

                //  Gravatar::make()->maxWidth(50),
                
                // Select::make('Type Id')->options([
                //     1 => 'Client',
                // ])->displayUsingLabels()
                //     ->rules('required')
                //     ->sortable(),
                /*Text::make('Name')
                    ->sortable()
                    ->rules('required', 'max:255'),*/ 
                Text::make('First Name')
                    ->sortable()
                    ->rules('max:255'),
                Text::make('Last Name')
                    ->sortable()
                    ->rules('max:255'),

                Text::make('Email')
                    ->sortable()
                    ->rules('required', 'email', 'max:254')
                    ->creationRules('unique:users,email')
                    ->updateRules('unique:users,email,{{resourceId}}'),
                
              
                Date::make('Date of Birth', 'date_of_birth')
                    ->rules('required', 'date', 'before:today')
                    ->hideFromIndex(),

                //HasOneRelation::make('Profile'),
                //HasMany::make('Results'),
                //HasMany::make('Answers'),
                //HasMany::make('Invitations'),
                //HasMany::make('Users'),
                HasMany::make('Client Session', 'userSession', \App\Nova\UserSession::class),
                //BelongsTo::make('Practitioner','practitioner' ,User::class),
                BelongsTo::make('Practitioner', 'practitioner', User::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->hideFromDetail(),


                Select::make('Country')
                    ->options([
                    'AF' => 'Afghanistan',
                    'AX' => 'Åland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BQ' => 'Bonaire, Sint Eustatius and Saba',
                    'BA' => 'Bosnia and Herzegovina',
                    'BW' => 'Botswana',
                    'BV' => 'Bouvet Island',
                    'BR' => 'Brazil',
                    'IO' => 'British Indian Ocean Territory',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island',
                    'CC' => 'Cocos (Keeling) Islands',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo',
                    'CD' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'CI' => "Côte d'Ivoire",
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CW' => 'Curaçao',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands (Malvinas)',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'TF' => 'French Southern Territories',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GG' => 'Guernsey',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HM' => 'Heard Island and McDonald Islands',
                    'VA' => 'Holy See (Vatican City State)',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran, Islamic Republic of',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KP' => "Korea, Democratic People's Republic of",
                    'KR' => 'Korea, Republic of',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => "Lao People's Democratic Republic",
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'North Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marshall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia, Federated States of',
                    'MD' => 'Moldova, Republic of',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'ME' => 'Montenegro',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'MM' => 'Myanmar',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NU' => 'Niue',
                    'NF' => 'Norfolk Island',
                    'MP' => 'Northern Mariana Islands',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PS' => 'Palestine, State of',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'RE' => 'Réunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthélemy',
                    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin (French part)',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SX' => 'Sint Maarten (Dutch part)',
                    'SK' => 'Slovakia',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'SS' => 'South Sudan',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard and Jan Mayen',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania, United Republic of',
                    'TH' => 'Thailand',
                    'TL' => 'Timor-Leste',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                    'UM' => 'United States Minor Outlying Islands',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VE' => 'Venezuela',
                    'VN' => 'Viet Nam',
                    'VG' => 'Virgin Islands, British',
                    'VI' => 'Virgin Islands, U.S.',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe',
                ])
                    ->displayUsingLabels()
                    ->rules('required'),

                Text::make('Wellness Goals')
                    ->sortable()
                    ->rules('max:255'),


                Text::make('URL', function () {
                    return '<a href="' . config('app.url') . '/start-session/' . $this->id . '" 
                                class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 
                                inline-flex items-center font-bold shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center 
                                font-bold px-4 h-9 text-sm shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                text-white dark:text-gray-800 inline-flex items-center font-bold" 
                                target="_blank">
                                Start New Session
                            </a>';
                })->asHtml(),
            ];
        }


        return [
            ID::make()->sortable(),

          //  Gravatar::make()->maxWidth(50),

            /*Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),*/ 
            Text::make('First Name')
                ->sortable()
                ->rules('max:255'),
            Text::make('Last Name')
                ->sortable()
                ->rules('max:255'),
            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
                //HasOneRelation::make('Profile'),
                //HasMany::make('Results'),
                //HasMany::make('Answers'),
                //HasMany::make('Invitations'),
            //HasMany::make('Users'),
                HasMany::make('Client Session', 'userSession', \App\Nova\UserSession::class),
                //BelongsTo::make('Practitioner','practitioner' ,User::class),
                Select::make('Type Id')->options([
                    1 => 'User',
                    2 => 'Practitioner',
                    3 => 'Admin',
                ])->displayUsingLabels()
                ->rules('required')
                ->sortable(),

            Text::make('Company Name')
                ->sortable()
                ->rules('max:255'),
 
            Select::make('Country')
                ->options([
                    'AF' => 'Afghanistan',
                    'AX' => 'Åland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BQ' => 'Bonaire, Sint Eustatius and Saba',
                    'BA' => 'Bosnia and Herzegovina',
                    'BW' => 'Botswana',
                    'BV' => 'Bouvet Island',
                    'BR' => 'Brazil',
                    'IO' => 'British Indian Ocean Territory',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island',
                    'CC' => 'Cocos (Keeling) Islands',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo',
                    'CD' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'CI' => "Côte d'Ivoire",
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CW' => 'Curaçao',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands (Malvinas)',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'TF' => 'French Southern Territories',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GG' => 'Guernsey',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HM' => 'Heard Island and McDonald Islands',
                    'VA' => 'Holy See (Vatican City State)',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran, Islamic Republic of',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KP' => "Korea, Democratic People's Republic of",
                    'KR' => 'Korea, Republic of',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => "Lao People's Democratic Republic",
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'North Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marshall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia, Federated States of',
                    'MD' => 'Moldova, Republic of',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'ME' => 'Montenegro',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'MM' => 'Myanmar',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NU' => 'Niue',
                    'NF' => 'Norfolk Island',
                    'MP' => 'Northern Mariana Islands',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PS' => 'Palestine, State of',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'RE' => 'Réunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthélemy',
                    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin (French part)',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SX' => 'Sint Maarten (Dutch part)',
                    'SK' => 'Slovakia',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'SS' => 'South Sudan',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard and Jan Mayen',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania, United Republic of',
                    'TH' => 'Thailand',
                    'TL' => 'Timor-Leste',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                    'UM' => 'United States Minor Outlying Islands',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VE' => 'Venezuela',
                    'VN' => 'Viet Nam',
                    'VG' => 'Virgin Islands, British',
                    'VI' => 'Virgin Islands, U.S.',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe',
                ])
                ->displayUsingLabels()
                ->rules('required'),

                Text::make('URL', function () {
                    return '<a href="' . config('app.url') . '/start-session/' . $this->id . '" 
                                class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 
                                inline-flex items-center font-bold shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center 
                                font-bold px-4 h-9 text-sm shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring 
                                text-white dark:text-gray-800 inline-flex items-center font-bold" 
                                target="_blank">
                                Start New Session
                            </a>';
                })->asHtml(),   
        ];  
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
         if(auth()->user()->is_admin()){
            return $query;
        }else{
            return $query->where('practitioner_id', $request->user()->id);
        }
    }
    public static function singularLabel()
    {
        $user = Auth::user();

        if ($user && $user->type_id == 3) {
            return 'User Profile ';
        } else{
        return 'Client Profile '; 
        
        }
        // or 'User Info', 'Account Details', etc.
    }
    public function title()
    {
        return $this->name;
    }
    public static function label()
    {
        $user = Auth::user();

        if ($user && $user->type_id == 3) {
            return 'Users';
        } else {
            return 'Clients';
        }
         // Changes the "Users" label in the sidebar and index
    }

    public static function authorizedToCreate(Request $request)
    {
        return true; // Disables the "Create Session" button
    }


}
