<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Ui\Component\Form\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Shubo\CompanyAccount\Model\ResourceModel\Company\CollectionFactory;

class CompanyDataProvider extends AbstractDataProvider
{
    private RequestInterface $request;
    private ?array $loadedData = null;

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
    }

    public function getData(): array
    {
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $items = $this->collection->getItems();

        foreach ($items as $company) {
            $this->loadedData[$company->getEntityId()] = $company->getData();
        }

        return $this->loadedData;
    }
}
