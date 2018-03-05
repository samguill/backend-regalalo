import React  from 'react';
import {inject,observer} from 'mobx-react';
import {Link} from 'react-router-dom'
export default inject('store')(observer(class CrudView extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (<div className="create-form">
              <ol className="breadcrumb">
              <li className="breadcrumb-item"><Link to="/">Home</Link></li>
              <li className="breadcrumb-item active">Vista Previa</li>
              </ol>
              <div className="card">
                <div className="card-body">
                  <table className='table'>
                    <thead>
                      <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                    {Object.keys(this.props.fields).map((field_name,i)=>{
                      let field=this.props.fields[field_name];
                      if(field.renderAs=="text")
                      {
                          if(field.type=="map")
                          {
                            let options=field.options;
                            let currentOption=options.filter((o)=>o.id==this.props.store.currentElement.get(field_name));
                            if(currentOption.length>0)
                            return <tr key={i}>
                                      <td><b>{field.title}</b></td>
                                      <td>{currentOption[0].value}</td>
                                  </tr>
                            else
                            return <tr key={i}>
                                      <td><b>{field.title}</b></td>
                                      <td>{this.props.store.currentElement.get(field_name)}</td>
                                  </tr>
                          }
                          if(field.type=="text")
                          {
                            return <tr key={i}>
                                      <td><b>{field.title}</b></td>
                                      <td>{this.props.store.currentElement.get(field_name)}</td>
                                  </tr>
                          }
                          if(field.type=="json")
                          {
                            var json=JSON.parse(this.props.store.currentElement.get(field_name));
                            var v="";
                            Object.keys(json).map((key)=>{
                                v+=json[key]+",";
                            })
                            v=v.substr(0,v.length-1);
                            return <tr key={i}>
                                      <td><b>{field.title}</b></td>
                                      <td>{v}</td>
                                  </tr>
                          }
                      }


                      if(field.renderAs=="icon")
                      return <tr key={i}>
                                <td><b>{field.title}</b></td>
                                <td>{<em className={"fa fa-"+this.props.fields[field_name].renderParams.filter((item)=>item.value==this.props.store.currentElement.get(field_name))[0].icon}></em>}</td>
                            </tr>
                    })}
                  </tbody>
                  </table>
                </div>
              </div>


      </div>);
  }
}
))
