<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\QueryContainerMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Bundle
 * @group MethodBuilder
 * @group QueryContainerMethodBuilderTest
 */
class QueryContainerMethodBuilderTest extends Test
{

    const BASE_DIRECTORY = '/foo/bar/baz/*/src/';
    const BUNDLE_DIRECTORY = '/foo/bar/baz/FooBundle/src/Spryker/FooApplication/';

    /**
     * @return void
     */
    public function testMethodNameIsQueryConntainer()
    {
        $methodBuilderMock = $this->getQueryContainerMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->willReturn(new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Persistence/FooBundleQueryContainerInterface.php', null, null));

        $bundleMethodTransfer = $methodBuilderMock->getMethod($this->getBundleTransfer());

        $this->assertSame('queryContainer', $bundleMethodTransfer->getName());
    }

    /**
     * @return void
     */
    public function testFileLookupIsPerformedInPersistenceLayer()
    {
        $methodBuilderMock = $this->getQueryContainerMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->with($this->anything(), $this->equalTo(static::BUNDLE_DIRECTORY . 'FooBundle/Persistence/'));

        $methodBuilderMock->getMethod($this->getBundleTransfer());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ClientMethodBuilder
     */
    protected function getQueryContainerMethodBuilderMock()
    {
        $methodBuilderMock = $this
            ->getMockBuilder(QueryContainerMethodBuilder::class)
            ->setConstructorArgs([$this->getNamespaceExtractorMock()])
            ->setMethods(['findFileByName', 'isSearchDirectoryAccessible'])
            ->getMock();

        $methodBuilderMock
            ->expects($this->any())
            ->method('isSearchDirectoryAccessible')
            ->willReturn(true);

        return $methodBuilderMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
     */
    protected function getNamespaceExtractorMock()
    {
        return $this
            ->getMockBuilder(NamespaceExtractor::class)
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    protected function getBundleTransfer()
    {
        $bundleTransfer = new IdeAutoCompletionBundleTransfer();
        $bundleTransfer->setName('FooBundle');
        $bundleTransfer->setNamespaceName('Generated\FooApplication\Ide');
        $bundleTransfer->setBaseDirectory(static::BASE_DIRECTORY);
        $bundleTransfer->setDirectory(static::BUNDLE_DIRECTORY);
        $bundleTransfer->setMethodName('fooBundle');

        return $bundleTransfer;
    }

}
