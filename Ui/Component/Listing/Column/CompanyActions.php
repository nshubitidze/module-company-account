<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class CompanyActions extends Column
{
    private UrlInterface $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['entity_id'])) {
                continue;
            }

            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl(
                        'shubo_company/company/edit',
                        ['entity_id' => $item['entity_id']]
                    ),
                    'label' => __('Edit'),
                ],
                'approve' => [
                    'href' => $this->urlBuilder->getUrl(
                        'shubo_company/company/approve',
                        ['entity_id' => $item['entity_id']]
                    ),
                    'label' => __('Approve'),
                    'confirm' => [
                        'title' => __('Approve Company'),
                        'message' => __('Are you sure you want to approve this company?'),
                    ],
                ],
                'reject' => [
                    'href' => $this->urlBuilder->getUrl(
                        'shubo_company/company/reject',
                        ['entity_id' => $item['entity_id']]
                    ),
                    'label' => __('Reject'),
                    'confirm' => [
                        'title' => __('Reject Company'),
                        'message' => __('Are you sure you want to reject this company?'),
                    ],
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl(
                        'shubo_company/company/delete',
                        ['entity_id' => $item['entity_id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete Company'),
                        'message' => __('Are you sure you want to delete this company? This cannot be undone.'),
                    ],
                ],
            ];
        }

        return $dataSource;
    }
}
