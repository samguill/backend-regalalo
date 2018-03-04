import React from 'react';
import {observer,inject} from 'mobx-react';
import {Link} from 'react-router-dom'
import "./home.css"
import * as swal from 'bootstrap-sweetalert'
import Tooltip from 'react-tooltip-component'
// JVA - 01-09-2017
// Componente de React para paginacion
export default inject("store")(observer(class PaginatorTable extends React.Component {

  constructor(props) {
    super(props);
    // Ligado de funciones con el contexto
    this.init=this.init.bind(this);
    this.updateItemsPerPage=this.updateItemsPerPage.bind(this);
    this.updateCurrentPage=this.updateCurrentPage.bind(this);
    this.updateCurrentElement=this.updateCurrentElement.bind(this);
    this.deleteElement=this.deleteElement.bind(this);
    this.nextPage=this.nextPage.bind(this);
    this.previousPage=this.previousPage.bind(this);
    this.handleCustomAction=this.handleCustomAction.bind(this);
    // Url del proyecto
    this.url=this.props.url;
    this.actions=this.props.actions;
    // Indices de los valores en los objetos que retorna laravel
    this.state={
        keys:this.props.keys,
        headers:this.props.headers,
        itemsPerPage:15,
        currentPage:0,pageCount:1,
        is_loading: false,
        id_loading: null
    }
    this.init();
  }
  // JVA - 01-09-2017
  // Inicializar las variables segun la configuración recibida
  init(){
    // Se setean valores por default
    if(this.props.fields)
    {
        this.fields=this.props.fields;
    }else{
      this.fields=null;
    }
  }
  updateItemsPerPage(e){
    this.props.store.params.set('items-per-page',e.target.value)
  }
  updateCurrentPage(v)
  {
    this.setState({currentPage:v});
  }
  nextPage(active)
  {
    if(active)
    this.setState({currentPage:this.state.currentPage+1});
  }
  previousPage(active){
    if(active)
    this.setState({currentPage:this.state.currentPage-1});
  }
  updateCurrentElement(row){
    this.props.store.updateCurrentElement(row);
  }
    handleCustomAction(name,action,item){
        this.setState({is_loading:true, id_loading:item.id});
        var params={};
        action.params.map((p)=>{params[p]=item[p]});
        axios.post(action.url,params).then((r)=>{
        if(r.data.status=='ok')
        {
          swal({  title: "Operación Exitosa",
                  text: "La operación se ha realizado con éxito.",
                  type: "success"});
            this.setState({is_loading:false, id_loading:null});
              this.props.store.updateElement(r.data.data);
              this.props.store.callStatus.set("updated",true)
        }
        if(r.data.status=='error')
        {
          swal({  title: "Ha ocurrido un error al Eliminar",
                  text: "Por favor intente una vez más.",
                  type: "error"})
            this.setState({is_loading:false, id_loading:null});
        }
    }).catch((e)=>{
      swal({  title: "Ha ocurrido un error al Eliminar",
              text: "Por favor intente una vez más.",
              type: "error"})
            this.setState({is_loading:false, id_loading:null});

    })
  }
  deleteElement(row){
    swal({
            title: "¿Estás seguro?",
            text: "Si eliminas este item no podrás recuperarlo",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, eliminar",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },()=>{
            axios.post(this.props.actions.delete.url,row).then((r)=>{
                if(r.data.status=='ok')
                {
                  swal({  title: "Eliminación Exitosa",
                          text: "El elemento ha sido eliminado de manera exitosa.",
                          type: "success"});
                      this.props.store.deleteElement(r.data.data);
                      this.props.store.callStatus.set("deleted",true)
                }
                if(r.data.status=='error')
                {
                  swal({  title: "Ha ocurrido un error al Eliminar",
                          text: "Por favor intente una vez más.",
                          type: "error"})
                }
            }).catch((e)=>{
              swal({  title: "Ha ocurrido un error al Eliminar",
                      text: "Por favor intente una vez más.",
                      type: "error"})

            })
          });


  }
  render() {
    return (<div className="col-md-12">
              <div className="card">
                  <div className="card-body">
                    <div className="fluid align-right">
                        <button onClick={()=>{this.props.store.updateData(this.props.url)}} className={"btn btn-success loading-button mb-20 "+(this.props.store.isDataUpdating.get()?"animcolor":"")} >
                            <em style={{color:"white", marginRight: 0}} className={"fa fa-refresh"}></em>
                        </button>
                    </div>
                    {this.props.store.isDataLoading.get()?
                        <div className="loading-container">
                            <div className="icon-load">
                                <i className="fa fa-circle-o-notch fa-spin"></i>
                            </div>
                        </div>
                        :<table className="table" style={{background:"white"}}>
                          <thead>
                            <tr>
                                {this.props.headers.map((k,i)=><th key={i} style={{'cursor':'pointer'}} onClick={()=>{this.props.store.updateSortBy(this.props.keys[i])}} >{k+" "+(this.props.keys[i]==this.props.store.params.get('sort-by')?(this.props.store.params.get("sort-asc")?"▼":"▲"):"")} </th>)}
                                {(Object.keys(this.actions).filter((name,i)=>["create",'custom'].indexOf(name)<0).length+Object.keys(this.actions["custom"]).length)>0?<th  key={"act"} style={{'cursor':'pointer',width:(Object.keys(this.actions).length+Object.keys(this.actions['custom']).length)*40+"px"}}>Acciones</th>:null}
                            </tr>
                          </thead>
                          <tbody>
                              {this.props.store.data.get().length<1?<tr><td><h5>No se encontraron datos.</h5></td></tr>:this.props.store.data.get()
                                .slice(this.state.currentPage*this.props.store.params.get('items-per-page'),(this.state.currentPage+1)*this.props.store.params.get('items-per-page'))
                                .map((row,ri)=>{
                                   return <tr key={ri} >{
                                          this.props.keys.map((cell,ci)=>{
                                                if(this.fields[cell].renderAs=="text")
                                                {

                                                  if(["text","date","file"].indexOf(this.fields[cell].type)>=0)
                                                  {
                                                    if(this.fields[cell].type=="date")
                                                    {
                                                      let m=moment(row[cell])
                                                      let fdate=m.format("DD-MM-Y");
                                                      return <td key={ci} >{fdate}</td>
                                                    }
                                                    return <td key={ci} >{row[cell]}</td>
                                                  }else if(this.fields[cell].type=="map"){
                                                    let options=this.fields[cell].options;
                                                    let currentOption=options.filter((o)=>o.id==row[cell]);
                                                    if(currentOption.length>0)
                                                    return <td key={ci} >{currentOption[0].value}</td>
                                                    else
                                                    return <td key={ci} >{row[cell]}</td>
                                                  }else if(this.fields[cell].type=="json")
                                                  {
                                                    var json=JSON.parse(row[cell]);
                                                    var v="";
                                                    let options=this.fields[cell].options;

                                                    var r=Object.keys(json).map((key,i)=>{
                                                        let currentOption=options.filter((o)=>o.id==json[key]);
                                                        if(currentOption.length>0)
                                                        return <li key={key}>{currentOption[0].value}</li>;
                                                        else{
                                                        return <li key={key}>{json[key]}</li>
                                                        }
                                                    })
                                                    return <td key={ci} ><ul className="react-crud-list">{r}</ul></td>
                                                  }

                                                }

                                                if(this.fields[cell].renderAs=="icon")
                                                {
                                                  if(this.fields[cell].renderParams.filter((item)=>item.value==row[cell]).length>0)
                                                  return <td key={ci} >{<em className={"fa fa-"+this.fields[cell].renderParams.filter((item)=>item.value==row[cell])[0].icon}></em>}</td>
                                                  else
                                                  return <td key={ci} >{<em className={"fa fa-circle-o"}></em>}</td>
                                                }

                                          })
                                        }
                                        { (Object.keys(this.actions).length>0)?
                                          (<td>
                                            {
                                              Object.keys(this.actions).indexOf('view')>=0?
                                                <Tooltip title={'Ver'} position={"top"}>
                                                  <Link to="view" onClick={(e)=>{this.updateCurrentElement(row)}}>
                                                    <button type="button"  className="btn btn-view btn-sm" style={{margin:"2px"}} >
                                                      <em className="fa fa-eye"></em>
                                                    </button>
                                                  </Link>
                                                </Tooltip>:null}
                                            {Object.keys(this.actions).indexOf('update')>=0?
                                              <Tooltip title={'Editar'} position={"top"}>
                                                <Link to="update" onClick={(e)=>{this.updateCurrentElement(row)}}>
                                                  <button type="button" className="btn btn-edit btn-sm" style={{margin:"2px"}} >
                                                    <em className="fa fa-edit"></em>
                                                  </button>
                                                </Link>
                                              </Tooltip>
                                              :null}
                                            {Object.keys(this.actions).indexOf('delete')>=0?
                                              <Tooltip title={'Eliminar'} position={"top"}>
                                                <button type="button" onClick={(e)=>{this.deleteElement(row)}} className="btn btn-danger btn-sm" style={{margin:"2px"}}>
                                                  <em className="fa fa-trash"></em>
                                                </button>
                                              </Tooltip>
                                              :null}
                                            {Object.keys(this.actions).indexOf('custom')>=0?Object.keys(this.actions.custom).map((custom_action,ri)=>{
                                              var action=this.actions.custom[custom_action];
                                              var condition=null;
                                              var condition_result=true;
                                              if(action.condition)
                                              {
                                                condition=action.condition;
                                                condition_result='"'+row[condition.field] + '"' + condition.operator + "=" + '"' + condition.value+'"';
                                                if([">","<","="].indexOf(condition.operator)>=0)
                                                condition_result=eval(condition_result);
                                                if(["in"].indexOf(condition.operator)>=0)
                                                condition_result=condition.value.indexOf(row[condition.field])>=0;

                                              }
                                              if(condition_result)
                                              {
                                                if(action.link)
                                                return <Tooltip key= {ri} title={action.title} position={"top"}>
                                                    <a key={custom_action} href={action.url+"?"+action.params.map((p)=>{ return p+"="+encodeURI(row[p])}).join("&")}  className="btn btn-primary btn-sm" style={{cursor:"pointer",margin:"2px",color:"white",background:action.color,borderColor:action.color}} ><em className={"fa fa-"+action.icon}></em></a>
                                                  </Tooltip>
                                                  else
                                                return <Tooltip key= {ri} title={action.title} position={"top"}>
                                                <a key={custom_action} onClick={(e)=>{this.handleCustomAction(custom_action,action,row)}}  className="btn btn-primary btn-sm" style={{cursor:"pointer",margin:"2px",color:"white",background:action.color,borderColor:action.color}} >{ (this.state.is_loading && this.state.id_loading == row.id) ? <em className="fa fa-refresh fa-spin"></em> : <em className={"fa fa-"+action.icon}></em>}</a>
                                                </Tooltip>
                                              }
                                              return <Tooltip key= {ri} title={action.title} position={"top"}>
                                                <a key={custom_action}  className="btn btn-primary btn-sm" style={{margin:"2px",color:"white",background:"#c7c6c6",borderColor:"#c7c6c6"}}><em className={"fa fa-"+action.icon}></em></a>
                                              </Tooltip>
                                            }):null}
                                          </td>):null
                                        }
                                        </tr>
                              })}
                          </tbody>
                        </table>
                      }
                  </div>
              </div>

            <div className="row mt-10">
                <div className="col-md-6">
                    <nav aria-label="Page navigation">
                        <ul className="pagination">
                            <li className={"page-item "+(this.state.currentPage>0?" ":"disabled")} onClick={()=>{this.previousPage(this.state.currentPage>0)}}><a className="page-link" href="#" >Anterior</a></li>
                            {
                                this.props.store.pageCount.get()>0?
                                    _.range(this.state.currentPage>7?this.state.currentPage-7:0,this.state.currentPage>7?(this.state.currentPage+7>this.props.store.pageCount?this.props.store.pageCount:this.state.currentPage+7):(this.props.store.pageCount<14?this.props.store.pageCount:15)).map((v,i)=>{
                                        return <li key={i} className={"page-item "+(this.state.currentPage==v?"active":"")} onClick={()=>{this.updateCurrentPage(v)}}><a className="page-link" href="#">{parseInt(v)+1} <span className="sr-only"></span></a></li>
                                    }):""
                            }
                            <li className={"page-item "+(this.state.currentPage+1)<this.props.store.pageCount?"disabled":""} onClick={()=>{this.nextPage((this.state.currentPage+1)<this.props.store.pageCount)}}><a className="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
                <div className="col-md-6 " style={{textAlign:"right"}}>
                    <div className="form-inline" style={{float:"right"}}>
                        <label htmlFor="paginator-page-number" >Items por página </label>
                        <input  id="paginator-page-number" className="form-control"  type="number" value={this.props.store.params.get('items-per-page')} onChange={this.updateItemsPerPage} />
                    </div>
                </div>
            </div>

              </div>
);
  }
}))
