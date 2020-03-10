<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
use App\Repository\CurrencyUnitRepository;
use App\Search\CurrencySearch;
use App\Service\CurrencyService;
use Illuminate\Support\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/", name="currency")
     * @param CurrencyUnitRepository $currencyUnitRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CurrencySearch $currencySearch
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(
        CurrencyUnitRepository $currencyUnitRepository,
        PaginatorInterface $paginator,
        Request $request,
        CurrencySearch $currencySearch
    )
    {

        $params = $request->query;
        $sql = $currencySearch->search($params->all());
        $pagination = $paginator->paginate($sql, $request->get('page', 1));
        $now = (new \DateTime())->format('Y-m-d');

        return $this->render('currency/index.html.twig', [
            'pagination' => $pagination,
            'dropDownCharCode' => $currencyUnitRepository->getDropDown(),
            'params' => $params,
            'now' => $now
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
     * @param CurrencySearch $currencySearch
     * @param Request $request
     * @return JsonResponse
     */
    public function download(CurrencySearch $currencySearch, Request $request)
    {
        $data = $currencySearch->search($request->query->all())->execute()->fetchAll();

        $response = new JsonResponse($data);
        $response->headers->set('Content-Disposition', 'attachment; filename="currencies.json"');
        return $response;
    }

    /**
     * @Route("/currency/load", name="currency_load")
     * @param Request $request
     * @param CurrencyService $service
     * @return RedirectResponse
     */
    public function load(Request $request, CurrencyService $service)
    {
        if (!$this->isCsrfTokenValid('load', $request->request->get('token'))) {
            return $this->redirectToRoute('currency');
        }
        $date = \DateTime::createFromFormat('Y-m-d',$request->request->get('date'));
        if (!$date instanceof \DateTime) {
            return $this->redirectToRoute('currency');
        }
        try {
            $service->load($date);
            $this->addFlash('success', 'Успешно');
        } catch (\Throwable $exception) {
            $this->addFlash('error', 'Ошибка');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }
}
