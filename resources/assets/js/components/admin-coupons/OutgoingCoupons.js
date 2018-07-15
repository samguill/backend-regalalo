import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';

export default class OutgoingCoupons extends React.Component {

    constructor(props) {
        super(props);
        this.stores = JSON.parse(this.props.data_stores);
        this.urlstorebranches = this.props.urlstorebranches;
        this.urloutgoingcoupons = this.props.urloutgoingcoupons;

        this.onSelectStore = this.onSelectStore.bind(this);
        this.onSelectBranch = this.onSelectBranch.bind(this);
        this.onSelectService = this.onSelectService.bind(this);
        this.handleQuantity = this.handleQuantity.bind(this);
        this.addService = this.addService.bind(this);
        this.removeService = this.removeService.bind(this);

        this.state = {
            services: [],
            branches: [],
            coupons_services: [],
            store_id: '',
            branch_id: '',
            service_id: '',
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
                    <lable>Servicio</lable>
                    <Select
                        value={this.state.service_id}
                        options={this.state.services}
                        className="form-control"
                        onChange={this.onSelectService}
                    />
                </div>
                <div className="col-md-2">
                    <lable>Cantidad</lable>
                    <input id="cantidad" name="cantidad" type="text" value={this.state.quantity} className="form-control" onChange={(e)=>{this.handleQuantity(e)}}/>
                </div>
                <div className="col-md-1" style={{paddingTop:"20px"}}>
                    <button type="button"  className="btn btn-success" onClick={(e)=> {this.addService(this.state.service_id)}}>
                        <em className="fa fa-plus"/>
                    </button>
                </div>
                <div className="col-md-12">
                    <table className="table mt-10">
                        <thead>
                        <tr>
                            <th>Serviceo</th>
                            <th>Ubicación</th>
                            <th width="60">Cantidad</th>
                            <th width="60">Quitar</th>
                        </tr>
                        </thead>
                        <tbody>
                        {
                            this.state.coupons_services.map((row, ri)=> {
                                return <tr key={ri}>
                                    <td>{row.service}</td>
                                    <td>{row.branch}</td>
                                    <td>{row.quantity}</td>
                                    <td>
                                        <button type="button"  className="btn btn-danger" style={{margin:"0px"}} onClick={(e)=> {this.removeService(ri)}}>
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
                    <button type="button" className="btn btn-primary"  onClick={(e)=> {this.storeOutgoingCoupons()}}>
                        { (this.state.is_loading) ? <em className="fa fa-refresh fa-spin" /> : 'Egresar serviceo(s)'}
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

    onSelectService(service){
        this.setState({service_id:service});
    }

    onSelectStore(store){
        let store_id = store.value;
        this.setState({store_id:store, services: [], service_id: '', branches: [], branch_id: ''});
        swal({
            title: "Obteniendo sucursales serviceos de la tienda " + store.label,
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
                    this.setState({branches:response.data.branches, services:response.data.services});
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

    addService(selectValue) {
        let coupons_services = this.state.coupons_services.slice();
        this.state.services.map((item) => {
            if(item.value === selectValue.value){
                coupons_services.push({
                    service: this.state.service_id.label,
                    branch: this.state.branch_id.label,
                    quantity:this.state.quantity,
                    store: this.state.store_id.label,
                    branch_id: this.state.branch_id.value,
                    service_id: this.state.service_id.value
                });
                this.setState({
                    coupons_services: coupons_services,
                });
            }
        });
    }

    removeService(index) {
        this.setState({
            coupons_services: this.state.coupons_services.filter((_, i) => i !== index)
        });
    }

    storeOutgoingCoupons(){
        this.setState({is_loading:true});
        axios.post(this.urloutgoingcoupons, {coupons_services:this.state.coupons_services})
            .then((response) => {
                console.log(response);
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text:  "El registro ha sido exitoso",
                        type: "success"
                    }, function () {
                        window.location = "/admin-coupons";
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


if (document.getElementsByClassName('admin-outgoing-coupons')) {
    let elements = document.getElementsByClassName('admin-outgoing-coupons');
    let count = elements.length;
    for(let i = 0; i < count; i++) {

        let element = elements[i];
        let data_stores = element.getAttribute("data-stores");
        let urloutgoingcoupons = element.getAttribute("url-outgoingcoupons");
        let urlstorebranches = element.getAttribute("url-store-branches");


        ReactDOM.render(<OutgoingCoupons
            data_stores={data_stores}
            urloutgoingcoupons={urloutgoingcoupons}
            urlstorebranches={urlstorebranches}
        />, element);
    }
}