<?php

namespace Spygar\Magento2\JobParameters;

use Akeneo\Tool\Component\Batch\Job\JobParameters\DefaultValuesProviderInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Akeneo\Pim\Enrichment\Component\Product\Validator\Constraints\FilterStructureLocale;
use Akeneo\Channel\Component\Repository\ChannelRepositoryInterface;
use Akeneo\Channel\Component\Repository\LocaleRepositoryInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;
use Akeneo\Tool\Component\Batch\Job\JobInterface;
use Spygar\Magento2\Repository\CredentialsRepository;
use Symfony\Component\Validator\Constraints;


class BaseJobParameter implements
    ConstraintCollectionProviderInterface,
    DefaultValuesProviderInterface
{
    /** @var array */
    protected $supportedJobNames;

    /** @var ChannelRepositoryInterface */
    protected $channelRepository;

    /** @var LocaleRepositoryInterface */
    protected $localeRepository;

    /** @var CredentialsRepository */
    protected $credentialsRepository;

    /** @var ConstraintCollectionProviderInterface */
    protected $simpleProvider;

    /**
     * @param ChannelRepositoryInterface     $channelRepository
     * @param LocaleRepositoryInterface      $localeRepository
     * @param ConstraintCollectionProviderInterface $simpleProvider
     * @param array                          $supportedJobNames
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        LocaleRepositoryInterface $localeRepository,
        CredentialsRepository $credentialsRepository,
        ConstraintCollectionProviderInterface $simpleProvider,
        array $supportedJobNames
    ) {
        $this->channelRepository = $channelRepository;
        $this->localeRepository  = $localeRepository;
        $this->simpleProvider    = $simpleProvider;
        $this->credentialsRepository = $credentialsRepository;
        $this->supportedJobNames = $supportedJobNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValues()
    {
        $channels           = $this->channelRepository->getChannelCodes();
        $defaultChannelCode = (0 !== count($channels)) ? $channels[0] : null;

        $localesCodes       = $this->localeRepository->getActivatedLocaleCodes();
        $defaultLocaleCode  = (0 !== count($localesCodes)) ? $localesCodes[0] : null;

        $credentials        = $this->credentialsRepository->findAll();
        $defaultCredential  = !empty($credentials) ? current($credentials)->getId() : null;

        $parameters = [
            "scope"      => $defaultChannelCode,
            "locale"     => $defaultLocaleCode,
            "credential" => $defaultCredential,
        ];

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection()
    {
        $constraintFields = [];
        
        
        // $baseConstraint = $this->simpleProvider->getConstraintCollection();
        // $constraintFields = $baseConstraint->fields;
        // $constraintFields['decimalSeparator'] = new NotBlank();
        // $constraintFields['dateFormat'] = new NotBlank();
        // $constraintFields['credential'] = new NotBlank();
       

        return new Collection([
                            'fields' => $constraintFields,
                            'allowExtraFields' => true,
                            'allowMissingFields' => true,
                        ]);
    }

    /**
     * {@inheritdoc}
    */
    public function supports(JobInterface $job)
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }
}
