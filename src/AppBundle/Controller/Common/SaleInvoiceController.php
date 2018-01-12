<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SaleInvoice;
use AppBundle\Grid\Common\SaleInvoiceGridType;

/**
 * @Route("/common/sale_invoice")
 */
class SaleInvoiceController extends Controller
{
    /**
     * @Route("/grid", name="common_sale_invoice_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $options = array();
        if ($request->query->has('form')) {
            $options['form'] = $request->query->get('form');
        }
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SaleInvoice::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SaleInvoiceGridType::class, $repository, $request, $options);

        return $this->render('common/sale_invoice/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
