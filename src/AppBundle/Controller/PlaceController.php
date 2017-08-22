<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\Place;

class PlaceController extends Controller
{
    /**
     * @Route("/places", name="places_list")
     * @Method({"GET"})
     */
    public function getPlacesAction()
    {

      
        $repository = $this->get('doctrine_mongodb')
        ->getManager()
        ->getRepository('AppBundle:Place');

        $places = $repository->findAll();

        return new JsonResponse($places);
    }

     /**
         * @Route("/places", name="create_place")
         * @Method({"POST"})
         */
    public function createAction(Request $request)
    {
      
       $data = json_decode($request->getContent(), true);
       $place = new Place($data["name"],$data["price"]);
        
        

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($place);
        $dm->flush();

        return new Response('Created place id '.$place->getId());

    }


    /**
     * @Route("/places/{id}", name="place_one")
     * @Method({"GET"})
     */
    public function getPlaceById($id,Request $request)
    {

      
        $repository = $this->get('doctrine_mongodb')
        ->getManager()
        ->getRepository('AppBundle:Place');

        $place = $repository->findOneById($id);

        return new JsonResponse($place);
    }


       /**
     * @Route("/places/{id}", name="delete_place")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
    $dm = $this->get('doctrine_mongodb')->getManager();
           

    $repository = $this->get('doctrine_mongodb')
        ->getManager()
        ->getRepository('AppBundle:Place');


         $place = $repository->findOneById($id);

         if(!empty($place))
            {
                $dm->remove($place);
                $dm->flush();
                return new Response('deleted place id '.$place->getId());     
            }

          else return new Response('no place with id'.$id);  

           

    }

       /**
     * @Route("/places/{id}", name="update_place")
     * @Method({"PUT"})
     */
    public function updateAction($id,Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository('AppBundle:Place');
        $place = $repository->findOneById($id);

        if (!$place) {
            return new Response ('No product found for id '.$id);
        }

        $data = json_decode($request->getContent(), true);
       
        $place->setName($data["name"]);
        $place->setPrice($data["price"]);
        $dm->flush();

        return new Response ("updated ".$place->getName());
    }
}