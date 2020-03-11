<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class DownloadRbcFormType
 * @package App\Form
 */
class DownloadRbcFormType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $route;

    /**
     * DownloadRbcFormType constructor.
     * @param UrlGeneratorInterface $route
     */
    public function __construct(UrlGeneratorInterface $route)
    {
        $this->route = $route;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('download', SubmitType::class, [
                'label' => 'Загрузить rbc',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('date', DateType::class, [
                'data' => new \DateTime(),
                'label' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker form-control'],
            ])
            ->setAction($this->route->generate('currency_load'))
        ;
    }
}
