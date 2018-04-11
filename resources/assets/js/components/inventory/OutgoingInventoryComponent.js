import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class OutgoingInventoryComponent extends React.Component {

    constructor(props) {
        super(props);

        this.dataproducts = this.props.dataproducts;
        this.urlincominginventory = this.props.urlincominginventory;


        this.state = {
            products: [],
            selectValue: '',
            quantity: '',
            outgoingquantity:''
        }
        ;

        this.updateValue = this.updateValue.bind(this);
        this.addProduct = this.addProduct.bind(this);
        this.removeProduct = this.removeProduct.bind(this);
        this.handleQuantity = this.handleQuantity.bind(this);
        this.validateQuantity = this.validateQuantity.bind(this);
        this.onQuantityOutChange = this.onQuantityOutChange.bind(this);
    }

    onQuantityOutChange(e){ this.setState({outgoingquantity:e.target.value}); }


    validateQuantity(e) {

console.log(parseInt(e.target.value)+'   '+parseInt(this.state.quantity))
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

        this.dataproducts.map((item) => {

            if(item.id === newValue.value){
                this.setState({
                    sku_code: item.sku_code,
                    quantity: item.quantity,
                    description: item.description,
                    price:item.price
                });
            }})

    }

    addProduct (selectValue) {
        var products = this.state.products.slice();

        this.dataproducts.map((item) => {
            if(item.id === selectValue.value){
                products.push({
                    product: selectValue.label,
                    value: selectValue.value,
                    quantity:this.state.quantity

                });
                this.setState({
                    products: products,
                });
            }});
    }

    removeProduct(index) {
        this.setState({
            products: this.state.products.filter((_, i) => i !== index)
        });
    }

    handleQuantity(e) {
        this.setState({ quantity: e.target.value });
    }

    storeIncomingInventory(){

        axios.post(this.urlincominginventory, {products:this.state.products})
            .then((response) => {
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text:  "Se ha creado el registro.",
                        type: "success"});

                    window.location = "/inventory";
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
                        <lable>Producto en inventario</lable>
                    <Select
                        value={this.state.selectValue}
                        options={this.dataproducts.map((opt,i)=>{
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

                        <button type="button"  className="btn btn-success" style={{margin:"2px"}} onClick={(e)=> {this.addProduct(this.state.selectValue)}}>
                            <em className="fa fa-plus"></em>
                        </button>
                    </div>



                    <div className="col-md-12" >
                        <table className="table mt-10">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Ubicación</th>
                                <th width="60">Cantidad</th>
                                <th width="60">Quitar</th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                this.state.products.map((row, ri) => {
                                    return <tr key={ri}>
                                        <td>{row.product}</td>
                                        <td>{row.branchLabel} </td>
                                        <td>{row.quantity}</td>
                                        <td> <button type="button"  className="btn btn-danger" style={{margin:"0px"}} onClick={(e)=> {this.removeProduct(ri)}}>
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
                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.storeIncomingInventory()}}>Egresar productos</button>
                    </div>
                </form>


        );
    }

}

if (document.getElementsByClassName('store-outgoing-inventory')) {
    var elements = document.getElementsByClassName('store-outgoing-inventory');
    var count = elements.length;
    for(var i = 0; i < count; i++) {

        let element = elements[i];
        var dataproducts = element.getAttribute("data-products");
        var urlincominginventory = element.getAttribute("url-outgoinginventory");


        ReactDOM.render(<OutgoingInventoryComponent

            dataproducts={JSON.parse(dataproducts)}
            urlincominginventory={urlincominginventory}

        />, element);
    }
}