<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
use Illuminate\Support\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/", name="currency")
     */
    public function index(CurrencyRepository $currencyRepository, PaginatorInterface $paginator, Request $request)
    {
        $sql = $currencyRepository->getQuery();
        $pagination = $paginator->paginate($sql, $request->get('page', 1));
        return $this->render('currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/currency/chart", name="currency_chart")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chart(Request $request, CurrencyRepository $currencyRepository)
    {
        $dropDownCharCode = $currencyRepository->getDropDownCharCode();
        $selectedCharCode = $request->get('char_code', array_key_first($dropDownCharCode));
        $begin = $request->get('begin', (new \DateTime())->format('Y-m-d'));
        $end = $request->get('end', (new \DateTime())->sub(new \DateInterval('P1Y'))->format('Y-m-d'));
        $currencies = new Collection(
            $currencyRepository->findAllByCharCode($selectedCharCode, $begin, $end)
        );
        $currencies = $currencies->map(function ($value) {
            return [$value['datetime'], (float)$value['value']];
        })->toArray();

        return $this->render('currency/chart.html.twig', [
            'currencies' => $currencies,
            'dropDownCharCode' => $dropDownCharCode,
            'selectedCharCode' => $selectedCharCode,
            'begin' => $begin,
            'end' => $end
        ]);
    }
}
