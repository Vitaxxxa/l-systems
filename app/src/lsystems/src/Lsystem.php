<?php
namespace Lsystems\Src;

class Lsystem
{
    protected $axiom = "";
    protected $rules = [];
    protected $binds = [];
    protected $steps = 1;
    protected $turtle= null;
    protected $image = null;

    function __construct(Turtle $turtle)
    {
        $this->turtle = $turtle;
    }

    protected function generatePath()
    {
        $steps = $this->steps;
        $rules = $this->rules;
        $string= $this->axiom;

        for ($i=0;$i<$steps;$i++){
            $string = strtr($string, $rules);
        }

        return $string;
    }

    public function setBind($key,$do,$param)
    {
        $this->binds[$key]= ['do'=>$do,'param'=>$param];
    }

    public function setBinds($array)
    {
        foreach ($array as $key => $bind){
            $this->setBind($key,$bind['value'],$bind['param']);
        }
    }

    protected function getGraphic()
    {
        $turtle = $this->turtle;
        return $turtle->getGraphic();
    }

    public function createImage($picId){
        $trtl    = $this->turtle;
        $string  = $this->generatePath();
        $binds   = $this->binds;
        $graphic = $this->getGraphic();
        $image = $graphic->svgOpenTag($picId);
        for ($i=0; $i < strlen($string); $i++){
            foreach ($binds as $key => $bind){

                if ($string[$i] == $key){
                    if ($bind['do'] == 'moveForward')
                        $image .= $trtl->moveForward($bind['param']);
                    if ($bind['do'] == 'moveRight')
                        $image .= $trtl->moveRight($bind['param']);
                    if ($bind['do'] == 'moveLeft')
                        $image .= $trtl->moveLeft($bind['param']);
                    if ($bind['do'] == 'savePoint')
                        $image .= $trtl->savePoint();
                    if ($bind['do'] == 'restorePoint')
                        $image .= $trtl->restorePoint();
                }
            }
        }
        $this->image = $image.$graphic->svgCloseTag();;
    }

    public function createThumbs($g)
    {
        if ($g > 1){
            $array = [];

            for ($i=0; $i<$g; $i++){
                $picId = 'svg-img-'.$i;
                $this->setStep($i);
                $this->getGraphic()->setBoardSize('100%', '300px');
                $this->createImage($picId);
                $image = $this->image;
                $array[$i]['id']    = $picId;
                $array[$i]['image'] = $image;
            }

            return $array;
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setAxiom($axiom){
        $this->axiom = $axiom;
    }

    public function addRule($key,$value){
        $this->rules[$key]=$value;
    }

    public function addRules($array){
        foreach ($array as $key => $rule){
            $this->addRule($key,$rule);
        }
    }

    public function setStep($step){
        $this->steps = $step;
    }
}