<?php

namespace App\Form;

use App\Repository\CurrencyUnitRepository;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChartFilterType extends AbstractType
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
            ->add('currency_unit_id', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'choices' => array_combine(array_column($dropdown, 'charCode'), array_column($dropdown, 'id')),
                'required' => true,
                'data' => current(array_column($dropdown, 'id')),
            ])
            ->add('begin', TextType::class, [
                'attr' => ['class' => 'datepicker form-control'],
                'required' => true,
                'data' => (new \DateTime())->sub(new \DateInterval('P1Y'))->format('Y-m-d'),
            ])
            ->add('end', TextType::class, [
                'attr' => ['class' => 'datepicker form-control'],
                'required' => true,
                'data' => (new \DateTime())->format('Y-m-d'),
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Показать'
            ])
            ->setMethod('get')
        ;
    }
}
