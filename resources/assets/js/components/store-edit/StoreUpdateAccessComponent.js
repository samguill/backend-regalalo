import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class StoreUpdateAccessComponent extends React.Component {

    constructor(props) {
        super(props);
        this.data_update_url = this.props.data_update_url;
        this.store_id = this.props.store_id;

        if(this.props.user_data !== ""){
            this.user_data = JSON.parse(this.props.user_data);
        }else{
            this.user_data = {
                email: "",
                id: "",
                name: ""
            }
        }

        this.fields = {
            id:{type:"hidden"},
            store_id:{type:"hidden", default:this.store_id},
            email:{title:"E-mail",type:"text",required:true,width:6, default:''},
            password:{title:"Contraseña",type:"password",required:true,width:6, default:''}
        };

        this.state = {
            is_loading: false
        };

        this.injectDefault = this.injectDefault.bind(this);
        this.injectDefault();
    }

    injectDefault(){
        Object.keys(this.user_data).map((field_name)=>{
            if(this.fields[field_name]!=null) {
                this.fields[field_name].default=this.user_data[field_name];
            }
        });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <AutoForm fields={this.fields} url={this.data_update_url}/>
            </div>
        );
    }
}


if (document.getElementsByClassName('store-access-component')) {
    let elements=document.getElementsByClassName('store-access-component');
    let count=elements.length;
    for(let i=0;i<count;i++) {
        let element = elements[i];
        let store_id = element.getAttribute('store_id');
        let user_data = element.getAttribute('user_data');
        let data_update_url = element.getAttribute('data_update_url');

        ReactDOM.render(<StoreUpdateAccessComponent
            data_update_url={data_update_url}
            user_data={user_data}
            store_id={store_id} />, element);
    }
}