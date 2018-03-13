import React  from 'react';
import DatePicker from 'react-datepicker';
import moment from 'moment';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert'
import * as validator from 'validate.js'
import 'react-datepicker/dist/react-datepicker.css';
import 'react-select/dist/react-select.css';
import 'bootstrap-sweetalert/dist/sweetalert.css'
import {inject,observer} from 'mobx-react';
import {Redirect} from 'react-router-dom';
moment.locale("es")
export default inject('store')(observer(class AutoForm extends React.Component {
  constructor(props) {
    super(props);
    var initialState={};
    this.fields=props.fields;
    this.handleFieldChange=this.handleFieldChange.bind(this)
    this.prepareFields=this.prepareFields.bind(this)
    this.clearErrors=this.clearErrors.bind(this)
    this.clearSearch=this.clearSearch.bind(this);
    var keys=Object.keys(this.fields);
    keys.map((k)=>{
      initialState[k]="";
    }
    );
    this.state = initialState;

  }
  componentDidMount(){
    var fieldUpdate={}
    Object.keys(this.fields).map((field_name)=>{
        let field=this.fields[field_name];
        if(field.type=="date")
        {
          fieldUpdate[field_name]=moment()
        }
        if(field.default!=null)
        {
            fieldUpdate[field_name]=field.default;
        }
      })
      this.setState(fieldUpdate)
  }
  clearSearch(){
    var fieldUpdate={}
    this.props.store.advancedFilters.keys().map((field_name)=>{
      let field=this.fields[field_name];
      if(field.type=="date")
      {
        fieldUpdate[field_name]=moment()
      }
      if(field.default!=null)
      {
          fieldUpdate[field_name]="";
      }
      fieldUpdate[field_name]="";

    })
    this.setState(fieldUpdate)
    this.props.store.advancedFilters.clear();

  }
  handleFieldChange(k,v){
    let fieldUpdate={}
    fieldUpdate[k]=v
    this.props.store.advancedFilters.set(k,v);
    this.setState(fieldUpdate);
  }
  handleSubmit(){
    let prepared=this.prepareFields();
    var params=prepared.params;
    this.clearErrors();
    var constraints=prepared.constraints;
    var errors=validator(params,constraints,{format: "detailed"});
    if(errors)
    {
          swal({  title: "Se encontraron errores en el formulario",
                  text: "Por favor corrija los errores para poder continuar",
                  type: "error"})
          errors.map((e)=>{
          var ufield=this.fields[e.attribute]
          ufield.error={message:e.options.message};
          this.setState(ufield);
        })
        return false;
    }else{
      this.clearErrors();
      axios.post(this.props.url,params).then((r)=>{
          if(r.data.status=="ok")
          {
            swal({  title: "Operación Exitosa",
                    text: "Se ha creado el registro.",
                    type: "success"});
                    this.props.store.callStatus.set('created',true);
                    this.props.store.addElement(r.data.data);
          }
      }).catch((e)=>{
        swal({  title: "Ha ocurrido un error",
                text: "Por favor intenta de nuevo.",
                type: "error"})

      });
    }
    return false;
  }
  clearErrors(){
    var ufields=  Object.keys(this.fields).map((k)=>{
            let field=this.fields[k];
            field.error=null;
          })
    this.setState(ufields);
  }
  prepareFields(){
    var params={};
    var constraints={};
    Object.keys(this.fields).map((k)=>{
          let field=this.fields[k];
          constraints[k]={};
          if(field.type=="text")
          {
            params[k]=this.state[k];
          }
          if(field.type=="hidden")
          {
            params[k]=this.state[k];
          }
          if(field.type=="email")
          {
            params[k]=this.state[k];
            constraints[k]=Object.assign(constraints[k],{email:{message:field.title+" no es un correo válido."}});
          }
          if(field.type=="map")
          {
            params[k]=this.state[k];
          }
          if(field.type=="json")
          {
            params[k]=this.state[k];
          }
          if(field.type=="date")
          {
            params[k]=this.state[k].format("Y-MM-DD");

          }
          if(field.required){
            constraints[k]= Object.assign(constraints[k],{
              presence:{message:field.title+" es requerido"},
            })
          }


    })
    return {params:params,constraints:constraints}
  }

  render() {
    if(this.props.store.callStatus.get('created'))
    {
      return <Redirect to="/" />
    }
    return (<div style={{background:"#e4e6e6",padding:"5px",paddingTop:"20px",marginBottom:"10px",borderRadius:"3px"}}>
        <div className="row" style={{width: "100%"}}>
        {
          Object.keys(this.fields).filter((field_name)=>this.fields[field_name].fillable).map((k,i)=>{
              var field=this.fields[k];
              if(field.type=="hidden")
              {
                return <input type="hidden" key={k}  value={this.state[k]} />
              }
              if(['text','email','phone','money'].indexOf(field.type)>=0)
              {
                if(!field.verbose)
                {
                  return (<div key={i} className={"form-group col-md-4"}>
                            {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                            <input id={k}  className={"form-control "+(field.error!=null?"error":"")} type="text" placeholder={field.title} value={this.state[k]} onChange={(e)=>{this.handleFieldChange(k,e.target.value)}}/>
                            {field.error!=null?<p className="error_message">{field.error.message}</p>:""}
                          </div>)
                }else{
                  return (<div key={i} className={"form-group col-md-"+field.width}>
                            {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                            <textarea id={k}  className={"form-control "+(field.error!=null?"error":"")} rows="3" type="text" placeholder={field.title} value={this.state[k]} onChange={(e)=>{this.handleFieldChange(k,e.target.value)}} />
                            {field.error!=null?<p className="error_message">{field.error.message}</p>:""}
                          </div>)
                }

              }
              if(field.type=="map"||field.type=="json")
              {

                return (<div key={i} className={"form-group col-md-"+field.width}>
                          {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                          <Select
                                    value={this.state[k]}
                                    multi={field.multiple}
                                    className={"form-control "+(field.error!=null?"error":"")}
                                    options={field.options.map((opt,i)=>{
                                        return {label:opt.value,value:opt.id}
                                      })}
                                      onChange={(v)=>{
                                        if(field.multiple)
                                        this.handleFieldChange(k,v.map((v)=>v.value))
                                        else
                                        this.handleFieldChange(k,v.value)

                                      }}

                                      />
                                      {field.error!=null?<p className="error_message">{field.error.message}</p>:""}

                        </div>)
              }
              if(field.type=="date")
              {
                  return (<div key={i} className={"form-group col-md-"+field.width}>
                            {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                            <DatePicker id={k} className={"form-control "+(field.error!=null?"error":"")}
                                  selected={this.state[k]}
                                  onChange={(moment)=>{
                                    this.handleFieldChange(k,moment)
                                  }}
                              />
                              {field.error!=null?<p className="error_message">{field.error.message}</p>:""}
                          </div>)
              }

          })
        }
        </div>
        <div className="form-group col-md-12">
            <a className="btn btn-danger" onClick={this.clearSearch} style={{color:"white",'cursor':"pointer"}}>Limpiar</a>
        </div>

      </div>);
  }
}
))
