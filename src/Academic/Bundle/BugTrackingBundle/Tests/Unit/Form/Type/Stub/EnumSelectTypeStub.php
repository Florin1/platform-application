<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type\Stub;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Oro\Component\Testing\Unit\Form\Type\Stub\EntityType;
use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;

class EnumSelectTypeStub extends EntityType
{
    const NAME = 'oro_enum_select';

    /**
     * @param array $choices
     */
    public function __construct(array $choices)
    {
        $choices = $this->getEnumChoices($choices);
        parent::__construct($choices, static::NAME);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'configs' => [],
            'enum_code' => null,
            'excluded_values' => [],
            'disabled' => null
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->name;
    }

    /**
     * @param AbstractEnumValue[] $choices
     * @return array
     */
    protected function getEnumChoices($choices)
    {
        $enumChoices = [];
        foreach ($choices as $choice) {
            $enumChoices[$choice->getId()] = $choice;
        }
        return $enumChoices;
    }
}
