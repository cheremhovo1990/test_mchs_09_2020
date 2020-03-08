<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
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
}
