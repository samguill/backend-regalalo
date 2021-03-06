import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class ProductEditComponent extends React.Component {

    constructor(props){
        super(props);
        this.default_data = JSON.parse(this.props.default_data);
        this.data_update_url = this.props.data_update_url;
        this.data_sex = JSON.parse(this.props.data_sex);
        this.data_ages = JSON.parse(this.props.data_ages);
        this.data_events = JSON.parse(this.props.data_events);
        this.data_brands = JSON.parse(this.props.data_brands);
        this.data_interests = JSON.parse(this.props.data_interests);
        this.data_tags = JSON.parse(this.props.data_tags);

        this.handleChange = this.handleChange.bind(this);

        this.fields = {
            id:{type:"hidden"},
            sku_code:{title:"Código del producto",type:"text",required:true,width:4},
            name:{title:"Nombre del producto",type:"text",required:true,width:4},
            brand_id:{
                title:"Marca",
                type:"map",
                width:4,
                options:this.data_brands
            },
            discount:{title:"Descuento",type:"text",required:true,width:4},
            price:{title:"Precio",type:"text",required:true,width:4},
            product_presentation:{
                title:"Venta por",
                type:"map",
                required:true,
                width:4,
                options:[
                    {id:"unidad", value:"Unidad"},
                    {id:"par", value:"Par"},
                    {id:"caja", value:"Caja"},
                    {id:"docena", value:"Docena"}
                ]
            },
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
            availability:{
                title:"Disponibilidad",
                type:"map",
                options:[
                    {id:"D", value:"Delivery"},
                    {id:"S", value:"Tienda"},
                    {id:"A", value:"Todos"}
                ],
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
            /*event:{
                title:"Ocasión",
                type:"map",
                multiple:true,
                renderAS:'text',
                options:this.data_events,
                width:6
            },
            interest:{
                title:"Interés",
                type:"map",
                multiple:true,
                renderAS:'text',
                options:this.data_interests,
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

if (document.getElementsByClassName('update-product-component')) {
    let elements=document.getElementsByClassName('update-product-component');
    let count=elements.length;
    for(let i=0;i<count;i++) {
        let element = elements[i];
        let default_data = element.getAttribute('default_data');
        let data_update_url = element.getAttribute('data_update_url');
        let data_sex = element.getAttribute('data_sex');
        let data_ages = element.getAttribute('data_ages');
        let data_events = element.getAttribute('data_events');
        let data_interests = element.getAttribute('data_interests');
        let data_brands = element.getAttribute('data-brands');
        let data_tags = element.getAttribute('data-tags');

        ReactDOM.render(<ProductEditComponent
            data_ages={data_ages}
            data_sex={data_sex}
            data_events={data_events}
            data_interests={data_interests}
            data_brands={data_brands}
            data_tags={data_tags}
            data_update_url={data_update_url}
            default_data={default_data} />, element);
    }
}