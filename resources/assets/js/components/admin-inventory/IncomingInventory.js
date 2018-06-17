import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';

export default class IncomingInventory extends React.Component {

    constructor(props) {
        super(props);
        this.stores = JSON.parse(this.props.data_stores);
        this.urlstorebranches = this.props.urlstorebranches;
        this.urlincominginventory = this.props.urlincominginventory;

        this.onSelectStore = this.onSelectStore.bind(this);
        this.onSelectBranch = this.onSelectBranch.bind(this);
        this.onSelectProduct = this.onSelectProduct.bind(this);
        this.handleQuantity = this.handleQuantity.bind(this);
        this.addProduct = this.addProduct.bind(this);
        this.removeProduct = this.removeProduct.bind(this);

        this.state = {
            products: [],
            branches: [],
            inventory_products: [],
            store_id: '',
            branch_id: '',
            product_id: '',
            is_loading: false,
            quantity: ''
        };
    }

    render() {
        return (
            <form className="form-group row mt-10">
                <div className="col-md-3">
                    <lable>Tienda</lable>
                    <Select
                        value={this.state.store_id}
                        options={this.stores}
                        className="form-control"
                        onChange={this.onSelectStore}
                    />
                </div>
                <div className="col-md-3">
                    <lable>Sucursal</lable>
                    <Select
                        value={this.state.branch_id}
                        options={this.state.branches}
                        className="form-control"
                        onChange={this.onSelectBranch}
                    />
                </div>
                <div className="col-md-3">
                    <lable>Producto</lable>
                    <Select
                        value={this.state.product_id}
                        options={this.state.products}
                        className="form-control"
                        onChange={this.onSelectProduct}
                    />
                </div>
                <div className="col-md-2">
                    <lable>Cantidad</lable>
                    <input id="cantidad" name="cantidad" type="text" value={this.state.quantity} className="form-control" onChange={(e)=>{this.handleQuantity(e)}}/>
                </div>
                <div className="col-md-1" style={{paddingTop:"20px"}}>
                    <button type="button"  className="btn btn-success" onClick={(e)=> {this.addProduct(this.state.product_id)}}>
                        <em className="fa fa-plus"/>
                    </button>
                </div>
                <div className="col-md-12">
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
                                this.state.inventory_products.map((row, ri)=> {
                                    return <tr key={ri}>
                                        <td>{row.product}</td>
                                        <td>{row.branch}</td>
                                        <td>{row.quantity}</td>
                                        <td>
                                            <button type="button"  className="btn btn-danger" style={{margin:"0px"}} onClick={(e)=> {this.removeProduct(ri)}}>
                                                <em className="fa fa-trash" />
                                            </button>
                                        </td>
                                    </tr>
                                })
                            }
                        </tbody>
                    </table>
                </div>
                <div className="col-md-12">
                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.storeIncomingInventory()}}>
                        { (this.state.is_loading) ? <em className="fa fa-refresh fa-spin" /> : 'Ingresar producto(s)'}
                    </button>
                </div>
            </form>
        );
    }

    handleQuantity(e) {
        this.setState({ quantity: e.target.value });
    }

    onSelectBranch(branch){
        this.setState({branch_id:branch});
    }

    onSelectProduct(product){
        this.setState({product_id:product});
    }

    onSelectStore(store){
        let store_id = store.value;
        this.setState({store_id:store, products: [], product_id: '', branches: [], branch_id: ''});
        swal({
            title: "Obteniendo sucursales productos de la tienda " + store.label,
            text: "Por favor, espere este proceso puede tomar varios segundos.",
            allowOutsideClick:false,
            allowEscapeKey:false,
            allowEnterKey:false,
            onOpen: () => {
                swal.showLoading();
            }
        });
        axios.post(this.urlstorebranches, {store_id: store_id})
            .then((response) => {
                if (response.data.status === "ok"){
                    this.setState({branches:response.data.branches, products:response.data.products});
                }
                swal.close();
            })
            .catch((error) => {
                swal.close();
                swal({
                    type: 'error',
                    title: 'Ha ocurrido un error',
                    text: error.message,
                });
            })
    }

    addProduct(selectValue) {
        let inventory_products = this.state.inventory_products.slice();
        this.state.products.map((item) => {
            if(item.value === selectValue.value){
                inventory_products.push({
                    product: this.state.product_id.label,
                    branch: this.state.branch_id.label,
                    quantity:this.state.quantity,
                    store: this.state.store_id.label,
                    branch_id: this.state.branch_id.value,
                    product_id: this.state.product_id.value
                });
                this.setState({
                    inventory_products: inventory_products,
                });
            }
        });
    }

    removeProduct(index) {
        this.setState({
            inventory_products: this.state.inventory_products.filter((_, i) => i !== index)
        });
    }

    storeIncomingInventory(){
        this.setState({is_loading:true});
        axios.post(this.urlincominginventory, {inventory_products:this.state.inventory_products})
            .then((response) => {
                console.log(response);
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text:  "El registro ha sido exitoso",
                        type: "success"
                    }, function () {
                        window.location = "/admin-inventory";
                    });
                }
                if(response.data.status === "error"){
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                }
                this.setState({is_loading:false});
            })
            .catch(function (error) {
                this.setState({is_loading:false});
                swal({  title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"});
            });
    }

}

if (document.getElementsByClassName('admin-incoming-inventory')) {
    let elements = document.getElementsByClassName('admin-incoming-inventory');
    let count = elements.length;
    for(let i = 0; i < count; i++) {

        let element = elements[i];
        let data_stores = element.getAttribute("data-stores");
        let urlincominginventory = element.getAttribute("url-incominginventory");
        let urlstorebranches = element.getAttribute("url-store-branches");


        ReactDOM.render(<IncomingInventory
            data_stores={data_stores}
            urlincominginventory={urlincominginventory}
            urlstorebranches={urlstorebranches}
        />, element);
    }
}