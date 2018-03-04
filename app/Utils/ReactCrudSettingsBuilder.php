<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 20:52
 */

namespace App\Utils;


class ReactCrudSettingsBuilder
{

    private $fields=[];
    private $actions=[];
    private $search=false;
    private $download=false;
    private $buttons =[];
    public function __construct()
    {
        $field=new ReactCrudField('id');
        $field->fillable()->show(false)->type('hidden');
        $this->fields=array_merge($this->fields,$field->fillable()->show(false)->type('hidden')->get());
        $this->actions=['custom'=>[]];
        return $this;
    }
    public function addField(ReactCrudField $field)
    {
        $this->fields=array_merge($this->fields,$field->get());
        return $this;
    }
    public function setActions($actions){
        $this->actions=array_merge($this->actions,$actions);
        return $this;
    }
    public function setDefaultActions()
    {
        $this->actions=['view','update','delete','create'];
    }
    public function get(){
        return json_encode(['fields'=>$this->fields,'actions'=>$this->actions,'search'=>$this->search,'download'=>$this->download,'buttons'=>$this->buttons]);
    }
    public function advancedSearch(){
        $this->search=true;
    }
    public function downloadable(){
        $this->download = true;
    }
    public function addButton($text,$onclick,$classname,$icon=""){
        $btn = [];
        $btn['text']=$text;
        $btn['onclick']=$onclick;
        $btn['classname']=$classname;
        $btn['icon']=$icon;
        $this->buttons = array_merge($this->buttons,[$btn]);
    }

}