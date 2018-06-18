import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class ComercialContactComponent extends React.Component {

    constructor(props){
        super(props);
        this.store_id = this.props.store_id;
        if(this.props.default_data !== ""){
            this.default_data = JSON.parse(this.props.default_data);
        }else{
            this.default_data = {
                document_number: "",
                email: "",
                id: "",
                name: "",
                phone: "",
                position: "",
                store_id: this.store_id
            }
        }

        this.data_update_url = this.props.data_update_url;

        this.handleChange = this.handleChange.bind(this);

        this.fields = {
            id:{type:"hidden"},
            store_id:{type:"hidden", default:this.store_id},
            name:{title:"Nombres y Apellidos",type:"text",required:true,width:4, default:''},
            document_number:{title:"DNI",type:"text",required:true,width:4, default:''},
            email:{title:"E-mail",type:"text",required:true,width:4, default:''},
            phone:{title:"Teléfono",type:"text",required:true,width:6, default:''},
            position:{title:"Cargo",type:"text",required:true,width:6, default:''}
        };

        this.state = {
            is_loading : false
        };

        this.injectDefault = this.injectDefault.bind(this);
        this.injectDefault();
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

if (document.getElementsByClassName('comercial-contact-edit-component')) {
    var elements=document.getElementsByClassName('comercial-contact-edit-component');
    var count=elements.length;
    for(var i=0;i<count;i++) {
        let element = elements[i];
        var default_data = element.getAttribute('default_data');
        var data_update_url = element.getAttribute('data_update_url');
        let store_id = element.getAttribute("store_id");

        ReactDOM.render(<ComercialContactComponent
            data_update_url={data_update_url}
            store_id={store_id}
            default_data={default_data} />, element);
    }
}