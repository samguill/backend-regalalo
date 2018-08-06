import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class ServiceEditComponent extends React.Component {

    constructor(props){
        super(props);
        this.default_data = JSON.parse(this.props.default_data);
        this.data_update_url = this.props.data_update_url;
        this.data_sex = JSON.parse(this.props.data_sex);
        this.data_ages = JSON.parse(this.props.data_ages);
        this.data_experiences = JSON.parse(this.props.data_experiences);
        this.data_product_characteristics = JSON.parse(this.props.data_product_characteristics);
        this.data_tags = JSON.parse(this.props.data_tags);

        this.handleChange = this.handleChange.bind(this);

        this.fields = {
            id:{type:"hidden"},
            sku_code:{title:"Código del servicio",type:"text",required:true,width:6},
            name:{title:"Nombre del servicio",type:"text",required:true,width:6},
            discount:{title:"Descuento",type:"text",required:true,width:4},
            price:{title:"Precio",type:"text",required:true,width:4},
            description:{title:"Descripción",type:"editor",required:true,width:12},
            min_age:{
                title:"Edad mínima",
                type:"map",
                renderAS:'text',
                options:this.data_ages,
                width:2
            },
            max_age:{
                title:"Edad máxima",
                type:"map",
                renderAS:'text',
                options:this.data_ages,
                width:2
            },
            sex:{
                title:"¿A quién regalas?",
                type:"map",
                options:this.data_sex,
                width:4
            },
            tags:{
                title:"Etiquetas",
                type:"map",
                multiple:true,
                renderAS:'text',
                options:this.data_tags,
                width:8
            },
            urbaner_vehicle:{
                title:"Vehículo de urbaner",
                type:"map",
                options:[
                    {id:"1", value:"Bicicleta"},
                    {id:"2", value:"Moto"},
                    {id:"3", value:"Auto"}
                ],
                width:4
            }
            /*experience:{
                title:"Experiencias",
                type:"map",
                multiple:true,
                renderAS:'text',
                options:this.data_experiences,
                width:6
            }*/
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
        let ages = this.default_data.age.split(",");
        this.default_data["min_age"] = ages[0];
        this.default_data["max_age"] = ages[1];
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

if (document.getElementsByClassName('update-service-component')) {
    var elements=document.getElementsByClassName('update-service-component');
    var count=elements.length;
    for(var i=0;i<count;i++) {
        let element = elements[i];
        var default_data = element.getAttribute('default_data');
        var data_update_url = element.getAttribute('data_update_url');
        var data_sex = element.getAttribute('data_sex');
        var data_ages = element.getAttribute('data_ages');
        var data_experiences = element.getAttribute('data_experiences');
        var data_product_characteristics = element.getAttribute('data_product_characteristics');
        let data_tags = element.getAttribute('data-tags');

        ReactDOM.render(<ServiceEditComponent
            data_ages={data_ages}
            data_sex={data_sex}
            data_experiences={data_experiences}
            data_product_characteristics={data_product_characteristics}
            data_tags={data_tags}
            data_update_url={data_update_url}
            default_data={default_data} />, element);
    }
}