<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SaleInvoice;
use AppBundle\Form\Transaction\SaleInvoiceType;
use AppBundle\Grid\Transaction\SaleInvoiceGridType;

/**
 * @Route("/transaction/sale_invoice")
 */
class SaleInvoiceController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sale_invoice_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SaleInvoice::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SaleInvoiceGridType::class, $repository, $request);

        return $this->render('transaction/sale_invoice/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sale_invoice_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sale_invoice/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_sale_invoice_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $saleInvoice = new SaleInvoice();
        
        $saleInvoiceService = $this->get('app.transaction.sale_invoice_form');
        $form = $this->createForm(SaleInvoiceType::class, $saleInvoice, array(
            'service' => $saleInvoiceService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $saleInvoiceService->save($saleInvoice);

            return $this->redirectToRoute('transaction_sale_invoice_show', array('id' => $saleInvoice->getId()));
        }

        return $this->render('transaction/sale_invoice/new.'.$_format.'.twig', array(
            'saleInvoice' => $saleInvoice,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sale_invoice_show")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SaleInvoice $saleInvoice)
    {
        return $this->render('transaction/sale_invoice/show.html.twig', array(
            'saleInvoice' => $saleInvoice,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_sale_invoice_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SaleInvoice $saleInvoice, $_format = 'html')
    {
        $saleInvoiceService = $this->get('app.transaction.sale_invoice_form');
        $form = $this->createForm(SaleInvoiceType::class, $saleInvoice, array(
            'service' => $saleInvoiceService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $saleInvoiceService->save($saleInvoice);

            return $this->redirectToRoute('transaction_sale_invoice_show', array('id' => $saleInvoice->getId()));
        }

        return $this->render('transaction/sale_invoice/edit.'.$_format.'.twig', array(
            'saleInvoice' => $saleInvoice,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sale_invoice_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SaleInvoice $saleInvoice)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(SaleInvoice::class);
                $repository->remove($saleInvoice);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sale_invoice_index');
        }

        return $this->render('transaction/sale_invoice/delete.html.twig', array(
            'saleInvoice' => $saleInvoice,
            'form' => $form->createView(),
        ));
    }
}
