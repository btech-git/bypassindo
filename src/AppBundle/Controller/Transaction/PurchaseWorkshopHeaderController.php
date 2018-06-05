<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\PurchaseWorkshopHeader;
use AppBundle\Form\Transaction\PurchaseWorkshopHeaderType;
use AppBundle\Grid\Transaction\PurchaseWorkshopHeaderGridType;

/**
 * @Route("/transaction/purchase_workshop_header")
 */
class PurchaseWorkshopHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_purchase_workshop_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_NEW') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_EDIT') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_DELETE')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PurchaseWorkshopHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(PurchaseWorkshopHeaderGridType::class, $repository, $request);

        return $this->render('transaction/purchase_workshop_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_purchase_workshop_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_NEW') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_EDIT') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_DELETE')")
     */
    public function indexAction()
    {
        return $this->render('transaction/purchase_workshop_header/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_purchase_workshop_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_NEW')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $purchaseWorkshopHeader = new PurchaseWorkshopHeader();
        
        $purchaseWorkshopHeaderService = $this->get('app.transaction.purchase_workshop_header_form');
        $form = $this->createForm(PurchaseWorkshopHeaderType::class, $purchaseWorkshopHeader, array(
            'service' => $purchaseWorkshopHeaderService,
            'init' => array('staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $purchaseWorkshopHeaderService->save($purchaseWorkshopHeader);

            return $this->redirectToRoute('transaction_purchase_workshop_header_show', array('id' => $purchaseWorkshopHeader->getId()));
        }

        return $this->render('transaction/purchase_workshop_header/new.'.$_format.'.twig', array(
            'purchaseWorkshopHeader' => $purchaseWorkshopHeader,
            'form' => $form->createView(),
            'purchaseWorkshopDetailsCount' => 0,
        ));
    }

    /**
     * @Route("/{id}", name="transaction_purchase_workshop_header_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_NEW') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_EDIT') or has_role('ROLE_PURCHASE_WORKSHOP_HEADER_DELETE')")
     */
    public function showAction(PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        return $this->render('transaction/purchase_workshop_header/show.html.twig', array(
            'purchaseWorkshopHeader' => $purchaseWorkshopHeader,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_purchase_workshop_header_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_EDIT')")
     */
    public function editAction(Request $request, PurchaseWorkshopHeader $purchaseWorkshopHeader, $_format = 'html')
    {
        $purchaseWorkshopDetailsCount = $purchaseWorkshopHeader->getPurchaseWorkshopDetails()->count();

        $purchaseWorkshopHeaderService = $this->get('app.transaction.purchase_workshop_header_form');
        $form = $this->createForm(PurchaseWorkshopHeaderType::class, $purchaseWorkshopHeader, array(
            'service' => $purchaseWorkshopHeaderService,
            'init' => array('staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $purchaseWorkshopHeaderService->save($purchaseWorkshopHeader);

            return $this->redirectToRoute('transaction_purchase_workshop_header_show', array('id' => $purchaseWorkshopHeader->getId()));
        }

        return $this->render('transaction/purchase_workshop_header/edit.'.$_format.'.twig', array(
            'purchaseWorkshopHeader' => $purchaseWorkshopHeader,
            'form' => $form->createView(),
            'purchaseWorkshopDetailsCount' => $purchaseWorkshopDetailsCount,
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_purchase_workshop_header_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PURCHASE_WORKSHOP_HEADER_DELETE')")
     */
    public function deleteAction(Request $request, PurchaseWorkshopHeader $purchaseWorkshopHeader)
    {
        $purchaseWorkshopHeaderService = $this->get('app.transaction.purchase_workshop_header_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $purchaseWorkshopHeaderService->delete($purchaseWorkshopHeader);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_purchase_workshop_header_index');
        }

        return $this->render('transaction/purchase_workshop_header/delete.html.twig', array(
            'purchaseWorkshopHeader' => $purchaseWorkshopHeader,
            'form' => $form->createView(),
        ));
    }
}
