<?php
class upimages {
        var $annexfolder = "uploadimgs";//附件存放点，默认为：annex
        var $smallfolder = "small";//缩略图存放路径，注：必须是放在 $annexfolder下的子目录，默认为：smallimg
        var $markfolder = "mark";//水印图片存放处
        var $upfiletype = "jpg gif png jpeg";//上传的类型，默认为：jpg gif png rar zip
        var $upfilemax = 102400;//上传大小限制，单位是"kb"，默认为：1024kb
        var $fonttype = 'georgiaz.ttf';//字体
        var $maxwidth = 400; //图片最大宽度
        var $maxheight = 400; //图片最大高度
        var $tofile = true; //是否覆盖存在的图片
       /* function upimages($annexfolder,$smallfolder,$includefolder) {
                $this->annexfolder = $annexfolder;
                $this->smallfolder = $smallfolder;
                $this->fonttype = $includefolder."/04b_08__.ttf";
        }*/
        function upimages($annexfolder,$smallfolder) {
                if($annexfolder){
                $this->annexfolder = $annexfolder;
                }
                if($smallfolder){
                $this->smallfolder = $smallfolder;
                }
        }
        function upload($inputname) {
                if(!is_dir($this->annexfolder)){
                    mkdir($this->annexfolder, 0700);
                }
                $imagename = time();//设定当前时间为图片名称
                if(@empty($_FILES[$inputname]["name"])) die("没有上传图片信息，请确认");
                $name = explode(".",$_FILES[$inputname]["name"]);//将上传前的文件以"."分开取得文件类型
                $imgcount = count($name);//获得截取的数量
                $imgtype = $name[$imgcount-1];//取得文件的类型
                if(strpos($this->upfiletype,$imgtype) === false) die("上传文件类型仅支持 ".$this->upfiletype." 不支持 ".$imgtype);
                $photo = $imagename.".".$imgtype;//写入的文件名
                $uploadfile = $this->annexfolder."/".$photo;//上传后的文件名称
                $upfileok = move_uploaded_file($_FILES[$inputname]["tmp_name"],$uploadfile);
                if($upfileok) {
                        $imgsize = $_FILES[$inputname]["size"];
                        $ksize = round($imgsize/1024);
                        if($ksize > ($this->upfilemax*1024)) {
                                @unlink($uploadfile);
                                die(error("上传文件超过 ".$this->upfilemax."kb"));
                        }
                } else {
                        die("上传图片失败，请确认你的上传文件不超过 $upfilemax kb 或上传时间超时");
                }
                return $photo;
        }
        //获取图片信息
        function getinfo($photo) {
                $photo = $this->annexfolder."/".$photo;
                $imageinfo = getimagesize($photo);
                $imginfo["width"] = $imageinfo[0];
                $imginfo["height"] = $imageinfo[1];
                $imginfo["type"] = $imageinfo[2];
                $imginfo["name"] = basename($photo);
                return $imginfo;
        }
        //缩略图
        function smallimg($photo,$width=128,$height=128) {
                $imginfo = $this->getinfo($photo);
                $photo = $this->annexfolder."/".$photo;//获得图片源
                $newname = substr($imginfo["name"],0,strrpos($imginfo["name"], "."))."_thumb.jpg";//新图片名称
                if($imginfo["type"] == 1) {
                        $img = imagecreatefromgif($photo);
                } elseif($imginfo["type"] == 2) {
                        $img = imagecreatefromjpeg($photo);
                } elseif($imginfo["type"] == 3) {
                        $img = imagecreatefrompng($photo);
                } else {
                        $img = "";
                }
                if(empty($img)) return false;
                $width = ($width > $imginfo["width"]) ? $imginfo["width"] : $width;
                $height = ($height > $imginfo["height"]) ? $imginfo["height"] : $height;
                $srcw = $imginfo["width"];
                $srch = $imginfo["height"];
                if ($srcw * $width > $srch * $height) {
                        $height = round($srch * $width / $srcw);
                } else {
                        $width = round($srcw * $height / $srch);
                }
                if (function_exists("imagecreatetruecolor")) {
                        $newimg = imagecreatetruecolor($width, $height);
                        imagecopyresampled($newimg, $img, 0, 0, 0, 0, $width, $height, $imginfo["width"], $imginfo["height"]);
                } else {
                        $newimg = imagecreate($width, $height);
                        imagecopyresized($newimg, $img, 0, 0, 0, 0, $width, $height, $imginfo["width"], $imginfo["height"]);
                }
                if ($this->tofile) {
                       if(!is_dir($this->annexfolder."/".$this->smallfolder)){
                          mkdir($this->annexfolder."/".$this->smallfolder, 0700);
                       }
                        if (file_exists($this->annexfolder."/".$this->smallfolder."/".$newname)) @unlink($this->annexfolder."/".$this->smallfolder."/".$newname);
                        imagejpeg($newimg,$this->annexfolder."/".$this->smallfolder."/".$newname);
                        return $this->annexfolder."/".$this->smallfolder."/".$newname;
                } else {
                        imagejpeg($newimg);
                }
                imagedestroy($newimg);
                imagedestroy($img);
                return $newname;
        }

        //加水印
        function watermark($photo,$text) {
                $imginfo = $this->getinfo($photo);
                $photo = $this->annexfolder."/".$photo;
                $newname = substr($imginfo["name"], 0, strrpos($imginfo["name"], ".")) . "_mark.jpg";
                switch ($imginfo["type"]) {
                        case 1:
                                $img = imagecreatefromgif($photo);
                        break;
                        case 2:
                                $img = imagecreatefromjpeg($photo);
                        break;
                        case 3:
                                $img = imagecreatefrompng($photo);
                        break;
                        default:
                                return false;
                }
                if (empty($img)) return false;
                $width = ($this->maxwidth > $imginfo["width"]) ? $imginfo["width"] : $this->maxwidth;
                $height = ($this->maxheight > $imginfo["height"]) ? $imginfo["height"] : $this->maxheight;
                $srcw = $imginfo["width"];
                $srch = $imginfo["height"];
                if ($srcw * $width > $srch * $height) {
                        $height = round($srch * $width / $srcw);
                } else {
                        $width = round($srcw * $height / $srch);
                }
                if (function_exists("imagecreatetruecolor")) {
                        $newimg = imagecreatetruecolor($width, $height);
                        imagecopyresampled($newimg, $img, 0, 0, 0, 0, $width, $height, $imginfo["width"], $imginfo["height"]);
                } else {
                        $newimg = imagecreate($width, $height);
                        imagecopyresized($newimg, $img, 0, 0, 0, 0, $width, $height, $imginfo["width"], $imginfo["height"]);
                }

                $white = imagecolorallocate($newimg, 255, 255, 255);
                $black = imagecolorallocate($newimg, 0, 0, 0);
                $alpha = imagecolorallocatealpha($newimg, 230, 230, 230, 40);
                imagefilledrectangle($newimg, 0, $height-26, $width, $height, $alpha);
                imagefilledrectangle($newimg, 13, $height-20, 15, $height-7, $black);
                //imagettftext($newimg, 100, 0, 20, $height-14, $black, $this->fonttype, $text[0]);
                imagettftext($newimg, 100, 0, 20, $height-6, $alpha, $this->fonttype, $text[1]);
                if($this->tofile) {
                    if(!is_dir($this->annexfolder."/".$this->markfolder)){
                          mkdir($this->annexfolder."/".$this->markfolder, 0700);
                       }
                   if (file_exists($this->annexfolder."/".$this->markfolder."/".$newname)) @unlink($this->annexfolder."/".$this->markfolder."/".$newname);
                    imagejpeg($newimg,$this->annexfolder."/".$this->markfolder."/".$newname);
                     return $this->annexfolder."/".$this->markfolder."/".$newname;
                } else {
                        imagejpeg($newimg);
                }
                imagedestroy($newimg);
                imagedestroy($img);
                return $newname;
        }
}

