import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class StoreEditComponent extends React.Component {

    constructor(props){
        super(props);
        this.default_data = JSON.parse(this.props.default_data);
        this.data_update_url = this.props.data_update_url;

        this.handleChange = this.handleChange.bind(this);

        this.state = {
            is_loading : false
        };

        this.fields = {
            id:{type:"hidden"},
            business_name:{title:"Razón social",type:"text",required:true,width:6},
            ruc:{title:"RUC",type:"text",required:true,width:6},
            legal_address:{title:"Dirección legal",type:"text",required:true,width:6},
            comercial_name:{title:"Nombre Comercial",type:"text",width:6},
            phone:{title:"Teléfono",type:"text",required:true,width:6},
            site_url:{title:"URL de la Tienda",type:"text",required:true,width:6},
        };

        this.injectDefault = this.injectDefault.bind(this);
        this.injectDefault();

        console.log(this.default_data);
    }

    handleChange(name,date) {
        let d = {};
        d[name] = date;
        this.setState(d);
    }

    injectDefault(){
        Object.keys(this.default_data).map((field_name)=>{
            if(this.fields[field_name]!=null) {
                this.fields[field_name].default=this.default_data[field_name];
            }
        });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <AutoForm fields={this.fields} url={this.data_update_url} />
            </div>
        );
    }
}

if (document.getElementsByClassName('update-store-component')) {
    var elements=document.getElementsByClassName('update-store-component');
    var count=elements.length;
    for(var i=0;i<count;i++) {
        let element = elements[i];
        var default_data = element.getAttribute('default_data');
        var data_update_url = element.getAttribute('data_update_url');

        ReactDOM.render(<StoreEditComponent
            data_update_url={data_update_url}
            default_data={default_data} />, element);
    }
}