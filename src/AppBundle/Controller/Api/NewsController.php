<?php


namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Validate;
use Nelmio\ApiDocBundle\Annotation as Doc;
class NewsController  extends Controller
{

    /**
     * @Doc\ApiDoc(
     *     resource=true,
     *     description="Get one single news",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The news unique identifier."
     *         }
     *     },
     *     section="news"
     * )
     * @Route("/news/{id}",name="news_show")
     * @Method({"GET"})
     */

       public function showNewsAction($id)
       {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

           if (empty($news)) {
               $response=array(

                   'code'=>1,
                   'message'=>'News Not found !',
                   'errors'=>null,
                   'result'=>null

               );


               return new JsonResponse($response, Response::HTTP_NOT_FOUND);
           }

           $data=$this->get('jms_serializer')->serialize($news,'json');
           $response=array(

               'code'=>0,
               'message'=>'success',
               'errors'=>null,
               'result'=>json_decode($data)

           );

           return new JsonResponse($response,200);

       }


    /**
     * @Doc\ApiDoc(
     *    description="Create a new news",
     *    input={"class"=NewsType::class},
     *    statusCodes = {
     *        201 = "Creation with success",
     *        400 = "invalid form"
     *    },
     *    responseMap={
     *         201 = {"class"=News::class},
     *         400 = { "class"=NewsType::class, "form_errors"=true}
     *    },
     *     section="news"
     * )
     * @Route("/news",name="news_create")
     * @Method({"POST"})
     */
       public function createNewsAction(Request $request,Validate $validate)
       {

           $data=$request->getContent();

           $news=$this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\News','json');


             $reponse=$validate->validateRequest($news);
             //dump($reponse);die();

             if (!empty($reponse)){
                 return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
             }



           $em=$this->getDoctrine()->getManager();
           $em->persist($news);
           $em->flush();


            return new Response('',Response::HTTP_CREATED,['Content-type'=>'application/json']);

       }


    /**
     * @Doc\ApiDoc(
     *     resource=true,
     *     description="Get the list of all news.",
     *     section="news"
     * )
     * @Route("/news",name="list_news")
     * @Method({"GET"})
     */
       public function listNewsAction()
       {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->findAll();

            if (!count($news)){
                $response=array(

                    'code'=>1,
                    'message'=>'No news found!',
                    'errors'=>null,
                    'result'=>null

                );


                return new JsonResponse($response, Response::HTTP_NOT_FOUND);
            }


           $data=$this->get('jms_serializer')->serialize($news,'json');

           $response=array(

               'code'=>0,
               'message'=>'success',
               'errors'=>null,
               'result'=>json_decode($data)

           );

           return new JsonResponse($response,200);


       }

    /**
     *@Doc\ApiDoc(
     *         description="update a specific news",
     *    input={"class"=NewsType::class},
     *    statusCodes = {
     *        200 = "update with success",
     *        400 = "invalid form",
     *         404="news not found"
     *    },
     *     section="news"
     *)
     *
     *
     *
     * @Route("/news/{id}",name="update_news")
     * @Method({"PUT"})
     */
        public function updateNewsAction($id,Request $request,Validate $validate)
      {
           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

          if (empty($news))
          {
              $response=array(

                  'code'=>1,
                  'message'=>'News Not found !',
                  'errors'=>null,
                  'result'=>null

              );

              return new JsonResponse($response, Response::HTTP_NOT_FOUND);
          }

          $body=$request->getContent();


          $data=$this->get('jms_serializer')->deserialize($body,'AppBundle\Entity\News','json');


            $reponse= $validate->validateRequest($data);

           if (!empty($reponse))
           {
                      return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);

           }

              $news->setTitle($data->getTitle());
              $news->setDescription($data->getDescription());

              $em=$this->getDoctrine()->getManager();
              $em->persist($news);
              $em->flush();

              return new Response('',200,['Content-Type'=>'application/json']);



        }




        /**
         *
         * @Doc\ApiDoc(
         *     statusCodes={
         *                    200="news deleted with success",
         *                     404="news not found"
         *                       },
         *     section="news"
         *
         * )
         *
         * @Route("/news/{id}",name="delete_news")
         * @Method({"DELETE"})
         */
       public function deleteNewsAction($id)
       {

           $news=$this->getDoctrine()->getRepository('AppBundle:News')->find($id);

           if (empty($news)) {

               $response=array(

                   'code'=>1,
                   'message'=>'News Not found !',
                   'errors'=>null,
                   'result'=>null

               );


               return new JsonResponse($response, Response::HTTP_NOT_FOUND);
           }

           $em=$this->getDoctrine()->getManager();
           $em->remove($news);
           $em->flush();


           return new Response('',200,['Content-Type'=>'application/json']);


       }




}