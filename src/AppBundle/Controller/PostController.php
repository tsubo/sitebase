<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("post")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_index")
     */
    public function indexAction()
    {
        // http://docs.guzzlephp.org/en/latest/
        // https://developer.wordpress.com/docs/api/
        $client = $this->get('guzzle.client.api_wp_com');
        $response = $client->get('posts', ['query' => [
            'status' => 'publish',
            'number' => 10,
            'fields' => 'ID,date,title,excerpt,status',
        ]]);
        $body = json_decode($response->getBody(), true);

        return $this->render('post/index.html.twig', [
            'posts' => $body['posts'],
            'url' => 'https://ja.blog.wordpress.com/',
        ]);
    }

    /**
     * @Route("/{id}", name="post_show")
     */
    public function showAction($id)
    {
        $client = $this->get('guzzle.client.api_wp_com');
        $response = $client->get("posts/${id}");
        $body = json_decode($response->getBody(), true);

        return $this->render('post/show.html.twig', [
            'post' => $body
        ]);
    }
}
