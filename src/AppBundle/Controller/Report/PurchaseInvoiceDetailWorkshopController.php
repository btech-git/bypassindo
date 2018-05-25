<?php

namespace AppBundle\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseInvoiceDetailWorkshop;
use AppBundle\Grid\Report\PurchaseInvoiceDetailWorkshopGridType;

/**
 * @Route("/report/purchase_invoice_detail_workshop")
 */
class PurchaseInvoiceDetailWorkshopController extends Controller
{
    /**
     * @Route("/grid", name="report_purchase_invoice_detail_workshop_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseInvoiceDetailWorkshop::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseInvoiceDetailWorkshopGridType::class, $repository, $request);

        return $this->render('report/purchase_invoice_detail_workshop/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="report_purchase_invoice_detail_workshop_index")
     * @Method("GET")
     * @Security("has_role('ROLE_REPORT')")
     */
    public function indexAction()
    {
        return $this->render('report/purchase_invoice_detail_workshop/index.html.twig');
    }
}
