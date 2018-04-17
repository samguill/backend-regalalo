import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class IncomingCouponsComponent extends React.Component {

    constructor(props) {
        super(props);

        this.dataproducts = this.props.dataproducts;
        this.databranches = this.props.databranches;
        this.urlincominginventory = this.props.urlincominginventory;


        this.state = {
            products: [],
            selectValue: '',
            quantity: '',
            selectBranch: ''
        }
        ;

        this.updateValue = this.updateValue.bind(this);
        this.addProduct = this.addProduct.bind(this);
        this.removeProduct = this.removeProduct.bind(this);
        this.updateBranch = this.updateBranch.bind(this);
        this.handleQuantity = this.handleQuantity.bind(this);



    }

    updateValue (newValue) {
        this.setState({
            selectValue: newValue,
        });

        this.dataproducts.map((item) => {

            if(item.id === newValue.value){
                this.setState({
                    sku_code: item.sku_code,
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
                    branchValue:this.state.selectBranch.value,
                    branchLabel:this.state.selectBranch.label,
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


    updateBranch(newValue) {
        this.setState({
            selectBranch: newValue,
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
                        <lable>Producto</lable>
                    <Select
                        value={this.state.selectValue}
                        options={this.dataproducts.map((opt,i)=>{
                            return {label:opt.value,value:opt.id}
                        })}
                        className="form-control"
                        onChange={this.updateValue}
                    />
                    </div>
                    <div className="col-md-4">
                        <lable>Sucursal</lable>
                        <Select
                            value={this.state.selectBranch}
                            options={this.databranches.map((opt,i)=>{
                                return {label:opt.name,value:opt.id}
                            })}
                            onChange={this.updateBranch}
                            className="form-control"

                        />
                    </div>

                    <div className="col-md-2">
                        <lable>Cantidad</lable>
                        <input id="cantidad" name="cantidad" type="text" value={this.state.quantity} className="form-control" onChange={(e)=>{this.handleQuantity(e)}}/>
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
                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.storeIncomingInventory()}}>Ingresar productos</button>
                    </div>
                </form>


        );
    }

}

if (document.getElementsByClassName('store-incoming-inventory')) {
    var elements = document.getElementsByClassName('store-incoming-inventory');
    var count = elements.length;
    for(var i = 0; i < count; i++) {

        let element = elements[i];
        var dataproducts = element.getAttribute("data-products");
        var databranches = element.getAttribute("data-branches");
        var urlincominginventory = element.getAttribute("url-incominginventory");


        ReactDOM.render(<IncomingCouponsComponent

            dataproducts={JSON.parse(dataproducts)}
            databranches={JSON.parse(databranches)}
            urlincominginventory={urlincominginventory}

        />, element);
    }
}