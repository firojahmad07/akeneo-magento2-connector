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
        $parameters  = $this->stepExecution->getJobParameters();
        $channelCode = !empty($parameters->has('scope')) ? $parameters->get('scope') : "";
        $channel        = $channelCode ? $this->channelRepository->findOneByIdentifier($channelCode) : null;
        $rootCategory   = $channel && $channel->getCategory() ? $channel->getCategory() : null;
        $rootCategoryId = $rootCategory ? $rootCategory->getId() : null;

        if ($rootCategoryId) {
            $categories = $this->categoryRepository->findBy(
                [ 'root' => $rootCategoryId ],
                [ 'root' => 'ASC', 'left' => 'ASC' ]
            );
        } else {
            $categories = $this->categoryRepository->getOrderedAndSortedByTreeCategories();
        }

        foreach ($categories as $key => $category) {
            if ($rootCategoryId == $category->getId()) {
                unset($categories[$key]);
                break;
            }
        }

        return new \ArrayIterator($categories);
    }
}