<?php
namespace Spygar\Magento2\Connector\Export\Reader;

use Akeneo\Pim\Enrichment\Component\Category\Connector\Reader\Database\CategoryReader as BaseCategoryReader;;


class CategoryReader extends BaseCategoryReader 
{
    /** @var $categoryRepository */

    protected $categoryRepository;
    protected $channelRepository;

    /**
     * @param $categoryRepository
     * @param $channelRepository
     */

    public function __construct(
        $categoryRepository,
        $channelRepository)
    {
        $this->categoryRepository   = $categoryRepository;
        $this->channelRepository    = $channelRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getResults()
    {
        $categories = $this->categoryRepository->getOrderedAndSortedByTreeCategories();

        return new \ArrayIterator($categories);
    }
}