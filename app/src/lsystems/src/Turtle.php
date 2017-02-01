<?php

namespace Lsystems\Src;

class Turtle
{
    protected $currentX;
    protected $currentY;
    protected $currentAngle;
    protected $step;
    protected $stack;
    protected $graphic;

    function __construct(GraphicInterface $graphic)
    {
        $this->currentX     = 0;
        $this->currentY     = 0;
        $this->currentAngle = 0;
        $this->step         = 1;
        $this->stack        = [];
        $this->graphic      = $graphic;
    }

    public function getGraphic()
    {
        return $this->graphic;
    }

    public function getImage()
    {
        return $this->graphic->getImage();
    }

    protected function getNewCoordinates($argument) 
    {
        $newX  = $this->currentX;
        $newY  = $this->currentY;
        $angel = $this->currentAngle;

        if ( 0 === $angel % 360 ) {
            $newX += $argument;
        }else if ( 90 === $angel % 360 ) {
            $newY += $argument;
        }else if ( 180 === $angel % 360 ) {
            $newX -= $argument;
        }else if ( 270 === $angel % 360 ) {
            $newY -= $argument;
        }else {
            $newX = $this->currentX + cos(deg2rad($angel)) * $argument;
            $newY = $this->currentY + sin(deg2rad($angel)) * $argument;
        }

        return [
            'x' => $newX,
            'y' => $newY,
        ];
    }

    protected function changeAngle($angel)
    {
        $this->currentAngle += $angel;
    }

    public function moveForward($step=0)
    {
        if ($step <= 0)
            $step = $this->step;

        $newCoordinates = $this->getNewCoordinates($step);

        $newX = $newCoordinates['x'];
        $newY = $newCoordinates['y'];

        $this->graphic->drawLine($this->currentX, $this->currentY, $newX, $newY);
        
        $this->currentX = $newX;
        $this->currentY = $newY;
    }

    public function moveBackward($step)
    {
        $this->moveForward(-$step);
    }

    public function moveRight($angle)
    {
        $this->changeAngle($angle);
    }

    public function moveLeft($angle)
    {
        $this->changeAngle(-$angle);
    }

    public function savePoint()
    {
        $stack = $this->stack;
        $array = [];

        $array['x']     = $this->currentX;
        $array['y']     = $this->currentY;
        $array['angel'] = $this->currentAngle;

        array_push($stack, $array);
        $this->stack = $stack;
    }

    public function restorePoint()
    {
        $array = array_pop($this->stack);

        $this->currentX     = $array['x'];
        $this->currentY     = $array['y'];
        $this->currentAngle = $array['angel'];
    }
}
