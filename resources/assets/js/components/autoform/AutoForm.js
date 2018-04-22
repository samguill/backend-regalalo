import React  from 'react';
import DatePicker from 'react-datepicker';
import CKEditor from 'react-ckeditor-component';
import moment from 'moment';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert'
import * as validator from 'validate.js'
import 'react-datepicker/dist/react-datepicker.css';
import 'react-select/dist/react-select.css';
import 'bootstrap-sweetalert/dist/sweetalert.css'
moment.locale("es")
export default class AutoForm extends React.Component {
  constructor(props) {
    super(props);
    var initialState={};
    this.type = props.type;
    this.fields=props.fields;
    Object.keys(this.fields).map((field_name)=>{
        let field=this.fields[field_name];

        if(field.fillable==null)
        {
          field.fillable=true;
        }
        if(field.readOnly==null)
        {
          field.readOnly=false;
        }
        if(field.width==null)
        {
          field.width=12;
        }
        if(field.hidden==null)
        {
          field.hidden=false;
        }
        if(field.label==null)
        {
          field.label=true;
        }
        if(field.type==null)
        {
          field.type="text";
        }
        if(field.verbose==null)
        {
          field.verbose=false;
        }
        if(field.error==null)
        {
          field.error=null;
        }
        if(field.options==null)
        {
          field.options=[];
        }
        if(field.multiple==null)
        {
          field.multiple=false;
        }
        if(field.show==null)
        {
          field.show=true;
        }
        if(field.renderAs==null)
        {
          field.renderAs='text';
        }


        this.fields[field_name]=field;
    })
    this.handleFieldChange=this.handleFieldChange.bind(this)
    this.prepareFields=this.prepareFields.bind(this)
    this.clearErrors=this.clearErrors.bind(this)
    var keys=Object.keys(this.fields);
    keys.map((k)=>{
      initialState[k]="";
    }
    );
    this.state = {'is_loading': false};
    this.state = initialState;

  }
  componentDidMount(){
    var fieldUpdate={}
    Object.keys(this.fields).map((field_name)=>{
        let field=this.fields[field_name];
        if(field.type=="date")
        {
          fieldUpdate[field_name]=null
        }
        if(field.default!=null)
        {
            if(field.type=="date")
            {
              fieldUpdate[field_name]=moment(field.default);
            }else{
              fieldUpdate[field_name]=field.default;
            }


        }
      })
      this.setState(fieldUpdate)
  }
  handleFieldChange(k,v){
    let fieldUpdate={}
    fieldUpdate[k]=v
    this.setState(fieldUpdate);
  }
  handleSubmit(){
    let prepared=this.prepareFields();
    var params=prepared.params;
    console.log(params);
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
        this.setState({is_loading:true});
      axios.post(this.props.url,params).then((r)=>{
          if(r.data.status=="ok")
          {
              this.setState({is_loading:false});
            var object = r.data.data;

            swal({  title: "Operación Exitosa",
                    text: "Sus datos se han actualizado.",
                    type: "success"});
          }else if(r.data.status=="error"){
              this.setState({is_loading:false});
              swal({  title: "Ha ocurrido un error",
                  text: r.data.data,
                  type: "error"})
          }
      }).catch((e)=>{
          this.setState({is_loading:false});
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
            if(field.type=="editor") {
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
          if(field.type=="date")
          {
            if(this.state[k]!=null)
            params[k]=this.state[k].format("YYYY-MM-DD");

          }
          if(field.renderAs=="text"){
              params[k]=this.state[k].toString();
          }
          if(field.required){
            constraints[k]= Object.assign(constraints[k],{
              presence:{message:"El campo "+field.title+" es obligatorio"},
            })
          }


    })
    return {params:params,constraints:constraints}
  }

  render() {
    return (
      <div className="container">
      <form>
      <div className="form-row">
        {
          Object.keys(this.fields).filter((field_name)=>this.fields[field_name].fillable).map((k,i)=>{
              var field=this.fields[k];
              if(field.type=="hidden")
              {
                return <input type="hidden" key={k} value={this.state[k]} />
              }
              if(field.type==="editor") {
                  return (<div key={i} className={"form-group col-md-"+field.width}>
                      {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                      <CKEditor activeClass={"p10"} id={k}  className={"form-control "+(field.error!=null?"error":"")} rows="3" type="text" placeholder={field.title} content={this.state[k]} events={{ "change": (e)=>{this.handleFieldChange(k,e.editor.getData())} }} />
                      {field.error!=null?<p className="error_message">{field.error.message}</p>:""}
                  </div>)
              }
              if(['text','email','phone','money'].indexOf(field.type)>=0)
              {
                if(!field.verbose)
                {
                  return (<div key={i} className={"form-group col-md-"+field.width} >
                            {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                            <input id={k}  className={"form-control "+(field.error!=null?"error":"")} type="text" placeholder={field.title} value={this.state[k]} readOnly={field.readOnly} onChange={(e)=>{this.handleFieldChange(k,e.target.value)}}/>
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
              if(field.type=="map")
              {

                return (<div key={i} className={"form-group col-md-"+field.width}>
                          {field.label?<label className="form-control-label" htmlFor={k}>{field.title}</label>:""}
                          <Select
                                    value={this.state[k].toString()}
                                    multi={field.multiple}
                                    className={"form-control "+(field.error!=null?"error":"")}
                                    options={field.options.filter((f)=>{
                                      if(field.depends!=null)
                                      {
                                          let parentField = this.fields[field.depends];
                                          let currentParentValue = this.state[field.depends];
                                          let parentOptions = parentField.options;
                                          let parentSelected = null;
                                          parentOptions.map((option)=>{
                                            if(option.id == currentParentValue)
                                            {
                                              parentSelected = option;
                                            }
                                          })

                                          if(parentSelected==null)
                                          return false;
                                          else
                                          return parentSelected.parent_code == f.correlative;
                                      }
                                      return true;
                                    }).map((opt,i)=>{
                                        return {label:opt.value,value:opt.id.toString()}
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
                            <DatePicker id={k} showYearDropdown className={"form-control "+(field.error!=null?"error":"")}
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
        <div className="form-group col-md-12" style={{textAlign:"center"}}>
          <a  onClick={(e)=>{
                this.handleSubmit();
                return false}} className="btn btn-success text-white btn-block ">
              { (this.state.is_loading) ? <em className="fa fa-refresh fa-spin"></em> : 'Guardar'}
          </a>
        </div>

      </form>
    </div>);
  }
}
