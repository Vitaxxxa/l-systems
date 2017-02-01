<?php

namespace Lsystems\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Lsystems\Src\SvgGraphic;
use Lsystems\Src\Turtle;
use Lsystems\Src\Lsystem;

class IndexController 
{
    /**
     * List of articles
     *
     * @param Request $request
     * @param \Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, \Application $app)
    {
        return $app->render('index.html.twig');
    }

    public function createAction(Request $request, \Application $app)
    {
        $axiom       = $_POST['axiom'];
        $generations = $_POST['generations'];
        $rules       = $_POST['rule'];
        $binds       = $_POST['binds'];

        $svg     = new SvgGraphic();
        $picId = 'svg-img-'.$generations;
        $svg->setSvgId($picId);
        
        $trtl    = new Turtle($svg);
        $lsystem = new Lsystem($trtl);

        $lsystem->setAxiom($axiom);
        $lsystem->setGenerations($generations);
        $lsystem->addRules($rules);
        $lsystem->setBind('[','savePoint','');
        $lsystem->setBind(']','restorePoint','');
        $lsystem->setBinds($binds);

        $lsystem->createImage($picId);
        
        $pic = $lsystem->getImage();

        $thumbs = $lsystem->createThumbs($generations);

        return json_encode(array(
            'pic' => array(
                'image' => $pic,
                'id'    => $picId
                ),
            'thumbs' => $thumbs
        ));
    }

    public function homeAction(Request $request, \Application $app){
        return $app->redirect("/en/");
    }
}