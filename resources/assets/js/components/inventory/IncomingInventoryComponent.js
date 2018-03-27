import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class IncomingInventoryComponent extends React.Component {

    constructor(props) {
        super(props);

        this.dataproducts = this.props.dataproducts;
        this.databranches = this.props.databranches;


        this.state = {
            products: [],
            selectValue: '',
            sku_code: '',
            description: '',
            price:'',
            selectBranch:''
        };

        this.updateValue = this.updateValue.bind(this);
        this.addProduct = this.addProduct.bind(this);
        this.removeProduct = this.removeProduct.bind(this);
        this.updateBranch = this.updateBranch.bind(this);

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

                products.push({producto: selectValue.label});

                this.setState({
                    products: products
                });
            }})

        console.log( this.state.products);
    }

    removeProduct(index) {
        this.setState({
            products: this.state.products.filter((_, i) => i !== index)
        });
    }

    updateBranch (newValue) {
        this.setState({
            selectBranch: newValue,
        });

    }

    render() {
        return (

                <form className="form-group row" >
                    <div className="col-md-11">
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

                    <div className="col-md-1" style={{paddingTop:"10px"}} >

                        <button type="button"  className="btn btn-success" style={{margin:"2px"}} onClick={(e)=> {this.addProduct(this.state.selectValue)}}>
                            <em className="fa fa-plus"></em>
                        </button>
                    </div>
                    <div className="col-md-6">
                        <lable>SKU Code</lable>
                        <input id="sku_code" name="sku_code" className="form-control" type="text" value={this.state.sku_code} readOnly />
                    </div>
                    <div className="col-md-6">
                        <lable>Precio</lable>
                        <input id="price" name="price" className="form-control" type="text" value={this.state.price} readOnly />
                    </div>

                    <div className="col-md-12">
                        <lable>Descripción</lable>
                        <input id="description" name="description" className="form-control" type="text" value={this.state.description}  readOnly/>
                    </div>

                    <div className="col-md-12 detalle_producto">
                        <table className="table" id="productos_manual">
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
                                        <td>{row.producto}</td>
                                        <td> <Select
                                            value={this.state.selectBranch}
                                            options={this.databranches.map((opt,i)=>{
                                                return {label:opt.name,value:opt.id}
                                            })}
                                            onChange={this.updateBranch}
                                            className="form-control"

                                        /></td>
                                        <td> <input id="cantidad" name="cantidad" className="form-control" type="text"/></td>
                                        <td> <button type="button"  className="btn btn-danger" style={{margin:"0px"}} onClick={(e)=> {this.removeProduct(ri)}}>
                                            <em className="fa fa-trash"></em>
                                        </button></td>
                                    </tr>
                                })
                            }
                            </tbody>
                        </table>
                    </div>

                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.removeProduct(ri)}}>Registrar</button>
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


        ReactDOM.render(<IncomingInventoryComponent

            dataproducts={JSON.parse(dataproducts)}
            databranches={JSON.parse(databranches)}
            urlincominginventory={urlincominginventory}

        />, element);
    }
}