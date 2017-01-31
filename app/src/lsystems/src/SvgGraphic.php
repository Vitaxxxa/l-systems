<?php
namespace Lsystems\Src;

class SvgGraphic implements GraphicInterface
{
    protected $size = [];
    protected $line = [];
    protected $id   = '';

    function __construct($x=400, $y=400)
    {
        $this->size['x'] = $x;
        $this->size['y'] = $y;
    }

    public function setBoardSize($x=400,$y=400)
    {
        if ($x > 0 AND $y > 0){
            $this->size['x'] = $x;
            $this->size['y'] = $y;
        }
    }

    public function drawLine($x1,$y1,$x2,$y2)
    {
        $line = $this->line;
        $string = "<line x1='".$x1."' y1='".$y1."' x2='".$x2."' y2='".$y2."' style='stroke:#000000; stroke-width:1px;' />";
        return $string;
    }

    public function svgOpenTag($data)
    {
        $svgOpenTag = "<svg version='1.1' xmlns='http://www.w3.org/2000/svg'>";

        if (isset($data)){
            $id     = $data['id'];
            $width  = $data['width'];
            $height = $data['height'];

            if (empty($id))     $id     = 'svg';
            if (empty($width))  $width  = $this->size['x'];
            if (empty($height)) $height = $this->size['y'];

            $svgOpenTag  = "<svg";
            $svgOpenTag .= " id='$id'";
            $svgOpenTag .= " width='$width'";
            $svgOpenTag .= " height='$height'";
            $svgOpenTag .= " version='1.1' xmlns='http://www.w3.org/2000/svg'>";
        }
    
        return $svgOpenTag;
    }

    public function svgCloseTag(){
        return "</svg>";
    }
}
