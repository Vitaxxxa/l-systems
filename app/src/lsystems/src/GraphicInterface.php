<?php
namespace Lsystems\Src;

interface GraphicInterface
{
  	public function setBoardSize($x,$y);
  	public function drawLine($x1,$y1,$x2,$y2);
  	public function getImage();
}
