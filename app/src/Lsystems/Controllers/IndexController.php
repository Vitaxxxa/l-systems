<?php

namespace Lsystems\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Lsystems\Src\SvgGraphic;
use Lsystems\Src\Turtle;
use Lsystems\Src\Lsystem;

class IndexController
{

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

        $lsystem = new Lsystem();

        $lsystem->setAxiom($axiom);
        $lsystem->addRules($rules);
        $lsystem->setBinds($binds);
        $lsystem->setBind('[', 'savePosition', '');
        $lsystem->setBind(']', 'restorePosition', '');

        $image  = $lsystem->createImage($generations);
        $thumbs = $lsystem->createThumbs($generations);

        return json_encode([
            'pic'      => $image,
            'thumbs'   => $thumbs,
        ]);
    }

    public function homeAction(Request $request, \Application $app){
        return $app->redirect("/en/");
    }
}