<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Block\Adminhtml\Company\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    private RequestInterface $request;
    private UrlInterface $urlBuilder;

    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    public function getButtonData(): array
    {
        $companyId = (int) $this->request->getParam('entity_id');

        if (!$companyId) {
            return [];
        }

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => sprintf(
                "deleteConfirm('%s', '%s', {data: {}})",
                __('Are you sure you want to delete this company?'),
                $this->urlBuilder->getUrl('*/*/delete', ['entity_id' => $companyId])
            ),
            'sort_order' => 20,
        ];
    }
}
