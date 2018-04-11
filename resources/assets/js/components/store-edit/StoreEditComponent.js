import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class StoreEditComponent extends React.Component {

    constructor(props){
        super(props);
        this.default_data = JSON.parse(this.props.default_data);
        this.data_update_url = this.props.data_update_url;

        this.handleChange = this.handleChange.bind(this);

        this.fields = {
            id:{type:"hidden"},
            business_name:{title:"Razón social",type:"text",required:true,width:6},
            ruc:{title:"RUC",type:"text",required:true,width:6},
            legal_address:{title:"Dirección legal",type:"text",required:true,width:6},
            comercial_name:{title:"Nombre Comercial",type:"text",width:6},
            phone:{title:"Teléfono",type:"text",required:true,width:4},

            payme_process_status:{
                title: "PayMe",
                type: "map",
                width:4,
                options:[
                    {id:"0", value:"Pendiente"},
                    {id:"1", value:"Integración"},
                    {id:"2", value:"Producción"}
                ]
            },
            site_url:{title:"URL de la Tienda",type:"text",width:4},
            financial_entity:{
                title: "Entidad Financiera",
                type: "map",
                width:4,
                options:[
                    {id:"BCP", value:"BCP"},
                    {id:"BBVA", value:"BBVA"},
                    {id:"INTERBANK", value:"INTERBANK"},
                    {id:"SCOTIABANK", value:"SCOTIABANK"},
                    {id:"BANBIF", value:"BANBIF"}
                ]
            },
            account_type:{
                title: "Tipo de cuenta",
                type: "map",
                width:4,
                options:[
                    {id:"Cuenta de Ahorros", value:"Cuenta de Ahorros"},
                    {id:"Cuenta Corriente", value:"Cuenta Corriente"}
                ]
            },
            account_statement_name:{title:"Nombre del Estado de Cuenta",type:"text",width:4},
            bank_account_number:{title:"Número de Cuenta Bancaria",type:"text",width:6},
            cci_account_number:{title:"Código de Cuenta Interbancario (CCI)",type:"text",width:6},

            business_turn:{title:"Giro de LA EMPRESA",type:"text",width:4},
            monthly_transactions:{title:"Transacciones mensuales",type:"text",width:4},
            average_amount:{title:"Importe promedio por transacción",type:"text",width:4},
            maximum_amount:{title:"Importe máximo por transacción",type:"text",width:4},

            payme_comerce_id:{title:"PayMe ID Comercio",type:"text",width:4},
            payme_wallet_id:{title:"PayMe ID Wallet",type:"text",width:4},

            urbaner_process_status:{
                title:"Urbaner",
                type: "map",
                width:4,
                options:[
                    {id:"0", value:"Pendiente"},
                    {id:"1", value:"Integración"},
                    {id:"2", value:"Producción"}
                ]},
            urbaner_token:{title:"Token Urbaner",type:"text",width:4},

            analytics_id:{title:"Google Analytics ID",type:"text",width:4},
        };

        this.state = {
            is_loading : false
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