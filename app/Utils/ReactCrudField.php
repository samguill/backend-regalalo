<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 20:51
 */

namespace App\Utils;


class ReactCrudField
{

    public $name="";
    public $field=[];
    public function __construct($name){
        $this->name=$name;
        $this->field[$name]=['title'=>$name];
    }
    public function title($title){
        $this->field[$this->name]['title']=$title;
        return $this;
    }

    public function required(){
        $this->field[$this->name]['required']=true;
        return $this;
    }
    public function type($type,$options=[]){
        $this->field[$this->name]['type']=$type;
        if(in_array($type,['map','json']))
        {
            $this->field[$this->name]['options']=$options;
        }
        if($type=="json")
        {
            $this->field[$this->name]['multiple']=true;
        }

        return $this;
    }
    public function default($v)
    {
        $this->field[$this->name]['default']=$v;
        return $this;
    }
    public function verbose()
    {
        $this->field[$this->name]['verbose']=true;
        return $this;
    }
    // text,number,icon,money
    // Cuando el tipo es text no necesita parametros
    // Cuando el tipo es number recibe el parametro, decimals
    // Cuando el tipo es money, recibe el parametro, sign (simbolo de moneda) y el booleano end que define si va al final o al inicio
    // Cuando es icon, recibe un arreglo [[value=>valor original,icon=>nombre del icono segun fontawesome]]
    public function renderAs($value,$params=[]){

        $this->field[$this->name]['renderAs']=$value;
        $this->field[$this->name]['renderParams']=$params;
        return $this;
    }
    public function fillable($v=true){
        $this->field[$this->name]['fillable']=$v;
        return $this;
    }
    public function width($v){
        $this->field[$this->name]['width']=$v;
        return $this;
    }
    public function show($v){
        $this->field[$this->name]['show']=$v;
        return $this;
    }
    public function get(){
        return $this->field;
    }

}