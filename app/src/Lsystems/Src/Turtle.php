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
    protected $moves;

    function __construct(GraphicInterface $graphic)
    {
        $this->currentX     = 0;
        $this->currentY     = 0;
        $this->currentAngle = -90;
        $this->step         = 1;
        $this->moves        = 0;
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

    public function getImageId()
    {
        return $this->graphic->getImageId();
    }

    public function getMoves()
    {
        return $this->moves;
    }

    public function newImage($id = 'img-svg')
    {

        $this->graphic->setImageId($id);
        $this->reset();
    }

    public function reset()
    {
        $this->currentX     = 0;
        $this->currentY     = 0;
        $this->step         = 1;
        $this->moves        = 0;
        $this->stack        = [];
        $this->currentAngle = -90;
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
            $step  = $this->step;

        $newCoordinates = $this->getNewCoordinates($step);

        $currentX = $this->currentX;
        $currentY = $this->currentY;
        $newX     = $newCoordinates['x'];
        $newY     = $newCoordinates['y'];

        $this->graphic->drawLine($currentX, $currentY, $newX, $newY);
        $this->moves++;

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

    public function savePosition()
    {
        $stack = $this->stack;
        $array = [];

        $array['x']     = $this->currentX;
        $array['y']     = $this->currentY;
        $array['angel'] = $this->currentAngle;

        array_push($stack, $array);
        $this->stack = $stack;
    }

    public function restorePosition()
    {
        $array = array_pop($this->stack);

        $this->currentX     = $array['x'];
        $this->currentY     = $array['y'];
        $this->currentAngle = $array['angel'];
    }
}
