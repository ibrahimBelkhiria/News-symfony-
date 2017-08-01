<?php


namespace AppBundle\Controller\Api;


use AppBundle\Entity\News;
use AppBundle\Form\NewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController  extends Controller
{

    /**
     * @Route("/news/{id}",name="news_show")
     * @Method({"GET"})
     */

       public function showNewsAction($id)
       {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

           if (empty($news)) {
               return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
           }

           $data=$this->get('jms_serializer')->serialize($news,'json');

           $response=new Response($data);
           $response->headers->set('Content-Type','application/json');

           return $response;

       }


    /**
     * @Route("/news",name="news_create")
     * @Method({"POST"})
     */
       public function createNewsAction(Request $request)
       {

           $data=$request->getContent();

           $news=$this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\News','json');

           $errors = $this->get('validator')->validate($news);

           if (count($errors)) {
               return new Response($errors, Response::HTTP_BAD_REQUEST);
           }



           $em=$this->getDoctrine()->getManager();
           $em->persist($news);
           $em->flush();


            return new Response('',Response::HTTP_CREATED,['Content-type'=>'application/json']);

       }


    /**
     * @Route("/news",name="list_news")
     * @Method({"GET"})
     */
       public function listNewsAction()
       {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->findAll();

            if (!count($news)){
                throw $this->createNotFoundException('There is no news yet!');
            }


           $data=$this->get('jms_serializer')->serialize($news,'json');

           $response=new Response($data);
           $response->headers->set('Content-Type', 'application/json');
           return $response;


       }

    /**
     * @Route("/news/{id}",name="update_news")
     * @Method({"PUT"})
     */
        public function updateNewsAction($id,Request $request)
      {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

          if (empty($news))
          {
              return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
          }

          $body=$request->getContent();


          $data=$this->get('jms_serializer')->deserialize($body,'AppBundle\Entity\News','json');


              $news->setTitle($data->getTitle());
              $news->setDescription($data->getDescription());

                  $em=$this->getDoctrine()->getManager();
                  $em->persist($news);
                  $em->flush();

                  return new Response('',200);


      }




        /**
         * @Route("/news/{id}",name="delete_news")
         * @Method({"DELETE"})
         */
       public function deleteNewsAction($id)
       {

           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

           if (empty($news)) {
               return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
           }

           $em=$this->getDoctrine()->getManager();
           $em->remove($news);
           $em->flush();


           return new Response('',200);


       }









}