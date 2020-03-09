<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
use App\Search\CurrencySearch;
use Illuminate\Support\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/", name="currency")
     * @param CurrencyRepository $currencyRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CurrencySearch $currencySearch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        CurrencyRepository $currencyRepository,
        PaginatorInterface $paginator,
        Request $request,
        CurrencySearch $currencySearch
    )
    {

        $params = $request->query;
        $sql = $currencySearch->search($params->all());
        $pagination = $paginator->paginate($sql, $request->get('page', 1));
        return $this->render('currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
            'pagination' => $pagination,
            'dropDownCharCode' => $currencyRepository->getDropDownCharCode(),
            'params' => $params,
        ]);
    }

    /**
     * @Route("/currency/chart", name="currency_chart")
     * @param Request $request
     * @param CurrencyRepository $currencyRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function chart(Request $request, CurrencyRepository $currencyRepository)
    {
        $dropDownCharCode = $currencyRepository->getDropDownCharCode();
        $selectedCharCode = $request->get('char_code', array_key_first($dropDownCharCode));
        $begin = $request->get('begin', (new \DateTime())->sub(new \DateInterval('P1Y'))->format('Y-m-d'));
        $end = $request->get('end', (new \DateTime())->format('Y-m-d'));
        $currencies = new Collection(
            $currencyRepository->findAllByCharCode($selectedCharCode, $begin, $end)
        );
        $currencies = $currencies->map(function ($value) {
            return [$value['date'], (float)$value['value']];
        })->toArray();

        return $this->render('currency/chart.html.twig', [
            'currencies' => $currencies,
            'dropDownCharCode' => $dropDownCharCode,
            'selectedCharCode' => $selectedCharCode,
            'begin' => $begin,
            'end' => $end
        ]);
    }

    /**
     * @Route("/currency/download", name="currency_download")
     */
    public function download(CurrencySearch $currencySearch, Request $request)
    {
        $data  =$currencySearch->search($request->query->all())->execute()->fetchAll();

        $response = new JsonResponse($data);
        $response->headers->set('Content-Disposition', 'attachment; filename="currencies.json"');
        return $response;
    }
}
