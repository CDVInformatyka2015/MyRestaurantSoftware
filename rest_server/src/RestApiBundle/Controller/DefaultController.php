<?php

namespace RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use JMS\Serializer\SerializerBuilder;

class DefaultController extends Controller
{

    /**
     * Zwrot wszystkich routów dostępnych przez API
     * @Route("/", name="api.Homepage")
     */
    public function indexAction()
    {
        $router = $this->container->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        foreach ($allRoutes as $route => $params) {
            $defaults = $params->getDefaults();
            $path = $params->getPath();

            if (isset($defaults['_controller'])) {
                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];

                if (!isset($routes[$controller])) {
                    $routes[$controller] = array();
                }

                $routes[$controller]['route'] = $route;
                $routes[$controller]['path'] = $path;
            }
        }

        $serializer = $this->container->get('jms_serializer');
        $data = $serializer->serialize($routes, 'json');
        return new Response(
            $data,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/invoices")
     */
    public function invoicesAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $invoices = $this->getDoctrine()->getRepository('RestApiBundle:Invoices')->createQueryBuilder('p')
            ->setMaxResults(25)->getQuery()->getResult();

        $data = $serializer->serialize($invoices, 'json');
        return new Response(
            $data,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/invoices/{id}")
     */
    public function invoicesIdAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine();
        $invoice = $em->getRepository('RestApiBundle:Invoices')->findOneBy(['id' => $id]);

        if ($invoice) {
            $data = $serializer->serialize($invoice, 'json');
            return new Response($data, 200);
        }
        return new Response('', 404);
    }

}
