<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseDeliveryOrder;
use AppBundle\Grid\Common\PurchaseDeliveryOrderGridType;

/**
 * @Route("/common/purchase_delivery_order")
 */
class PurchaseDeliveryOrderController extends Controller
{
    /**
     * @Route("/grid", name="common_purchase_delivery_order_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseDeliveryOrder::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseDeliveryOrderGridType::class, $repository, $request);

        return $this->render('common/purchase_delivery_order/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
