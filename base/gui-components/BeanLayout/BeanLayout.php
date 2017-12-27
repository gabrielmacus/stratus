<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 27/12/2017
 * Time: 02:30 PM
 */
class BeanLayout implements IView,JsonSerializable
{

    use Magic;

    protected $title;
    protected $metaTags;
    protected $content;

    /**
     * BeanLayout constructor.
     * @param $title
     * @param $metaTags
     * @param $content
     */
    public function __construct($title, $metaTags,IView $content)
    {
        $this->title = $title;
        $this->metaTags = $metaTags;
        $this->content = $content->getHTML();
    }
    public function getTplSrc()
    {
        return dirname(__FILE__)."/layout.tpl";
    }


    public function getHTML()
    {


        $core = new Dwoo\Core();


        $html =$core->get($this->getTplSrc(),$this->jsonSerialize());

        return $html;

    }

    function jsonSerialize()
    {

        $data =[];
        foreach ($this as $k=>$v)
        {
            $data[$k] = $v;
        }

        return $data;
    }
}