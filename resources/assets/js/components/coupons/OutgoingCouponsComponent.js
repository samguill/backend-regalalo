import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class OutgoingCouponsComponent extends React.Component {

    constructor(props) {
        super(props);

        this.dataservices = this.props.dataservices;
        this.urloutgoingcoupons = this.props.urloutgoingcoupons;


        this.state = {
            services: [],
            selectValue: '',
            quantity: '',
            outgoingquantity:''
        }
        ;

        this.updateValue = this.updateValue.bind(this);
        this.addService = this.addService.bind(this);
        this.removeService = this.removeService.bind(this);
        this.handleQuantity = this.handleQuantity.bind(this);
        this.validateQuantity = this.validateQuantity.bind(this);
        this.onQuantityOutChange = this.onQuantityOutChange.bind(this);
    }

    onQuantityOutChange(e){ this.setState({outgoingquantity:e.target.value}); }


    validateQuantity(e) {
        if(parseInt(e.target.value) > parseInt(this.state.quantity)){

    swal({
             title: "Valor superado.",
             text: "La cantidad a egresar no puede ser mayor a la cantidad actual.",
             type: "error"
         });

        }else{
            this.setState({ outgoingquantity: e.target.value });

        };
    }

    updateValue (newValue) {
        this.setState({
            selectValue: newValue,
        });

        this.dataservices.map((item) => {

            if(item.id === newValue.value){
                this.setState({
                    sku_code: item.sku_code,
                    quantity: item.quantity,
                    description: item.description,
                    price:item.price,
                    store_branche_id:item.store_branche_id
                });
            }})

    }

    addService (selectValue) {
        var services = this.state.services.slice();

        this.dataservices.map((item) => {
            if(item.id === selectValue.value){
                services.push({
                    service: selectValue.label,
                    value: selectValue.value,
                    quantity:this.state.outgoingquantity,
                    store_branche_id: item.store_branche_id

                });
                this.setState({
                    services: services,
                });
            }});
    }

    removeService(index) {
        this.setState({
            services: this.state.services.filter((_, i) => i !== index)
        });
    }

    handleQuantity(e) {
        this.setState({ quantity: e.target.value });
    }

    storeIncomingCoupons(){

        axios.post(this.urloutgoingcoupons, {services:this.state.services})
            .then((response) => {
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text:  "Se ha creado el registro.",
                        type: "success"});

                    window.location = "/coupons";
                }
                if(response.data.status === "error"){
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                }

            })
            .catch(function (error) {
                swal({  title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"});

            });
    }

    render() {
        return (

                <form className="form-group row mt-10" >
                    <div className="col-md-5">
                        <lable>Servicio disponible</lable>
                    <Select
                        value={this.state.selectValue}
                        options={this.dataservices.map((opt,i)=>{
                            return {label:opt.value,value:opt.id}
                        })}
                        className="form-control"
                        onChange={this.updateValue}
                    />
                    </div>

                    <div className="col-md-2">
                        <lable>Cantidad actual</lable>
                        <input id="actual_quantity" name="actual_quantity" readOnly type="text" value={this.state.quantity} className="form-control" onChange={(e)=>{this.handleQuantity(e)}}/>
                    </div>

                    <div className="col-md-2">
                        <lable>Cantidad a egresar</lable>
                        <input id="outgoing_quantity" name="outgoing_quantity" type="text" value={this.state.outgoingquantity} className="form-control" onKeyPress={(e)=>{this.validateQuantity(e)}} onChange={this.onQuantityOutChange}/>
                    </div>

                    <div className="col-md-1" style={{paddingTop:"10px"}} >

                        <button type="button"  className="btn btn-success" style={{margin:"2px"}} onClick={(e)=> {this.addService(this.state.selectValue)}}>
                            <em className="fa fa-plus"></em>
                        </button>
                    </div>



                    <div className="col-md-12" >
                        <table className="table mt-10">
                            <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Ubicación</th>
                                <th width="60">Cantidad</th>
                                <th width="60">Quitar</th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                this.state.services.map((row, ri) => {
                                    return <tr key={ri}>
                                        <td>{row.service}</td>
                                        <td>{row.branchLabel} </td>
                                        <td>{row.quantity}</td>
                                        <td> <button type="button"  className="btn btn-danger" style={{margin:"0px"}} onClick={(e)=> {this.removeService(ri)}}>
                                            <em className="fa fa-trash"></em>
                                        </button></td>
                                    </tr>
                                })
                            }
                            </tbody>
                        </table>
                    </div>
                    <div className="col-md-10">
                    </div>
                    <div className="col-md-2">
                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.storeIncomingCoupons()}}>Egresar cupos de servicio</button>
                    </div>
                </form>


        );
    }

}

if (document.getElementsByClassName('store-outgoing-coupons')) {
    var elements = document.getElementsByClassName('store-outgoing-coupons');
    var count = elements.length;
    for(var i = 0; i < count; i++) {

        let element = elements[i];
        var dataservices = element.getAttribute("data-services");
        var urloutgoingcoupons = element.getAttribute("url-outgoingcoupons");


        ReactDOM.render(<OutgoingCouponsComponent

            dataservices={JSON.parse(dataservices)}
            urloutgoingcoupons={urloutgoingcoupons}

        />, element);
    }
}