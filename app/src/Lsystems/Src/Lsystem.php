<?php
namespace Lsystems\Src;

class Lsystem
{
    protected $axiom;
    protected $rules;
    protected $binds;

    function __construct()
    {
        $this->axiom = "";
        $this->rules = [];
        $this->binds = [];
    }

    public function setBind($key, $do, $param)
    {
        $this->binds[$key] = ['do' => $do, 'param' => $param];
    }

    public function setBinds($array)
    {
        foreach ($array as $key => $bind){
            $this->setBind($key, $bind['value'], $bind['param']);
        }
    }

    public function setAxiom($axiom)
    {
        $this->axiom = $axiom;
    }

    public function addRule($key, $rule)
    {
        $this->rules[$key]=$rule;
    }

    public function addRules($array)
    {
        foreach ($array as $key => $rule){
            $this->addRule($key, $rule);
        }
    }

    protected function generatePath($g)
    {
        $generations = $g;
        $rules       = $this->rules;
        $string      = $this->axiom;

        for ($i = 0; $i < $generations; $i++){
            $string = strtr($string, $rules);
        }

        return $string;
    }

    public function createImage($generation)
    {
        $startTime = microtime(true);
        $graphic   = new SvgGraphic();
        $turtle    = new Turtle($graphic);
        $path      = $this->generatePath($generation);
        $binds     = $this->binds;

        $turtle->newImage('svg-img' . $generation);

        for ($i = 0; $i < strlen($path); $i++){
            foreach ($binds as $key => $bind){

                $do    = $bind['do'];
                $param = $bind['param'];

                if ($path[$i] === $key){
                    if ($do === 'moveForward')
                        $turtle->moveForward($param);
                    if ($do === 'moveRight')
                        $turtle->moveRight($param);
                    if ($do === 'moveLeft')
                        $turtle->moveLeft($param);
                    if ($do === 'savePosition')
                        $turtle->savePosition();
                    if ($do === 'restorePosition')
                        $turtle->restorePosition();
                }
            }
        }

        $image   = $turtle->getImage();
        $imageId = $turtle->getImageId();
        $moves   = $turtle->getMoves();

        unset($turtle);

        $time = round(microtime(true) - $startTime, 3);

        return [
            'image'       => $image,
            'id'          => $imageId,
            'moves'       => $moves,
            'time'        => $time,
            'generations' => $generations,
            'source'      => $path
        ];
    }

    public function createThumbs($generations)
    {
        if ($generations >= 1){
            $thumbs = [];

            for ($i = 0; $i <= $generations; $i++){
                $thumbs[$i] = $this->createImage($i);
            }

            return $thumbs;
        }
    }
}
