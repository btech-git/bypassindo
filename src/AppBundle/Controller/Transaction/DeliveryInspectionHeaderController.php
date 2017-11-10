<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\DeliveryInspectionHeader;
use AppBundle\Entity\Master\InspectionItem;
use AppBundle\Form\Transaction\DeliveryInspectionHeaderType;
use AppBundle\Grid\Transaction\DeliveryInspectionHeaderGridType;

/**
 * @Route("/transaction/delivery_inspection_header")
 */
class DeliveryInspectionHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_delivery_inspection_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(DeliveryInspectionHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(DeliveryInspectionHeaderGridType::class, $repository, $request);

        return $this->render('transaction/delivery_inspection_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_delivery_inspection_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/delivery_inspection_header/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_delivery_inspection_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $deliveryInspectionHeader = new DeliveryInspectionHeader();

        $deliveryInspectionHeaderService = $this->get('app.transaction.delivery_inspection_header_form');
        $form = $this->createForm(DeliveryInspectionHeaderType::class, $deliveryInspectionHeader, array(
            'service' => $deliveryInspectionHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
            'inspectionItemRepository' => $this->getDoctrine()->getManager()->getRepository(InspectionItem::class),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deliveryInspectionHeaderService->save($deliveryInspectionHeader);

            return $this->redirectToRoute('transaction_delivery_inspection_header_show', array('id' => $deliveryInspectionHeader->getId()));
        }

        return $this->render('transaction/delivery_inspection_header/new.html.twig', array(
            'deliveryInspectionHeader' => $deliveryInspectionHeader,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_delivery_inspection_header_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        return $this->render('transaction/delivery_inspection_header/show.html.twig', array(
            'deliveryInspectionHeader' => $deliveryInspectionHeader,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_delivery_inspection_header_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        $deliveryInspectionHeaderService = $this->get('app.transaction.delivery_inspection_header_form');
        $form = $this->createForm(DeliveryInspectionHeaderType::class, $deliveryInspectionHeader, array(
            'service' => $deliveryInspectionHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
            'inspectionItemRepository' => $this->getDoctrine()->getManager()->getRepository(InspectionItem::class),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deliveryInspectionHeaderService->save($deliveryInspectionHeader);

            return $this->redirectToRoute('transaction_delivery_inspection_header_show', array('id' => $deliveryInspectionHeader->getId()));
        }

        return $this->render('transaction/delivery_inspection_header/edit.html.twig', array(
            'deliveryInspectionHeader' => $deliveryInspectionHeader,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_delivery_inspection_header_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, DeliveryInspectionHeader $deliveryInspectionHeader)
    {
        $deliveryInspectionHeaderService = $this->get('app.transaction.delivery_inspection_header_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $deliveryInspectionHeaderService->save($deliveryInspectionHeader);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_delivery_inspection_header_index');
        }

        return $this->render('transaction/delivery_inspection_header/delete.html.twig', array(
            'deliveryInspectionHeader' => $deliveryInspectionHeader,
            'form' => $form->createView(),
        ));
    }
}
