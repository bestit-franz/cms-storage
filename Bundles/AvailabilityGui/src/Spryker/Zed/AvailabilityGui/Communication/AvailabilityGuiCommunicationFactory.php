<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication;

use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider;
use Spryker\Zed\AvailabilityGui\Communication\Form\AvailabilityStockForm;
use Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider\AvailabilityStockFormDataProvider;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable;

/**
 * @method \Spryker\Zed\AvailabilityGui\AvailabilityGuiConfig getConfig()
 */
class AvailabilityGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param int $idLocale
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable
     */
    public function createAvailabilityAbstractTable($idLocale)
    {
        $queryProductAbstractAvailabilityGui = $this->getAvailabilityQueryContainer()
            ->queryAvailabilityAbstractWithStockByIdLocale($idLocale);

        return new AvailabilityAbstractTable($queryProductAbstractAvailabilityGui);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable
     */
    public function createAvailabilityTable($idProductAbstract, $idLocale)
    {
        $queryProductAbstractAvailability = $this->getAvailabilityQueryContainer()
            ->queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

        return new AvailabilityTable(
            $queryProductAbstractAvailability,
            $idProductAbstract,
            $this->getProductBundleQueryContainer()
        );
    }

    /**
     *
     * @param int $idLocale
     * @param int $idAbstractProductBundle
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable
     */
    public function createBundledProductAvailabilityTable($idLocale, $idAbstractProductBundle = null)
    {
        return new BundledProductAvailabilityTable(
            $this->getAvailabilityQueryContainer(),
            $this->getProductBundleQueryContainer(),
            $idLocale,
            $idAbstractProductBundle
        );
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityStockForm($idProduct, $sku)
    {
        $AvailabilityForm = new AvailabilityStockForm();

        $AvailabilityGuiStockFormDataProvider = $this->createAvailabilityGuiStockFormDataProvider();

        return $this->getFormFactory()->create(
            $AvailabilityForm,
            $AvailabilityGuiStockFormDataProvider->getData($idProduct, $sku),
            [
                AvailabilityStockFormDataProvider::DATA_CLASS => $AvailabilityGuiStockFormDataProvider->getOptions()[AvailabilityStockFormDataProvider::DATA_CLASS]
            ]
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider\AvailabilityStockFormDataProvider
     */
    public function createAvailabilityGuiStockFormDataProvider()
    {
        return new AvailabilityStockFormDataProvider($this->getStockFacade());
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleInterface
     */
    public function getLocalFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToAvailabilityInterface
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface
     */
    public function getAvailabilityQueryContainer()
    {
       return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::QUERY_CONTAINER_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface
     */
    public function getProductBundleQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_BUNDLE);
    }

}
