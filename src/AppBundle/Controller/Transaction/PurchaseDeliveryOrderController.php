<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseDeliveryOrder;
use AppBundle\Form\Transaction\PurchaseDeliveryOrderType;
use AppBundle\Grid\Transaction\PurchaseDeliveryOrderGridType;

/**
 * @Route("/transaction/purchase_delivery_order")
 */
class PurchaseDeliveryOrderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_purchase_delivery_order_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseDeliveryOrder::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseDeliveryOrderGridType::class, $repository, $request);

        return $this->render('transaction/purchase_delivery_order/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_purchase_delivery_order_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/purchase_delivery_order/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_purchase_delivery_order_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $purchaseDeliveryOrder = new PurchaseDeliveryOrder();
        
        $purchaseDeliveryOrderService = $this->get('app.transaction.purchase_delivery_order_form');
        $form = $this->createForm(PurchaseDeliveryOrderType::class, $purchaseDeliveryOrder, array(
            'service' => $purchaseDeliveryOrderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $purchaseDeliveryOrderService->save($purchaseDeliveryOrder);

            return $this->redirectToRoute('transaction_purchase_delivery_order_show', array('id' => $purchaseDeliveryOrder->getId()));
        }

        return $this->render('transaction/purchase_delivery_order/new.'.$_format.'.twig', array(
            'purchaseDeliveryOrder' => $purchaseDeliveryOrder,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_purchase_delivery_order_show")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        return $this->render('transaction/purchase_delivery_order/show.html.twig', array(
            'purchaseDeliveryOrder' => $purchaseDeliveryOrder,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_purchase_delivery_order_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, PurchaseDeliveryOrder $purchaseDeliveryOrder, $_format = 'html')
    {
        $purchaseDeliveryOrderService = $this->get('app.transaction.purchase_delivery_order_form');
        $form = $this->createForm(PurchaseDeliveryOrderType::class, $purchaseDeliveryOrder, array(
            'service' => $purchaseDeliveryOrderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $purchaseDeliveryOrderService->save($purchaseDeliveryOrder);

            return $this->redirectToRoute('transaction_purchase_delivery_order_show', array('id' => $purchaseDeliveryOrder->getId()));
        }

        return $this->render('transaction/purchase_delivery_order/edit.html.twig', array(
            'purchaseDeliveryOrder' => $purchaseDeliveryOrder,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_purchase_delivery_order_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, PurchaseDeliveryOrder $purchaseDeliveryOrder)
    {
        $purchaseDeliveryOrderService = $this->get('app.transaction.purchase_delivery_order_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $purchaseDeliveryOrderService->delete($purchaseDeliveryOrder);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_purchase_delivery_order_index');
        }

        return $this->render('transaction/purchase_delivery_order/delete.html.twig', array(
            'purchaseDeliveryOrder' => $purchaseDeliveryOrder,
            'form' => $form->createView(),
        ));
    }
}
