<?php
namespace Lsystems\Src;

class SvgGraphic implements GraphicInterface
{
  protected $size = [];
  protected $line = [];
  protected $id = '';
  protected $temp;

  function __construct($x=400, $y=400){
    $this->size['x'] = $x;
    $this->size['y'] = $y;
    $this->setLineColor();
    $this->setLineWidth();
  }

  public function setBoardSize($x=400,$y=400){
    if ($x > 0 AND $y > 0){
      $this->size['x'] = $x;
      $this->size['y'] = $y;
    }
  }

  public function setLineColor($color = 'rgb(0,0,0)'){
    $this->line['color'] = $color;
  }

  public function setLineWidth($width = 1){
    $this->line['width'] = $width;
  }

  public function drawLine($x1,$y1,$x2,$y2){
    $line = $this->line;
    $string = "<line x1='".$x1."' y1='".$y1."' x2='".$x2."' y2='".$y2."' style='stroke:".$line['color']."; stroke-width:".$line['width']."' />";
    return $string;
  }

  public function svgOpenTag($id){
    $this->id = $id;
    $size = $this->size;
    return "<svg id='".$id."' width='".$size['x']."' height='".$size['y']."' version='1.1' xmlns='http://www.w3.org/2000/svg'>"; //viewBox='-50 -25 8000 4000'
  }

  public function svgCloseTag(){
    return "</svg>";
  }



}