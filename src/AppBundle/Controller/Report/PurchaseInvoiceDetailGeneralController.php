<?php

namespace AppBundle\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseInvoiceDetailGeneral;
use AppBundle\Grid\Report\PurchaseInvoiceDetailGeneralGridType;

/**
 * @Route("/report/purchase_invoice_detail_general")
 */
class PurchaseInvoiceDetailGeneralController extends Controller
{
    /**
     * @Route("/grid", name="report_purchase_invoice_detail_general_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_ACCOUNTING_HEAD') or has_role('ROLE_OPERATIONAL_HEAD') or has_role('ROLE_SALES_MANAGER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseInvoiceDetailGeneral::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseInvoiceDetailGeneralGridType::class, $repository, $request);

        return $this->render('report/purchase_invoice_detail_general/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="report_purchase_invoice_detail_general_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNTING_HEAD') or has_role('ROLE_OPERATIONAL_HEAD') or has_role('ROLE_SALES_MANAGER')")
     */
    public function indexAction()
    {
        return $this->render('report/purchase_invoice_detail_general/index.html.twig');
    }
}
