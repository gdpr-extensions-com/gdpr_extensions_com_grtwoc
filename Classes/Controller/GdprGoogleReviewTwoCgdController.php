<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGrtwoc\Controller;


use GdprExtensionsCom\GdprExtensionsComGrtwoc\Utility\Helper;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "gdpr-extensions-com-google_reviewslider" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * gdprgoogle_reviewsliderController
 */
class GdprGoogleReviewTwoCgdController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * gdprManagerRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComGrtwoc\Domain\Repository\GdprManagerRepository
     */

    protected $gdprManagerRepository = null;

    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject = null;

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();

        // intialize the content object
    }

    /**
     * @param \GdprExtensionsCom\GdprExtensionsComGrtwoc\Domain\Repository\GdprManagerRepository $gdprManagerRepository
     */
    public function injectGdprManagerRepository(\GdprExtensionsCom\GdprExtensionsComGrtwoc\Domain\Repository\GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $showReviewsUrl = $this->uriBuilder->reset()
            ->uriFor('showReviews');
        $this->view->assign('showReviewsUrl', $showReviewsUrl);
        $this->view->assign('data', $this->contentObject->data);
        $this->view->assign('rootPid', $GLOBALS['TSFE']->site->getRootPageId());
        return $this->htmlResponse();
    }
    public function showReviewsAction()
    {
        $reviewsToFetch = GeneralUtility::_GP('reveiwsToFetch') ?: 4;
        $contentElementUid = $this->configurationManager->getContentObject()->data['uid']; // Example to get current content element UID

        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
        $cache = $cacheManager->getCache('GdprExtensionsComGrtwoc');

        // Adjusted cache identifier to be more specific and include content element UID
        $cacheIdentifier = 'reviewArray_' . $contentElementUid;
        $cacheTag = 'content_element_' . $contentElementUid; // Cache tag based on content element UID

        $reviewArray = $cache->get($cacheIdentifier);

        if (!$reviewArray) {
            $reviewArray = $this->fetchReviews();
            $cache->set($cacheIdentifier, $reviewArray, [$cacheTag], 3600);
        }

        $reviewsSlice = array_slice($reviewArray, 0, (int)$reviewsToFetch);
        if(count($reviewArray) > 0){
            $completed = (count($reviewsSlice) == count($reviewArray)) ? 1 : 0;
        }
        $result = ['fetchedReviews' => $reviewsSlice ,'completed'=>$completed ];
        die(json_encode($result));
        return $this->jsonResponse(json_encode($result));
    }


    public function fetchReviews()
    {
        $reviews = [];
        $maxResults = $this->contentObject->data['tx_gdprreviewsclient_slider_max_reviews_twocgd'];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $showAllReviews = $this->contentObject->data['gdpr_show_all_reviews_twocgd'];
        if ($showAllReviews == 1) {
            $maxResults = 2000;
        }

        // .................................................................

        $helper = GeneralUtility::makeInstance(Helper::class);
        $rootpid = $helper->getRootPage($this->contentObject->data['pid']);

        $selectedLocations = explode(",", $this->contentObject->data['gdpr_business_locations_twocgd']);

//        if (!empty($this->contentObject->data['gdpr_business_locations_twocgd'])) {
            $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
            $locationsreviewsQB = $connectionPool->getQueryBuilderForTable('gdpr_multilocations');
            $locationNamesList = [];

//            foreach ($selectedLocations as $uid) {
                $locationResult = $locationsreviewsQB->select('dashboard_api_key')
                    ->from('gdpr_multilocations')
                    ->where(
                        $locationsreviewsQB->expr()
                            ->eq('root_pid', $locationsreviewsQB->createNamedParameter($rootpid))
                    )
                    ->executeQuery();
                $locationName = $locationResult->fetchOne();
                $locationNamesList[] = $locationName;
//            }
            if ($locationNamesList) {
                $reviews = [];
                foreach ($locationNamesList as $location) {

                    $reviewsResult = $reviewsQB->select('*')
                        ->from('tx_gdprclientreviews_domain_model_reviews')
                        ->where(
                            $reviewsQB->expr()
                                ->eq('dashboard_api_key', $reviewsQB->createNamedParameter($location)),
                        )
                        ->executeQuery();

                    $reviewsData = $reviewsResult->fetchAllAssociative();

                    $reviews = array_merge($reviews, $reviewsData);
                }
            }
//        }
        usort($reviews, function ($a, $b) {
            return $b['date_sort'] - $a['date_sort'];
        });
        $currentCount = sizeof($reviews);
        if ($currentCount < $maxResults) {
            $maxResults = $currentCount;
        }
        $holdReviews = $reviews;
        $filteredReveiws = [];
        for ($i = 0; $i < $maxResults; $i++) {
            $filteredReveiws[$i] = $holdReviews[$i];
        }
        return $filteredReveiws;
    }
}
