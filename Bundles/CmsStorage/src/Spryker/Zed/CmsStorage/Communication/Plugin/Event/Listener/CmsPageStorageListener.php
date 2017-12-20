<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

class CmsPageStorageListener extends AbstractCmsPageStorageListener implements EventBulkHandlerInterface
{

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

        if ($eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE) {
            $this->publish($cmsPageIds);
        } else if ($eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE) {
            $this->unpublish($cmsPageIds);
        }
    }

}