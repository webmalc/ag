<?php

namespace AG\Bundle\CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AG\Bundle\CarBundle\Document\Car;

/**
 * Car controller
 * @Route("/cars")
 */
class CarsController extends Controller
{

    /**
     * @Route("/", name="rest_user_cars", options={"expose"=true})
     * @Method("GET")
     */
    public function carsAction()
    {
        $carsData = [];
        
        $dm = $this->get('doctrine_mongodb')->getManager();
        
        $cars = $dm->createQueryBuilder('AGCarBundle:Car')
            ->field('user.id')->equals($this->getUser()->getId())
            ->sort('number', 'asc')
            ->getQuery()
            ->execute()
        ;
        
        foreach ($cars as $car) {
            $carsData[] = $car->jsonSerialize();
        }
        return new JsonResponse($carsData);
    }

    /**
     * @Route("/create", name="rest_user_car_create", options={"expose"=true})
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $translator = $this->container->get('translator');
        $data = json_decode($request->getContent(), true);

        if (empty($data['number'])) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('create.error', [], 'AGCarBundle')
            ]);
        }

        $car = new Car();
        $car->setNumber($data['number'])
                ->setUser($this->getUser())
        ;
        $errors = $this->get('validator')->validate($car);

        if (count($errors)) {

            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('create_exists.error', [], 'AGCarBundle')
            ]);
        } else {
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($car);
            $dm->flush();
        }

        return new JsonResponse([
            'success' => true,
            'message' => $translator->trans('create.success', [], 'AGCarBundle'),
            'car' => $car->jsonSerialize()
        ]);
    }
    
    /**
     * @Route("/update/{id}", name="rest_user_car_update", options={"expose"=true})
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $translator = $this->container->get('translator');
        $data = json_decode($request->getContent(), true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $car = $dm->getRepository('AGCarBundle:Car')->find($id);
        
        if (empty($data) || empty($car)) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('update.error', [], 'AGCarBundle')
            ]);
        }
        $car->setArrayData($data);
        $errors = $this->get('validator')->validate($car);
        
        if (count($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('update_exists.error', [], 'AGCarBundle')
            ]);
        } else {
            $dm->persist($car);
            $dm->flush();
        }
        
        return new JsonResponse([
            'success' => true,
            'message' => $translator->trans('update.success', [], 'AGCarBundle'),
            'car' => $car->jsonSerialize()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="rest_user_car_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $translator = $this->container->get('translator');
        $user = $this->getUser();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $car = $dm->getRepository('AGCarBundle:Car')->find($id);

        if (!$car || $car->getUser()->getId() != $user->getId()) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('delete.error', [], 'AGCarBundle')
            ]);
        }
        $dm->remove($car);
        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => $translator->trans('delete.success', [], 'AGCarBundle')
        ]);
    }

    /**
     * @Route("/marks", name="rest_user_car_marks", options={"expose"=true})
     * @Method("GET")
     */
    public function getMarks()
    {
        return new JsonResponse([
            ['text' => 'Acura'],
            ['text' => 'Alfa Romeo'],
            ['text' => 'Aston Martin'],
            ['text' => 'Audi'],
            ['text' => 'Bentley'],
            ['text' => 'BMW'],
            ['text' => 'Brilliance'],
            ['text' => 'Bugatti'],
            ['text' => 'BYD'],
            ['text' => 'Cadillac'],
            ['text' => 'Changan'],
            ['text' => 'Chery'],
            ['text' => 'Chevrolet'],
            ['text' => 'Chrysler'],
            ['text' => 'Citroen'],
            ['text' => 'Daewoo'],
            ['text' => 'Dodge'],
            ['text' => 'FAW'],
            ['text' => 'Ferrari'],
            ['text' => 'Fiat'],
            ['text' => 'Ford'],
            ['text' => 'Foton'],
            ['text' => 'Geely'],
            ['text' => 'GMC'],
            ['text' => 'Great Wall'],
            ['text' => 'Haima'],
            ['text' => 'Honda'],
            ['text' => 'Hyundai'],
            ['text' => 'Infiniti'],
            ['text' => 'JAC'],
            ['text' => 'Jaguar'],
            ['text' => 'Jeep'],
            ['text' => 'Kia'],
            ['text' => 'Lamborghini'],
            ['text' => 'Land Rover'],
            ['text' => 'Lexus'],
            ['text' => 'Lifan'],
            ['text' => 'Lincoln'],
            ['text' => 'Maserati'],
            ['text' => 'Mazda'],
            ['text' => 'Mc Laren'],
            ['text' => 'Mercedes-Benz'],
            ['text' => 'Mini'],
            ['text' => 'Mitsubishi'],
            ['text' => 'Nissan'],
            ['text' => 'Opel'],
            ['text' => 'Peugeot'],
            ['text' => 'Porsche'],
            ['text' => 'Renault'],
            ['text' => 'Rolls-Royce'],
            ['text' => 'SEAT'],
            ['text' => 'Skoda'],
            ['text' => 'Smart'],
            ['text' => 'Spyker'],
            ['text' => 'Ssang Yong'],
            ['text' => 'Subaru'],
            ['text' => 'Suzuki'],
            ['text' => 'Tesla'],
            ['text' => 'Toyota'],
            ['text' => 'Volkswagen'],
            ['text' => 'Volvo'],
            ['text' => 'Vortex'],
            ['text' => 'ВАЗ'],
            ['text' => 'ЗАЗ'],
            ['text' => 'ТагАЗ'],
            ['text' => 'УАЗ'],
        ]);
    }
    
    /**
     * @Route("/models", name="rest_user_car_models", options={"expose"=true})
     * @Method("GET")
     */
    public function getModels()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        
        $cars = $dm->createQueryBuilder('AGCarBundle:Car')
            ->distinct('model')
            ->sort('model', 0)
            ->getQuery()
            ->execute()
        ;
        return new JsonResponse($cars->toArray());
    }

}
