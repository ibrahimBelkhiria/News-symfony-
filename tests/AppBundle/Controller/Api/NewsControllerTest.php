<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02/08/2017
 * Time: 22:20
 */

namespace tests\AppBundle\Controller\Api;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class NewsControllerTest extends TestCase
{

    private $client;

    public function setUp()
    {
        $this->client=new Client(['base_uri'=>'http://127.0.0.1:8000/api']);
    }


    public function tearDown() {
        $this->client = null;
    }


    public function test_create_news_action()
        {


            $data=array(
                'title'=>'test title',
                'description'=>'test description'
            );

            $response=$this->client->post('/news',['body'=>json_encode($data)]);

            $this->assertEquals(201,$response->getStatusCode());
            $this->assertEquals('application/json',$response->getHeaders()["Content-type"][0]);

        }


        public function test_get_news_action()
        {

            $response=$this->client->get('/news/9');
            $this->assertEquals(200,$response->getStatusCode());
            $this->assertEquals('application/json',$response->getHeaders()["Content-Type"][0]);


        }


    public function test_get_list_news_action()
    {
            $response=$this->client->get('/news');
            $this->assertEquals(200,$response->getStatusCode());
            $this->assertEquals('application/json',$response->getHeaders()["Content-Type"][0]);

    }


    public function test_put_news_action()
    {
        $data=array(
            'title'=>'test update',
            'description'=>'test update description'
        );
                    $response=$this->client->put('/news/6',['body'=>json_encode($data)]);
                    $this->assertEquals(200,$response->getStatusCode());
                    $this->assertEquals('application/json',$response->getHeaders()["Content-Type"][0]);

    }



    public function test_delete_news_action()
    {

        $response=$this->client->delete('/news/1');
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals('application/json',$response->getHeaders()["Content-Type"][0]);



    }









}