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
use RestApiBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CategoryController extends FOSRestController
{

    /**
     * @Route(path="category", name="category.getAll")
     * @Method({"GET"})
     */
    public function getAll()
    {
        $categories = $this->getDoctrine()->getRepository('RestApiBundle:Category')->findAll();

        $view = $this->view($categories);
        return $this->handleView($view);
    }

    /**
     * @Route(path="category/{id}", name="category.getProducts")
     * @Method({"GET"})
     */
    public function getCategory($id)
    {
        $categories = $this->getDoctrine()->getRepository('RestApiBundle:Products')->findBy(['category' => $id]);

        $view = $this->view($categories);
        return $this->handleView($view);
    }

    /**
     * @Route(path="category", name="category.update")
     * @Method({"PATCH"})
     *
     * @ParamConverter("updatedCategory", converter="fos_rest.request_body")
     */
    public function updateCategory(Category $updatedCategory, ConstraintViolationListInterface $validationErrors)
    {
        $updatedElementId = $updatedCategory->getId();

        if (empty($updatedElementId)) {
            throw new BadRequestHttpException('Id missing');
        }

        $em = $this->getDoctrine()->getManager();
        if (empty($em->getRepository('RestApiBundle:Category')->find($updatedElementId))) {
            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {
            $view = $this->view(['errors' => $validationErrors], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $updatedCategory = $em->merge($updatedCategory);
        $em->persist($updatedCategory);
        $em->flush();

        $view = $this->view($updatedCategory);
        return $this->handleView($view);
    }

    /**
     * @Route(path="category", name="category.add")
     * @Method({"POST"})
     *
     * @ParamConverter("category", converter="fos_rest.request_body")
     */
    public function addCategory(Category $category, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            $view = $this->view(['errors' => $validationErrors], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $view = $this->view($category, Response::HTTP_CREATED);
        return $this->handleView($view);
    }

    /**
     * @Route(path="category/{id}", name="category.delete")
     * @Method({"DELETE"})
     */
    public function deleteCategory(Category $category)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $view = $this->view($category, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}