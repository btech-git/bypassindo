<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SalePayment;
use AppBundle\Form\Transaction\SalePaymentType;
use AppBundle\Grid\Transaction\SalePaymentGridType;

/**
 * @Route("/transaction/sale_payment")
 */
class SalePaymentController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sale_payment_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SalePayment::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SalePaymentGridType::class, $repository, $request);

        return $this->render('transaction/sale_payment/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sale_payment_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sale_payment/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_sale_payment_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $salePayment = new SalePayment();

        $salePaymentService = $this->get('app.transaction.sale_payment_form');
        $form = $this->createForm(SalePaymentType::class, $salePayment, array(
            'service' => $salePaymentService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $salePaymentService->save($salePayment);

            return $this->redirectToRoute('transaction_sale_payment_show', array('id' => $salePayment->getId()));
        }

        return $this->render('transaction/sale_payment/new.'.$_format.'.twig', array(
            'salePayment' => $salePayment,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sale_payment_show")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SalePayment $salePayment)
    {
        return $this->render('transaction/sale_payment/show.html.twig', array(
            'salePayment' => $salePayment,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_sale_payment_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SalePayment $salePayment, $_format = 'html')
    {
        $salePaymentService = $this->get('app.transaction.sale_payment_form');
        $form = $this->createForm(SalePaymentType::class, $salePayment, array(
            'service' => $salePaymentService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $salePaymentService->save($salePayment);

            return $this->redirectToRoute('transaction_sale_payment_show', array('id' => $salePayment->getId()));
        }

        return $this->render('transaction/sale_payment/edit.'.$_format.'.twig', array(
            'salePayment' => $salePayment,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sale_payment_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SalePayment $salePayment)
    {
        $salePaymentService = $this->get('app.transaction.sale_payment_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $salePaymentService->delete($salePayment);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sale_order_index');
        }

        return $this->render('transaction/sale_payment/delete.html.twig', array(
            'salePayment' => $salePayment,
            'form' => $form->createView(),
        ));
    }
}
