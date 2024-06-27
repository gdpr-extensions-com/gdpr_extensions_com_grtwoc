<?php
defined('TYPO3') || die();

$frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'GdprExtensionsComGrtwoc',
    'gdprgoogletwocgrid',
    'Google Reviews 2-Col Grid'
);


$fields = [
    'gdpr_button_shape_twocgd' => [
        'onChange' => 'reload',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Round', '1'],
                ['Square', '2'],
            ],
        ],
    ],
    'gdpr_business_locations_twocgd' => [
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'itemsProcFunc' => 'GdprExtensionsCom\GdprExtensionsComGrtwoc\Utility\ProcessSliderItems->getLocationsforRoodPid',
        ],
    ],
    'gdpr_background_color_twocgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_color_of_text_twocgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_button_color_twocgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'gdpr_button_text_color_twocgd' => [
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
        ],
    ],
    'tx_gdprreviewsclient_slider_max_reviews_twocgd' => [
        'displayCond' => 'FIELD:gdpr_show_all_reviews_twocgd:=:0',
        'config' => [
            'type' => 'input',
            'size' => 10,
            'eval' => 'trim,int',
            'range' => [
                'lower' => 1,
                'upper' => 100,
            ],
            'default' => 4,
            'slider' => [
                'step' => 1,
                'width' => 200,
            ],
        ],
    ],

    'gdpr_show_all_reviews_twocgd' => [
        'onChange' => 'reload',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                ],
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

$GLOBALS['TCA']['tt_content']['types']['gdprextensionscomgrtwoc_gdprgoogletwocgrid'] = [
    'showitem' => '
                --palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
                 gdpr_color_of_text_twocgd; Text Color,
                 gdpr_button_color_twocgd ; Button Color,
                 gdpr_button_text_color_twocgd ; Button Text Color,
                 gdpr_background_color_twocgd; Background Color,
                 tx_gdprreviewsclient_slider_max_reviews_twocgd; Max. number of reviews,
                 gdpr_button_shape_twocgd ; Button Shape,
                 gdpr_show_all_reviews_twocgd; Show All Reviews,

                --div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.frames;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
                --div--;' . $frontendLanguageFilePrefix . 'tabs.access,
                hidden;' . $frontendLanguageFilePrefix . 'field.default.hidden,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        ',
];
