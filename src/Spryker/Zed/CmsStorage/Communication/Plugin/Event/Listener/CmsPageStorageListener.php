<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @deprecated Use `\Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStoragePublishListener` and `\Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageUnpublishListener` instead.
 *
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 */
class CmsPageStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if ($eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE ||
            $eventName === CmsEvents::CMS_VERSION_PUBLISH
        ) {
            $this->getFacade()->publish($cmsPageIds);
        } elseif ($eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE ||
            $eventName === CmsEvents::CMS_VERSION_UNPUBLISH
        ) {
            $this->getFacade()->unpublish($cmsPageIds);
        }
    }
}
