<?php

namespace AppBundle\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SaleInvoiceDetail;
use AppBundle\Grid\Report\SaleInvoiceDetailGridType;

/**
 * @Route("/report/sale_invoice_detail")
 */
class SaleInvoiceDetailController extends Controller
{
    /**
     * @Route("/grid", name="report_sale_invoice_detail_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SaleInvoiceDetail::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SaleInvoiceDetailGridType::class, $repository, $request);

        return $this->render('report/sale_invoice_detail/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="report_sale_invoice_detail_index")
     * @Method("GET")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function indexAction()
    {
        return $this->render('report/sale_invoice_detail/index.html.twig');
    }
}
