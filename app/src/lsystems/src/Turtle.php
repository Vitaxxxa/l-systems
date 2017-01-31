<?php
namespace Lsystems\Src;

class Turtle
{
    protected $currentX = 0;
    protected $currentY = 0;
    protected $currentAngle = -90;
    protected $step = 1;
    protected $pen = true;
    protected $board;
    protected $stack =[];

    function __construct(GraphicInterface $board)
    {
        $this->board = $board;
    }
    public function getGraphic()
    {
        return $this->board;
    }

    protected function getNewPosition($argument) 
    {
        $newX = $this->currentX;
        $newY = $this->currentY;
        $deg = $this->currentAngle;
        if ( 0 === $deg % 360 ) {
            $newX += $argument;
        } else if ( 90 === $deg % 360 ) {
            $newY += $argument;
        } else if ( 180 === $deg % 360 ) {
            $newX -= $argument;
        } else if ( 270 === $deg % 360 ) {
            $newY -= $argument;
        } else {
            $newX = $this->currentX + cos(deg2rad($deg)) * $argument;
            $newY = $this->currentY + sin(deg2rad($deg)) * $argument;
        }

        return array(
            'x' => $newX,
            'y' => $newY,
        );
    }

    protected function changeAngle($angleChange)
    {
        $this->currentAngle += $angleChange;
        //while ( $this->currentAngle < 0) {
        //    $this->currentAngle += 360;
        //}
    }

    public function penUp()
    {
        $this->pen = false;
    }

    public function penDown()
    {
        $this->pen = true;
    }

    public function moveForward($step=0)
    {
        if ($step <= 0)
            $step = $this->step;

        $newPosition = $this->getNewPosition($step);

        if ($this->pen === true){
            $temp = $this->board->drawLine( $this->currentX, $this->currentY, $newPosition['x'], $newPosition['y']);
        }

        $this->currentX = $newPosition['x'];
        $this->currentY = $newPosition['y'];

        return $temp;
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
        array_push($this->stack, [
            'x'     => $this->currentX,
            'y'     => $this->currentY,
            'angel' => $this->currentAngle
        ]);
    }

    public function restorePoint()
    {
        $array = array_pop($this->stack);

        $this->currentX = $array['x'];
        $this->currentY = $array['y'];
        $this->currentAngle = $array['angel'];
    }

    public function getImage()
    {
        return $this->board->getImage();
    }

}
