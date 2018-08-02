<?php

namespace AppBundle\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SaleInvoiceDetailUnit;
use AppBundle\Grid\Report\SaleInvoiceDetailUnitGridType;

/**
 * @Route("/report/sale_invoice_detail_unit")
 */
class SaleInvoiceDetailUnitController extends Controller
{
    /**
     * @Route("/grid", name="report_sale_invoice_detail_unit_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SaleInvoiceDetailUnit::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SaleInvoiceDetailUnitGridType::class, $repository, $request);

        return $this->render('report/sale_invoice_detail_unit/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="report_sale_invoice_detail_unit_index")
     * @Method("GET")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function indexAction()
    {
        return $this->render('report/sale_invoice_detail_unit/index.html.twig');
    }
}
