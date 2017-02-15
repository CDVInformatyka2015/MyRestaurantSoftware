<?php

namespace RestApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{

    /**
     * Zwrot wszystkich routów dostępnych przez API
     * @Route("/doc", name="api.doc")
     */
    public function indexAction()
    {
        $router = $this->get('router');
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

        $view = $this->view($routes);
        return $this->handleView($view);
    }

    /**
     * Zaloguj sie i pobierz token
     * @Route("login", name="api.login")
     */
    public function tokenAuthentication(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);

        if(!$user) {
            $view = $this->view(['status' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
            return $this->handleView($view);
        }

        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            $view = $this->view(['status' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
            return $this->handleView($view);
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername()]);

        $view = $this->view(['token' => $token], Response::HTTP_OK);
        return $this->handleView($view);
    }

}
