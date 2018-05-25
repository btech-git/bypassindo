<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/transaction/sale_invoice_sparepart_header")
 */
class SaleInvoiceSparepartHeaderController extends Controller
{
    /**
     * @Route("/import", name="transaction_sale_invoice_sparepart_header_import")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PURCHASE_INVOICE_SPAREPART_HEADER')")
     */
    public function importAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('headerDataFile', FileType::class, array('constraints' => array(new NotNull())))
            ->add('detailDataFile', FileType::class, array('constraints' => array(new NotNull())))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if ($formData['headerDataFile']->isValid() && $formData['detailDataFile']->isValid()) {
                $saleInvoiceSparepartHeaderSheet = $this->get('app.transaction.sale_invoice_sparepart_header_sheet');

                $headerMappingXml = $this->renderView('transaction/sale_invoice_sparepart_header/import_mapping_header.xml.twig');
                $detailMappingXml = $this->renderView('transaction/sale_invoice_sparepart_header/import_mapping_detail.xml.twig');
                $objects = $saleInvoiceSparepartHeaderSheet->parse($formData['headerDataFile']->getPathname(), $headerMappingXml, $formData['detailDataFile']->getPathname(), $detailMappingXml);

                if ($saleInvoiceSparepartHeaderSheet->validate($objects)) {
                    $saleInvoiceSparepartHeaderSheet->save($objects);
                    $this->addFlash('success', array('title' => 'Success!', 'message' => 'The records was imported successfully.'));
                } else {
                    $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to import the records.'));
                }

                return $this->redirectToRoute('transaction_sale_invoice_sparepart_header_index');
            }
        }

        return $this->render('transaction/sale_invoice_sparepart_header/import.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
