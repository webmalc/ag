<?php

namespace AG\Bundle\CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AG\Bundle\CarBundle\Document\Car;

/**
 * Car search controller
 * @Route("/")
 */
class SearchController extends Controller
{

    /**
     * Car search process
     * @Route("/search", name="rest_user_car_search", options={"expose"=true})
     * @Method("POST")
     */
    public function searchAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse([
                'success' => false
            ]);
        }

        $number = Car::cleanNumber(implode('', $data));

        $car = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('AGCarBundle:Car')
                ->findOneBy(['number' => $number])
        ;
        if (empty($car)) {
            return new JsonResponse([
                'success' => false
            ]);
        }
        return new JsonResponse([
            'success' => true,
            'car' => $car->jsonSerialize()
        ]);
    }

    /**
     * Send message to user
     * @Route("/send/message/{id}/{type}", name="rest_user_car_send_message", requirements={"type" = "prevent|danger"}, options={"expose"=true})
     * @Method("POST")
     */
    public function sendMessageAction($id, $type)
    {
        $translator = $this->container->get('translator');
        $dm = $this->get('doctrine_mongodb')->getManager();
        $car = $dm->getRepository('AGCarBundle:Car')
                ->find($id)
        ;
        if (empty($car)) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('send_message.error', [], 'AGCarBundle')
            ]);
        }

        /* Send messages */
        $message = [
            'content' => $translator->trans('send_message.' . $type, ['%number%' => $car->getNumber()], 'AGCarBundle')
        ];
        $messanger = $this->container->get('ag.service.messanger');
        $result = $messanger->send(
                $car->getUser(), $message, $translator->trans('send_message.subject', [], 'AGCarBundle'), true
        );
        foreach ($result as $entry) {
            if ($entry['success']) {
                return new JsonResponse([
                    'success' => true,
                    'message' => $translator->trans('send_message.success', [], 'AGCarBundle')
                ]);
            }
        }

        return new JsonResponse([
            'success' => false,
            'message' => $translator->trans('send_message.error', [], 'AGCarBundle')
        ]);
    }

    /**
     * Car search layout
     * @Route("/", name="car_search_layout")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * Modal with messages
     * @Route("/messages/modal", name="car_search_messages_modal", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function messagesModalAction()
    {
        return [];
    }

}
