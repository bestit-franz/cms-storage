<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Availability\Zed\Tester;

use Availability\ZedAcceptanceTester;

class AvailabilityTester extends ZedAcceptanceTester
{
    /**
     * @return void
     */
    public function assertTableWithDataExists($numberOfItemsExpected)
    {
        $showingEntries = $this->grabTextFrom('//*[@class="dataTables_info"]');
        preg_match('/^Showing\s{1}\d+\s{1}to\s{1}(\d+)/', $showingEntries, $matches);
        $this->assertEquals($numberOfItemsExpected, $matches[1]);

        $td = $this->grabTextFrom('//*[@class="dataTables_scrollBody"]/table/tbody');
        $itemListItems = count(explode("\n", $td));

        $this->assertEquals($numberOfItemsExpected, $itemListItems);
    }
}
