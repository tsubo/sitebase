<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("blog")
 */
class RssController extends Controller
{
    const URL = 'http://event.lafino.co.jp/';
    const RSS_URL = 'http://event.lafino.co.jp/feed/';

    /**
     * @Route("/", name="blog_index")
     */
    public function indexAction()
    {
        $rss = $this->getRss();

        // description 内の「もっと読む」を削除できるか調査
//        dump(preg_replace("/<div class=\"post-permalink\">[\n\t.]*<a.*div>/", '', $rss->raw_data));

        return $this->render('rss/index.html.twig', array(
            'rss' => $rss,
            'url' => self::URL,
        ));
    }

    /**
     * @Route("/{id}", name="blog_show")
     */
    public function showAction($id)
    {
        $rss = $this->getRss();
        $item = $rss->get_item($id);

        return $this->render('rss/show.html.twig', array(
            'item' => $item,
        ));
    }

    protected function getRss()
    {
        $rss = $this->get('fkr_simple_pie.rss');
        $rss->set_feed_url(self::RSS_URL);
        $rss->set_item_limit(10);
        $rss->init();

        return $rss;
    }

}
