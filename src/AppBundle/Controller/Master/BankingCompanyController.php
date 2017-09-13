<?php

namespace AppBundle\Controller\Master;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Master\BankingCompany;
use AppBundle\Form\Master\BankingCompanyType;
use AppBundle\Grid\Master\BankingCompanyGridType;

/**
 * @Route("/master/banking_company")
 */
class BankingCompanyController extends Controller
{
    /**
     * @Route("/grid", name="master_banking_company_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(BankingCompany::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(BankingCompanyGridType::class, $repository, $request);

        return $this->render('master/banking_company/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="master_banking_company_index")
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function indexAction()
    {
        return $this->render('master/banking_company/index.html.twig');
    }

    /**
     * @Route("/new", name="master_banking_company_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function newAction(Request $request)
    {
        $bankingCompany = new BankingCompany();
        $form = $this->createForm(BankingCompanyType::class, $bankingCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(BankingCompany::class);
            $repository->add($bankingCompany);

            return $this->redirectToRoute('master_banking_company_show', array('id' => $bankingCompany->getId()));
        }

        return $this->render('master/banking_company/new.html.twig', array(
            'bankingCompany' => $bankingCompany,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="master_banking_company_show")
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function showAction(BankingCompany $bankingCompany)
    {
        return $this->render('master/banking_company/show.html.twig', array(
            'bankingCompany' => $bankingCompany,
        ));
    }

    /**
     * @Route("/{id}/edit", name="master_banking_company_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function editAction(Request $request, BankingCompany $bankingCompany)
    {
        $form = $this->createForm(BankingCompanyType::class, $bankingCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(BankingCompany::class);
            $repository->update($bankingCompany);

            return $this->redirectToRoute('master_banking_company_show', array('id' => $bankingCompany->getId()));
        }

        return $this->render('master/banking_company/edit.html.twig', array(
            'bankingCompany' => $bankingCompany,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="master_banking_company_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function deleteAction(Request $request, BankingCompany $bankingCompany)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(BankingCompany::class);
                $repository->remove($bankingCompany);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('master_banking_company_index');
        }

        return $this->render('master/banking_company/delete.html.twig', array(
            'bankingCompany' => $bankingCompany,
            'form' => $form->createView(),
        ));
    }
}
