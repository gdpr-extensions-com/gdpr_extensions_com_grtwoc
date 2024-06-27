<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComGrtwoc',
        'gdprgoogletwocgrid',
        [
            \GdprExtensionsCom\GdprExtensionsComGrtwoc\Controller\GdprGoogleReviewTwoCgdController::class => 'index , showReviews'
        ],
        // non-cacheable actions
        [
            \GdprExtensionsCom\GdprExtensionsComGrtwoc\Controller\GdprGoogleReviewTwoCgdController::class => 'showReviews',
            \GdprExtensionsCom\GdprExtensionsComGrtwoc\Controller\GdprManagerController::class => 'create, update, delete'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // register plugin for cookie widget
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComGrtwoc',
        'gdprcookiewidget',
        [
            \GdprExtensionsCom\GdprExtensionsComGrtwoc\Controller\GdprCookieWidgetController::class => 'index'
        ],
        // non-cacheable actions
        [],
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    gdprcookiewidget {
                        iconIdentifier = gdpr_extensions_com_grtwoc-plugin-gdprcookiewidget
                        title = cookie
                        description = LLL:EXT:gdpr_extensions_com_grtwoc/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grtwoc_gdprcookiewidget.description
                        tt_content_defValues {
                            CType = list
                            list_type = gdprextensionscomgrtwoc_gdprcookiewidget
                        }
                    }
                }
                show = *
            }
       }'
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod.wizards.newContentElement.wizardItems {
               gdpr.header = LLL:EXT:gdpr_extensions_com_grtwoc/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grtwoc_ggdprgoogletwocgrid.tab
        }'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.gdpr {
                elements {
                    gdprgoogletwocgrid {
                        iconIdentifier = gdpr_extensions_com_grtwoc-plugin-gdprgoogletwocgrid-gdprmanager
                        title = LLL:EXT:gdpr_extensions_com_grtwoc/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grtwoc_ggdprgoogletwocgrid.name
                        tt_content_defValues {
                            CType = gdprextensionscomgrtwoc_gdprgoogletwocgrid
                        }
                    }
                }
                show = *
            }
       }'
    );
    // $registeredTasks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'];
    // $alreadyRegistered = 0;
    // foreach($registeredTasks as $registeredTask){
       
    //     if(isset($registeredTask['extension']) && (strpos($registeredTask['extension'], 'gdpr_extensions_com_gr')!== false || strpos($registeredTask['extension'], 'GdprExtensionsComGr') !== false)){
    //         $alreadyRegistered +=1;
    //     }
      
    // }
 
    // if(!$alreadyRegistered){
    // }
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComGrtwoc\Commands\SyncReviewsTask::class] = [
        'extension' => 'gdpr_extensions_com_grtwoc',
        'title' => 'LLL:EXT:gdpr_extensions_com_grtwoc/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grtwoc_schedular_title',
        'description' => 'LLL:EXT:gdpr_extensions_com_grtwoc/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_grtwoc_schedular_description',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComGrtwoc\Commands\SyncReviewsTask::class,
    ];
})();
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \GdprExtensionsCom\GdprExtensionsComGrtwoc\Hooks\DataHandlerHook::class;
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComGrtwoc'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComGrtwoc'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'groups' => ['all', 'GdprExtensionsComGrtwoc'],
        'options' => [
            'defaultLifetime' => 3600, // Cache lifetime in seconds
        ],
    ];
}
