<?php

class Zwe_View_Helper_Import_Text extends Zwe_View_Helper_Import
{
    protected $_exists = array();

    protected $_fontDirectory = 'fonts';

    protected $_font = null;
    protected $_fontSize = 24;
    protected $_color = array(0xCF, 0xA8, 0x71);
    protected $_backgroundColor = array(0xFF, 0xFF, 0xFF);

    public function __construct()
    {
        parent::__construct();

        $this->_directory = 'images/text';
    }

    public function import_Text($Text, array $Options = array())
    {
        $Hash = md5($Text . print_r($Options, 1));
        $ImagePath = $this->_baseurl . $this->_directory . '/' . $Hash . '.png';
        $RealPath = $Options['path'] = PUBLIC_PATH . '/' . $ImagePath;

        if(!isset($this->_exists[$Hash]) && !file_exists($RealPath))
        {
            $this->_createImg($Text, $Options);
            $this->_exists[$Hash] = true;
        }

        return $this->view->import_Img($ImagePath, array('alt' => $Text) + (isset($Options['img']) ? $Options['img'] : array()), true);
    }

    protected function _createImg($Text, array $Options = array())
    {
        if(isset($Options['font']))
            $this->_font = PUBLIC_PATH . '/' . $this->_fontDirectory . '/' . $Options['font'];
        else
            throw new Exception("A font name must be specified");

        if(!file_exists($this->_font))
            throw new Exception("Font file does not exist");

        if(isset($Options['fontSize']))
            $this->_fontSize = $Options['fontSize'];

        if(isset($Options['color']))
            $this->_color = $Options['color'];

        if(isset($Options['backgroundColor']))
            $this->_backgroundColor = $Options['backgroundColor'];

        $Transparent = isset($Options['transparent']) && $Options['transparent'];

        $Size = imagettfbbox($this->_fontSize, isset($Options['angle']) ? $Options['angle'] : 0, $this->_font, $Text);
        $Width = abs($Size[2] - $Size[0]);
        $Height = abs($Size[7] - $Size[1]);

        $Image = imagecreatetruecolor($Width, $Height);
        $BGColor = imagecolorallocate($Image, $this->_backgroundColor[0], $this->_backgroundColor[1], $this->_backgroundColor[2]);
        $Color = imagecolorallocate($Image, $this->_color[0], $this->_color[1], $this->_color[2]);

        imagefill($Image, 0, 0, $Transparent ? IMG_COLOR_TRANSPARENT : $BGColor);
        imagesavealpha($Image, true);
        imagealphablending($Image, false);
        imagettftext($Image, $this->_fontSize, isset($Options['angle']) ? $Options['angle'] : 0, 0, $Height - 2, $Color, $this->_font, $Text);
        imagepng($Image, $Options['path']);
        imagedestroy($Image);
    }

    protected function doImport($File)
    {
        # do nothing
    }
}