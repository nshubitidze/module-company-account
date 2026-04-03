<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\ViewModel;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CompanyRegistration implements ArgumentInterface
{
    private CountryCollectionFactory $countryCollectionFactory;
    private RegionCollectionFactory $regionCollectionFactory;

    public function __construct(
        CountryCollectionFactory $countryCollectionFactory,
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
    }

    public function getCountries(): array
    {
        $collection = $this->countryCollectionFactory->create();
        $collection->loadByStore();
        $countries = [];

        foreach ($collection as $country) {
            $countries[] = [
                'value' => $country->getCountryId(),
                'label' => $country->getName(),
            ];
        }

        return $countries;
    }

    public function getRegionsJson(): string
    {
        $collection = $this->regionCollectionFactory->create();
        $collection->addAllowedCountriesFilter();
        $regions = [];

        foreach ($collection as $region) {
            $regions[$region->getCountryId()][] = [
                'value' => $region->getRegionId(),
                'label' => $region->getName(),
            ];
        }

        return json_encode($regions);
    }

    public function getPostUrl(): string
    {
        return 'company/account/registerPost';
    }
}
