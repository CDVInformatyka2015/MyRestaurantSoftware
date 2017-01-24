<?php
/**
 * Created by PhpStorm.
 * User: filip
 * Date: 23.01.2017
 * Time: 21:37
 */

namespace RestApiBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use RestApiBundle\Entity\Invoices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvoiceController extends FOSRestController
{

    /**
     * @Route(path="invoice", name="invoice.getAll")
     * @Method({"GET"})
     */
    public function getAllInvoices()
    {
        $invoices = $this->getDoctrine()->getRepository('RestApiBundle:Invoices')->findAll();

        $view = $this->view($invoices);
        return $this->handleView($view);
    }

    /**
     * @Route(path="invoice/{id}", name="invoice.get")
     * @Method({"GET"})
     */
    public function getInvoice(Invoices $invoice)
    {
        $view = $this->view($invoice);
        return $this->handleView($view);
    }

    /**
     * @Route(path="invoice", name="invoice.update")
     * @Method({"PATCH"})
     *
     * @ParamConverter("updatedInvoice", converter="fos_rest.request_body")
     */
    public function updateInvoice(Invoices $updatedInvoice, ConstraintViolationListInterface $validationErrors)
    {
        $updatedElementId = $updatedInvoice->getId();

        if(empty($updatedElementId)) {
            throw new BadRequestHttpException('Id missing');
        }

        $em = $this->getDoctrine()->getManager();
        if(empty($em->getRepository('RestApiBundle:Invoices')->find($updatedElementId)) ) {
            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {
            $view = $this->view([ 'errors' => $validationErrors ], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $updatedInvoice = $em->merge($updatedInvoice);
        $em->persist($updatedInvoice);
        $em->flush();

        $view = $this->view($updatedInvoice);
        return $this->handleView($view);
    }

    /**
     * @Route(path="invoice", name="invoice.add")
     * @Method({"POST"})
     *
     * @ParamConverter("invoice", converter="fos_rest.request_body")
     */
    public function addInvoice(Invoices $invoice, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            $view = $this->view([ 'errors' => $validationErrors ], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($invoice);
        $em->flush();

        $view = $this->view($invoice, Response::HTTP_CREATED);
        return $this->handleView($view);
    }

    /**
     * @Route(path="invoice/{id}", name="invoice.delete")
     * @Method({"DELETE"})
     */
    public function deleteInvoice(Invoices $invoice)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();

        $view = $this->view($invoice, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}