<?php

namespace RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $object = [
            'Test',
            'Test'
        ];

        $serializer = $this->container->get('jms_serializer');
        $data = $serializer->serialize($object, 'json');
        return new Response($data, 200);
    }

    /**
     * @Route("/invoices")
     */
    public function invoicesAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $invoices = $this->getDoctrine()->getRepository('RestApiBundle:Invoices')->createQueryBuilder('p')
            ->setMaxResults(25)->getQuery()->getResult();

//        $invoice = $em->createQueryBuilder('p')
//            ->where('p.divisionId = :id')
//            ->setParameter('id', $id)
//            ->orderBy('p.id', 'ASC')
//            ->leftJoin("AppBundle:User", "u", "WITH", "u.id=p.playerId")
//            ->select('p.id, p.divisionId, p.playerId, p.role, u.username, u.email')
//            ->getQuery();

        $data = $serializer->serialize($invoices, 'json');
        return new Response($data, 200);
    }

    /**
     * @Route("/invoices/{id}")
     */
    public function invoicesIdAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine();
        $invoice = $em->getRepository('RestApiBundle:Invoices')->findOneBy(['id' => $id]);

        if ($invoice){
            $data = $serializer->serialize($invoice, 'json');
            return new Response($data, 200);
        }
        return new Response('', 404);
    }

}
