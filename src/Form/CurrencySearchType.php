<?php

namespace App\Form;

use App\Repository\CurrencyUnitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencySearchType extends AbstractType
{
    /**
     * @var CurrencyUnitRepository
     */
    private CurrencyUnitRepository $currencyUnitRepository;

    public function __construct(CurrencyUnitRepository $currencyUnitRepository)
    {
        $this->currencyUnitRepository = $currencyUnitRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dropdown = $this->currencyUnitRepository->getDropDown();
        $builder
            ->add('date', TextType::class, [
                'attr' => ['class' => 'datepicker form-control']
            ])
            ->add('currency_unit_id', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'choices' => ['' => ''] + array_combine(array_column($dropdown, 'charCode'), array_column($dropdown, 'id')),
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->setMethod('get')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
