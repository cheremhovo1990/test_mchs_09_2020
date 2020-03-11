<?php

namespace App\Controller;

use App\Form\ChartFilterType;
use App\Form\CurrencySearchType;
use App\Repository\CurrencyRepository;
use App\Repository\CurrencyUnitRepository;
use App\Search\CurrencySearch;
use App\Service\CurrencyService;
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
        $formSearch = $this->createForm(CurrencySearchType::class);
        $formSearch->handleRequest($request);
        $sql = $currencySearch->search($formSearch->getData() ?? []);
        $pagination = $paginator->paginate($sql, $request->get('page', 1));
        $now = (new \DateTime())->format('Y-m-d');

        return $this->render('currency/index.html.twig', [
            'pagination' => $pagination,
            'params' => $params,
            'now' => $now,
            'formSearch' => $formSearch->createView()
        ]);
    }

    /**
     * @Route("/currency/chart", name="currency_chart")
     * @param Request $request
     * @param CurrencyRepository $currencyRepository
     * @param CurrencyUnitRepository $currencyUnitRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function chart(
        Request $request,
        CurrencyRepository $currencyRepository,
        CurrencyUnitRepository $currencyUnitRepository
    )
    {
        $formFilter = $this->createForm(ChartFilterType::class);
        $formFilter->handleRequest($request);
        $currencyUnit = $currencyUnitRepository->find($formFilter->get('currency_unit_id')->getData());
        $currencies = $currencyRepository->findAllByCharCode(
            $currencyUnit->getId(),
            $formFilter->get('begin')->getData(),
            $formFilter->get('end')->getData()
        );
        $currencies = array_map(function ($value) {
            return [$value['date']->format('Y-m-d'), (float)$value['value']];
        }, $currencies);

        return $this->render('currency/chart.html.twig', [
            'currencies' => $currencies,
            'currencyUnit' => $currencyUnit,
            'formFilter' => $formFilter->createView(),
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
