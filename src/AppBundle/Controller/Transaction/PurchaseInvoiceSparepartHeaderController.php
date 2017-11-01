<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseInvoiceSparepartHeader;
use AppBundle\Form\Transaction\PurchaseInvoiceSparepartHeaderType;
use AppBundle\Grid\Transaction\PurchaseInvoiceSparepartHeaderGridType;

/**
 * @Route("/transaction/purchase_invoice_sparepart_header")
 */
class PurchaseInvoiceSparepartHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_purchase_invoice_sparepart_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseInvoiceSparepartHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseInvoiceSparepartHeaderGridType::class, $repository, $request);

        return $this->render('transaction/purchase_invoice_sparepart_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_purchase_invoice_sparepart_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/purchase_invoice_sparepart_header/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_purchase_invoice_sparepart_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $purchaseInvoiceSparepartHeader = new PurchaseInvoiceSparepartHeader();
        $form = $this->createForm(PurchaseInvoiceSparepartHeaderType::class, $purchaseInvoiceSparepartHeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(PurchaseInvoiceSparepartHeader::class);
            $repository->add($purchaseInvoiceSparepartHeader);

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_header_show', array('id' => $purchaseInvoiceSparepartHeader->getId()));
        }

        return $this->render('transaction/purchase_invoice_sparepart_header/new.html.twig', array(
            'purchaseInvoiceSparepartHeader' => $purchaseInvoiceSparepartHeader,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_purchase_invoice_sparepart_header_show")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(PurchaseInvoiceSparepartHeader $purchaseInvoiceSparepartHeader)
    {
        return $this->render('transaction/purchase_invoice_sparepart_header/show.html.twig', array(
            'purchaseInvoiceSparepartHeader' => $purchaseInvoiceSparepartHeader,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_purchase_invoice_sparepart_header_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, PurchaseInvoiceSparepartHeader $purchaseInvoiceSparepartHeader)
    {
        $form = $this->createForm(PurchaseInvoiceSparepartHeaderType::class, $purchaseInvoiceSparepartHeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(PurchaseInvoiceSparepartHeader::class);
            $repository->update($purchaseInvoiceSparepartHeader);

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_header_show', array('id' => $purchaseInvoiceSparepartHeader->getId()));
        }

        return $this->render('transaction/purchase_invoice_sparepart_header/edit.html.twig', array(
            'purchaseInvoiceSparepartHeader' => $purchaseInvoiceSparepartHeader,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_purchase_invoice_sparepart_header_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, PurchaseInvoiceSparepartHeader $purchaseInvoiceSparepartHeader)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(PurchaseInvoiceSparepartHeader::class);
                $repository->remove($purchaseInvoiceSparepartHeader);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_header_index');
        }

        return $this->render('transaction/purchase_invoice_sparepart_header/delete.html.twig', array(
            'purchaseInvoiceSparepartHeader' => $purchaseInvoiceSparepartHeader,
            'form' => $form->createView(),
        ));
    }
}
