<?php

/**
 * @author 
 * @author Quetzalcoatl Pantoja Hinojosa
 * @package class
**/


  class JobEntry
  {
    private $data = array();
    private $bgcolor = array(255, 255, 255);
    private $bordercolor = array(100, 100, 100);
    private $borderwidth = 1;
    private $rect_bordercolor = array(170, 170, 170);
    private $rect_bgcolor = array(200, 200, 200);
    private $fontcolor = array(0, 0, 0);
    private $font = 2;
    private $fontwidth = 0;
    private $fontheight = 0;
    private $padding = 10;
    private $inpadding = 5;
    private $spacepadding = 5;
    private $alpha =0; // 0-127 transparencia
    private $leftoffset = 0;
    
    function JobEntry($data)
    {
      if (is_array($data))
      {
        $this->data = $data;
        $this->leftoffset = 0;
        //$this->Draw();
        return true;
      }
      else
      {
        return false;
      }
    }
    
    
    function SetBackgroundColor($r, $g, $b)
    {
      $this->bgcolor = array($r, $g, $b);
    }
    function SetBorderColor($r, $g, $b)
    {
      $this->bordercolor = array($r, $g, $b);
    }
    function SetBorderWidth($n)
    {
      $this->borderwidth = ($n < 0 ? 0 : (int) $n);
    }
    function SetRectangleBackgroundColor($r, $g, $b)
    {
      $this->rect_bgcolor = array($r, $g, $b);
    }
    function SetRectangleBorderColor($r, $g, $b)
    {
      $this->rect_bordercolor = array($r, $g, $b);
    }
    function SetFontColor($r, $g, $b)
    {
      $this->fontcolor = array($r, $g, $b);
    }
    function SetFont($font)
    {
      $this->font = $font;
    }
    function SetPadding($p)
    {
      $this->padding = (int) $p;
    }
    function SetInPadding($p)
    {
      $this->inpadding = (int) $p;
    }
    function SetSpacing($p)
    {
      $this->spacepadding = (int) $p;
    }

    function Draw($file = "")
    {
      if (count($this->data) == 0)
      {
        return;
      }

      $arrk = array_keys($this->data);
      $this->fontwidth = imagefontwidth($this->font);
      $this->fontheight = imagefontheight($this->font);
      $maxw = $this->__GetMaxWidth($this->data);

      $w = $maxw + (2 * $this->padding) + 1;
      $h = $this->__GetMaxDeepness($this->data);
      $h = (2 * $this->padding) +
           (($this->fontheight + (2 * $this->inpadding)) * $h) +
           ((2 * $this->spacepadding) * ($h - 1)) + 1;

      $this->im = imagecreatetruecolor($w, $h);
      
      // background color
      $this->__AllocateColor("im_bgcolor", $this->bgcolor, false);
      imagefilledrectangle($this->im, 0, 0, $w, $h, $this->im_bgcolor);
      if ($this->borderwidth > 0)
      {
        $this->__AllocateColor("im_bordercolor", $this->bordercolor);
        for ($i = 0; $i < $this->borderwidth; $i++) {
          imagerectangle($this->im, $i, $i, $w - 1 - $i, $h - 1 - $i, $this->im_bordercolor);
        }
      }
      
      // allocate colors
      $this->__AllocateColor("im_rect_bgcolor", $this->rect_bgcolor);
      $this->__AllocateColor("im_rect_bordercolor", $this->rect_bordercolor);
      $this->__AllocateColor("im_fontcolor", $this->fontcolor);
      
      // draw all data
      $this->__DrawData($this->data[$arrk[0]], $this->padding);
      
      // draw 1st square
      $rw = ($this->fontwidth * strlen($arrk[0])) + (2 * $this->inpadding);
      $x1 = round(($w - $rw) / 2);
      $y1 = $this->padding;
      $x2 = $x1 + $rw;
      $y2 = $y1 + (2 * $this->inpadding) + $this->fontheight;
      $this->__Rectangle($x1, $y1, $x2, $y2, $this->im_rect_bordercolor, $this->im_rect_bgcolor);
      imagestring($this->im, $this->font, $x1 + $this->inpadding, $y1 + $this->inpadding, $arrk[0], $this->im_fontcolor);
      $x1 = $x1 + round(($x2 - $x1) / 2);
      imageline($this->im, $x1, $y2 + 1, $x1, $y2 + $this->spacepadding - 1, $this->im_rect_bordercolor);
      
      // output
      if (strlen($file) > 0 && is_dir(dirname($file)))
      {
        imagepng($this->im, $file);
      }
      else
      {
        header("Content-Type: image/png");
        imagepng($this->im);
      }
    }

    function __DrawData(&$data, $offset = 0, $level = 1, $width = 0)
    {
      $top = $this->padding + ($level * (($this->spacepadding * 2) + $this->fontheight + (2 * $this->inpadding)));
      $startx = $endx = 0;
      foreach ($data as $k => $v)
      {
        if (is_array($v))
        {
          $width = $this->__GetMaxWidth($v);
          $rw = ($this->fontwidth * strlen($k)) + (2 * $this->inpadding);
          if ($width < $rw)
          {
            $width = $rw;
          }

          $x1 = $offset + round(($width - $rw) / 2);
          $y1 = $top;
          $x2 = $x1 + $rw;
          $y2 = $y1 + (2 * $this->inpadding) + $this->fontheight;

          //echo "($x1,$y1)-($x2,$y2)<br>\n";
          $this->__Rectangle($x1, $y1, $x2, $y2, $this->im_rect_bordercolor, $this->im_rect_bgcolor);
          imagestring($this->im, $this->font, $x1 + $this->inpadding, $y1 + $this->inpadding, $k, $this->im_fontcolor);
          
          // upper line
          $x1 = $x1 + round(($x2 - $x1) / 2);
          imageline($this->im, $x1, $y1 - 1, $x1, $y1 - $this->spacepadding + 1, $this->im_rect_bordercolor);

          // lower line
          imageline($this->im, $x1, $y2 + 1, $x1, $y2 + $this->spacepadding - 1, $this->im_rect_bordercolor);

          $this->__DrawData($v, $offset, $level + 1, $width);
          $offset += $width + $this->spacepadding + 1;
        }
        else
        {
          $rw = ($this->fontwidth * strlen($v)) + (2 * $this->inpadding);

          if (count($data) == 1)
          {
            $offset += round(($width - $rw) / 2);
          }

          $x1 = $offset;
          $y1 = $top;
          $x2 = $x1 + $rw;
          $y2 = $y1 + (2 * $this->inpadding) + $this->fontheight;
          
          $this->__Rectangle($x1, $y1, $x2, $y2, $this->im_rect_bordercolor, $this->im_rect_bgcolor);
          imagestring($this->im, $this->font, $x1 + $this->inpadding, $y1 + $this->inpadding, $v, $this->im_fontcolor);

          // upper line
          $x1 = $x1 + round(($x2 - $x1) / 2);
          imageline($this->im, $x1, $y1 - 1, $x1, $y1 - $this->spacepadding + 1, $this->im_rect_bordercolor);

          $offset += $rw + $this->spacepadding + 1;
        }
        if ($startx == 0)
        {
          $startx = $x1;
        }
        $endx = $x1;
      }
      $top -= $this->spacepadding;
      imageline($this->im, $startx, $top, $endx, $top, $this->im_rect_bordercolor);
    }
    
    function __GetMaxWidth(&$arr)
    {
      $c = 0;
      foreach ($arr as $k => $v)
      {
        if ($c > 0)
        {
          $c += $this->spacepadding + 1;
        }
        if (is_array($v))
        {
          $n = $this->__GetMaxWidth($v);
          if ($n > (2 * $this->inpadding) + (imagefontwidth($this->font) * strlen($k)))
          {
            $c += $n;
          }
          else
          {
            $c += (2 * $this->inpadding) + (imagefontwidth($this->font) * strlen($k));
          }
        }
        else
        {
          $c += (2 * $this->inpadding) + (imagefontwidth($this->font) * strlen($v));
        }
      }
      return $c;
    }
    
    function __GetMaxDeepness(&$arr)
    {
      $p = 0;
      foreach ($arr as $k => $v)
      {
        if (is_array($v))
        {
          $r = $this->__GetMaxDeepness($v);
          if ($r > $p)
          {
            $p = $r;
          }
        }
      }
      return ($p + 1);
    }
    
    function __Rectangle($x1, $y1, $x2, $y2, $color, $bgcolor)
    {
      imagerectangle($this->im, $x1, $y1, $x2, $y2, $color);
      imagefilledrectangle($this->im, $x1 + 1, $y1 + 1, $x2 - 1, $y2 - 1, $bgcolor);
    }
    
    function __AllocateColor($var, $color, $alpha = true)
    {
      $alpha = ($alpha ? $this->alpha : 0);
      $this->$var = imagecolorallocatealpha($this->im, $color[0], $color[1], $color[2], $alpha);
    }
  }
?>
