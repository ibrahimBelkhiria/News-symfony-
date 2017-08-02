<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02/08/2017
 * Time: 14:19
 */

namespace AppBundle\Service;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Validate
{

    private $validator;
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator=$validator;

    }


    public function validateRequest($data)
    {
        $errors = $this->validator->validate($data);

        $errorsResponse = array();

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorsResponse[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }


        if (count($errors))
        {

            $reponse=array(

                'code'=>1,
               'message'=>'validation errors',
                 'errors'=>$errorsResponse,
                   'result'=>null
                         );

            return $reponse;
        }else{

            $reponse=[];
            return $reponse;




        }

    }

















}