import React from 'react';
import PaginatorTable from './PaginatorTable'
import {Link} from 'react-router-dom';
import Search from '../search/search';
import DownloadExcel from "./DownloadExcel";
import {observer,inject} from 'mobx-react'
export default inject("store")(observer(class CrudHome extends React.Component {
  constructor(props) {
    super(props);
    this.props.store.setDefinition(this.props.fields);
    this.init=this.init.bind(this);
    this.buildHeaders=this.buildHeaders.bind(this);
    this.updateSearch=this.updateSearch.bind(this);
    this.state={data:[],keys:[],headers:[]};
    this.init();
  }
  init(){
    this.url=this.props.url;
    if(this.props.fields)
    {
        this.fields=this.props.fields;
    }else{
      this.fields=null;
    }
    if(this.props.actions)
    {
        this.actions=this.props.actions;
    }else{
      this.actions=['create','update','delete'];
    }
    this.props.store.getData(this.url).then((data)=>{
      this.buildHeaders(this.fields!=null,data);
    });
    this.props.store.callStatus.set('created',false);
    this.props.store.callStatus.set('updated',false);
  }
  // JVA - 01-09-2017
  // Estructurar la cabecera de la tabla segun el arreglo de configuracion, si existiese
  buildHeaders(existsFieldsArray,data){
      this.header=[];
      // Si existe configuracion se usa ese arreglo para construir la tabla
      if(existsFieldsArray && data.length>0)
      {
          var fieldKeys=Object.keys(this.fields).filter((k)=>{return this.fields[k].show});
          this.setState({keys:fieldKeys});
          this.setState({headers:fieldKeys.map((k)=>{
              if(this.fields[k]['title'])
              {
                return this.fields[k]['title']
              }
              return k;
          })});
      }else{
          // Si no existe configuracion se imprimen todos los campos de la tabla
          if(data.length>0)
          {
            this.setState({keys:Object.keys(data[0])});
            this.setState({headers:Object.keys(data[0])});
          }

      }
      this.props.store.updateFields(this.state.keys);
  }
  updateSearch(e){
    this.props.store.updateSearch(e.target.value);
  }
  render() {
    return (<div>
              {this.props.search?<div className="row">
                  <div className="col-12"><Search fields={this.fields} /></div>
               </div>:""
              }
        <div className="row mb-10">
              <div className="col-md-6">
                {Object.keys(this.actions).indexOf("create")>=0?<span><Link to="create" className="btn btn-primary" >Nuevo</Link>&nbsp;</span>:""}
                {this.props.buttons.map((button_def,k)=>{
                  return <span key={k}><button  className={"btn "+button_def.classname} onClick={()=>{eval(button_def.onclick)}}>{button_def.text}</button>&nbsp;</span>
                })}
                {this.props.download?<DownloadExcel fields={this.fields} headers={this.state.headers} />:""}

              </div>
              <div className="col-md-6"  style={{display:!this.props.store.params.get('show-advanced-search')?"inherit":"none"}}>
                <input className="form-control" placeholder="Búsqueda Rápida" value={this.props.store.searchField} onChange={this.updateSearch}/>
              </div>
        </div>

        <div className="row" >
                <PaginatorTable url={this.url} fields={this.fields} headers={this.state.headers} keys={this.state.keys} actions={this.actions}/>
          </div>
        </div>);
  }
}))
