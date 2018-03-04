import React  from 'react';
import ReactDOM from 'react-dom';
import {HashRouter as Router, Route,Link} from 'react-router-dom';
import CrudHome from './home/CrudHome';
import CrudCreate from './create/CrudCreate';
import CrudUpdate from './update/CrudUpdate';
import CrudView from './view/CrudView';
import {Provider} from 'mobx-react';
import CrudStore from '../../store/CrudStore';
export default class  CrudComponent extends React.Component {
  constructor(props) {
    super(props);
    this.init=this.init.bind(this);
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
        this.actions=null;
    }
    // Se setean valores por default
    Object.keys(this.fields).map((field_name)=>{
        let field=this.fields[field_name];
        if(field.fillable==null)
        {
          field.fillable=true;
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
  }
  render() {
    return (
      <Provider store={CrudStore}>
          <Router>
              <div>
                <Route path="/" exact  component={()=><CrudHome url={this.url} buttons={this.props.buttons}  download={this.props.download} search={this.props.search} fields={this.fields} actions={this.actions} />} />
                <Route path="/create" exact component={()=><CrudCreate fields={this.fields} actions={this.actions}/>} />
                <Route path="/update" exact component={()=><CrudUpdate fields={this.fields} actions={this.actions}/>} />
                <Route path="/view" exact component={()=><CrudView fields={this.fields}/>} />
              </div>
          </Router>
      </Provider>
    );
  }
}


if (document.getElementById('laravel-crud-component')) {
    var element=document.getElementById('laravel-crud-component');
    var settings=JSON.parse(element.getAttribute("data-settings"))
    var fields=[];
    var actions=[];
    var buttons =[];
    var search=false;
    var download=false;
    if(settings.fields!=null)
    fields=settings.fields;
    if(settings.actions!=null)
    actions=settings.actions;
    if(settings.search!=null)
    search=settings.search;
    if(settings.download!=null)
    download=settings.download;
    if(settings.buttons!=null)
    buttons=settings.buttons;
    
    ReactDOM.render(<CrudComponent url={element.getAttribute("data-url")} fields={fields} actions={actions} search={search} download = {download} buttons = {buttons} />, element);
}
