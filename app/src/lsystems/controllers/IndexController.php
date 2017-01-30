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
        $svg     = new SvgGraphic();
        $trtl    = new Turtle($svg);
        $lsystem = new Lsystem($trtl);

        $axiom       = $_POST['axiom'];
        $generations = $_POST['generations'];
        $rules       = $_POST['rule'];
        $binds       = $_POST['binds'];

        $svg->setLineColor('rgb(0,0,0)');
        $lsystem->setAxiom($axiom);
        $lsystem->setStep($generations);
        $lsystem->addRules($rules);
        $lsystem->setBind('[','savePoint','');
        $lsystem->setBind(']','restorePoint','');
        $lsystem->setBinds($binds);

        $svg->setBoardSize('100%','100%');
        $picId = 'svg-img-'.$generations;
        $lsystem->createImage($picId);
        $pic = $lsystem->getImage($picId);

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