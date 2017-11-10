<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseInvoiceSparepartDetail;
use AppBundle\Form\Transaction\PurchaseInvoiceSparepartDetailType;
use AppBundle\Grid\Transaction\PurchaseInvoiceSparepartDetailGridType;

/**
 * @Route("/transaction/purchase_invoice_sparepart_detail")
 */
class PurchaseInvoiceSparepartDetailController extends Controller
{
    /**
     * @Route("/grid", name="transaction_purchase_invoice_sparepart_detail_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseInvoiceSparepartDetail::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseInvoiceSparepartDetailGridType::class, $repository, $request);

        return $this->render('transaction/purchase_invoice_sparepart_detail/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_purchase_invoice_sparepart_detail_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/purchase_invoice_sparepart_detail/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_purchase_invoice_sparepart_detail_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $purchaseInvoiceSparepartDetail = new PurchaseInvoiceSparepartDetail();
        $form = $this->createForm(PurchaseInvoiceSparepartDetailType::class, $purchaseInvoiceSparepartDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(PurchaseInvoiceSparepartDetail::class);
            $repository->add($purchaseInvoiceSparepartDetail);

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_detail_show', array('id' => $purchaseInvoiceSparepartDetail->getId()));
        }

        return $this->render('transaction/purchase_invoice_sparepart_detail/new.html.twig', array(
            'purchaseInvoiceSparepartDetail' => $purchaseInvoiceSparepartDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_purchase_invoice_sparepart_detail_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(PurchaseInvoiceSparepartDetail $purchaseInvoiceSparepartDetail)
    {
        return $this->render('transaction/purchase_invoice_sparepart_detail/show.html.twig', array(
            'purchaseInvoiceSparepartDetail' => $purchaseInvoiceSparepartDetail,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_purchase_invoice_sparepart_detail_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, PurchaseInvoiceSparepartDetail $purchaseInvoiceSparepartDetail)
    {
        $form = $this->createForm(PurchaseInvoiceSparepartDetailType::class, $purchaseInvoiceSparepartDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(PurchaseInvoiceSparepartDetail::class);
            $repository->update($purchaseInvoiceSparepartDetail);

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_detail_show', array('id' => $purchaseInvoiceSparepartDetail->getId()));
        }

        return $this->render('transaction/purchase_invoice_sparepart_detail/edit.html.twig', array(
            'purchaseInvoiceSparepartDetail' => $purchaseInvoiceSparepartDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_purchase_invoice_sparepart_detail_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, PurchaseInvoiceSparepartDetail $purchaseInvoiceSparepartDetail)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(PurchaseInvoiceSparepartDetail::class);
                $repository->remove($purchaseInvoiceSparepartDetail);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_purchase_invoice_sparepart_detail_index');
        }

        return $this->render('transaction/purchase_invoice_sparepart_detail/delete.html.twig', array(
            'purchaseInvoiceSparepartDetail' => $purchaseInvoiceSparepartDetail,
            'form' => $form->createView(),
        ));
    }
}
