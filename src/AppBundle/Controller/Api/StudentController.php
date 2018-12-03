<?php
/**
 * Created by PhpStorm.
 * Date: 03.12.18
 * Time: 13:58
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Student;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StudentController
 * @package AppBundle\Controller\Api
 *
 * @Rest\Route("/student")
 */
class StudentController extends FOSRestController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Rest\Get("/")
     * @SWG\Get(path="/api/student/",
     *   tags={"STUDENT"},
     *   security=false,
     *   summary="GET ALL STUDENTS",
     *   description="The method for getting all students",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *      name="Content-Type",
     *      in="header",
     *      required=true,
     *      type="string",
     *      default="application/json",
     *      description="Content Type"
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      type="integer",
     *      default=1,
     *      description="pagination page"
     *   ),
     *   @SWG\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      type="integer",
     *      default=20,
     *      description="pagination limit"
     *   ),
     *   @SWG\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
     *      type="string",
     *      description="find by name or email or phone"
     *   ),
     *   @SWG\Response(
     *      response=200,
     *      description="Success. List Students",
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="data",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(
     *                      property="id",
     *                      type="integer"
     *                  ),
     *                  @SWG\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @SWG\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @SWG\Property(
     *                      property="phone",
     *                      type="string"
     *                  )
     *              ),
     *          ),
     *          @SWG\Property(
     *              type="object",
     *              property="pagination",
     *              @SWG\Property(
     *                  type="integer",
     *                  property="current_page_number"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="total_count"
     *              ),
     *          )
     *      )
     *   )
     * )
     */
    public function getAllAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository("AppBundle:Student")->getAll($request->query->all());

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $students,
            ($request->query->getInt('page', 1) > 0) ? $request->query->getInt('page', 1) : 1,
            ($request->query->getInt('limit', 20) > 0) ? $request->query->getInt('limit', 20) : 20
        );
        $view = $this->view([
            'data' => $pagination->getItems(),
            'pagination' => [
                'current_page_number' => $pagination->getCurrentPageNumber(),
                'total_count' => $pagination->getTotalItemCount(),
            ]
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Rest\Post("/")
     * @SWG\Post(path="/api/student/",
     *   tags={"STUDENT"},
     *   security=false,
     *   summary="CREATE STUDENT",
     *   description="The method for creating student",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *      name="Content-Type",
     *      in="header",
     *      required=true,
     *      type="string",
     *      default="application/json",
     *      description="Content Type"
     *   ),
     *   @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      required=true,
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="name",
     *              example="name",
     *              description="required",
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="email",
     *              example="example@example.com",
     *              description="required",
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="phone",
     *              example="123456789",
     *              description="required",
     *          )
     *      )
     *   ),
     *   @SWG\Response(
     *      response=200,
     *      description="Success. Student Created",
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="id",
     *              type="integer"
     *          )
     *      )
     *   ),
     *   @SWG\Response(
     *      response=400,
     *      description="Bad request",
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="error",
     *              type="array",
     *              @SWG\Items(
     *                  type="string"
     *              )
     *          )
     *      )
     *   )
     * )
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $student = new Student($request->request->all());
        $errors = $this->get('validator')->validate($student, null, array('student'));
        if(count($errors) === 0){
            $em->persist($student);
            $em->flush();

            $view = $this->view(['id'=>$student->getId()], Response::HTTP_OK);
        }
        else {
            $error_description = [];
            foreach ($errors as $er) {
                $error_description[] = $er->getMessage();
            }
            $view = $this->view(['error'=>$error_description], Response::HTTP_BAD_REQUEST);

        }
        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     *
     * @Rest\Get("/{id}",requirements={"id"="\d+"})
     * @SWG\Get(path="/api/student/{id}",
     *   tags={"STUDENT"},
     *   security=false,
     *   summary="GET STUDENT BY ID",
     *   description="The method for getting student by id",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *      name="Content-Type",
     *      in="header",
     *      required=true,
     *      type="string",
     *      default="application/json",
     *      description="Content Type"
     *   ),
     *   @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      type="integer",
     *      default="",
     *      description="id"
     *   ),
     *   @SWG\Response(
     *      response=200,
     *      description="Success. Student Info",
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="id",
     *              type="integer"
     *          ),
     *          @SWG\Property(
     *              property="name",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="email",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="phone",
     *              type="string"
     *          )
     *      )
     *   ),
     *   @SWG\Response(
     *      response=404,
     *      description="NOT FOUND",
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="error",
     *              type="array",
     *              @SWG\Items(
     *                  type="string"
     *              )
     *          )
     *      )
     *   )
     * )
     */
    public function getByIdAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository("AppBundle:Student")->find($id);
        if($student instanceof Student){
            $view = $this->view($student, Response::HTTP_OK);
        }
        else{
            $view = $this->view(['error'=>['Student Not found']], Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);
    }
}