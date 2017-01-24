<?php
/**
 * Created by PhpStorm.
 * User: filip
 * Date: 24.01.2017
 * Time: 21:33
 */

namespace RestApiBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use RestApiBundle\Entity\Products;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductController extends FOSRestController
{

    /**
     * @Route(path="product", name="product.getAll")
     * @Method({"GET"})
     */
    public function getAll()
    {
        $products = $this->getDoctrine()->getRepository('RestApiBundle:Products')->findAll();

        $view = $this->view($products);
        return $this->handleView($view);
    }

    /**
     * @Route(path="product/{id}", name="product.get")
     * @Method({"GET"})
     */
    public function getProduct(Products $invoice)
    {
        $view = $this->view($invoice);
        return $this->handleView($view);
    }

    /**
     * @Route(path="product", name="product.update")
     * @Method({"PATCH"})
     *
     * @ParamConverter("updatedProduct", converter="fos_rest.request_body")
     */
    public function updateProduct(Products $updatedProduct, ConstraintViolationListInterface $validationErrors)
    {
        $updatedElementId = $updatedProduct->getId();

        if(empty($updatedElementId)) {
            throw new BadRequestHttpException('Id missing');
        }

        $em = $this->getDoctrine()->getManager();
        if(empty($em->getRepository('RestApiBundle:Products')->find($updatedElementId)) ) {
            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {
            $view = $this->view([ 'errors' => $validationErrors ], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $updatedProduct = $em->merge($updatedProduct);
        $em->persist($updatedProduct);
        $em->flush();

        $view = $this->view($updatedProduct);
        return $this->handleView($view);
    }

    /**
     * @Route(path="product", name="product.add")
     * @Method({"POST"})
     *
     * @ParamConverter("product", converter="fos_rest.request_body")
     */
    public function addProduct(Products $product, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            $view = $this->view([ 'errors' => $validationErrors ], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $view = $this->view($product, Response::HTTP_CREATED);
        return $this->handleView($view);
    }

    /**
     * @Route(path="product/{id}", name="product.delete")
     * @Method({"DELETE"})
     */
    public function deleteProduct(Products $product)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $view = $this->view($product, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}