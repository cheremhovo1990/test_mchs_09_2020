<?php

namespace App\Controller;

use App\Form\ChartFilterType;
use App\Form\CurrencySearchType;
use App\Form\DownloadRbcFormType;
use App\Repository\CurrencyRepository;
use App\Repository\CurrencyUnitRepository;
use App\Search\CurrencySearch;
use App\Serializer\CurrencySerializer;
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
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CurrencySearch $currencySearch
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        CurrencySearch $currencySearch
    )
    {

        $formSearch = $this->createForm(CurrencySearchType::class);
        $formSearch->handleRequest($request);
        $sql = $currencySearch->search($formSearch->getData() ?? []);
        $pagination = $paginator->paginate($sql, $request->get('page', 1));
        $downloadRbc = $this->createForm(DownloadRbcFormType::class);

        return $this->render('currency/index.html.twig', [
            'pagination' => $pagination,
            'downloadRbc' => $downloadRbc->createView(),
            'formSearch' => $formSearch->createView(),
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
     * @param CurrencySerializer $currencySerializer
     * @return JsonResponse
     */
    public function download(
        CurrencySearch $currencySearch,
        Request $request,
        CurrencySerializer $currencySerializer
    )
    {

        $formSearch = $this->createForm(CurrencySearchType::class);
        $formSearch->handleRequest($request);
        $currencies = $currencySearch->search($formSearch->getData() ?? [])->getQuery()->getResult();
        $response = new JsonResponse( $currencySerializer->serialize($currencies));
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
        $form = $this->createForm(DownloadRbcFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('date')->getData();
        }
        try {
            $service->load($date);
            if (!empty($service->errors)) {
                foreach ($service->errors as $error) {
                    $this->addFlash('error', $error);
                }
            } else {
                $this->addFlash('success', 'Успешно');
            }

        } catch (\Throwable $exception) {
            $this->addFlash('error', 'Ошибка');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }
}
