import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class ProductCharacteristicsComponent extends React.Component {

    constructor(props) {
        super(props);
        this.data_product_characteristics = JSON.parse(this.props.data_product_characteristics);
        this.data_product_characteristics_detail = JSON.parse(this.props.data_product_characteristics_detail);
        this.product_id = this.props.product_id;
        this.data_update_url = this.props.data_update_url;
        this.data_store_url = this.props.data_store_url;
        this.data_delete_url = this.props.data_delete_url;

        this.product_characteristics = this.data_product_characteristics.map((element) => {
            let obj = {};
            obj["label"] = element.name;
            obj["value"] = element.id;
            return obj;
        });

        this.state = {
            id: '',
            values_array: [],
            values: [],
            product_characteristic_id: '',
            product_characteristics: this.data_product_characteristics,
            product_characteristics_detail: this.data_product_characteristics_detail,
            is_loading: false,
            updating: false
        };

        this.onSelectCharacteristic = this.onSelectCharacteristic.bind(this);
        this.onSelectValue = this.onSelectValue.bind(this);
        this.setValues = this.setValues.bind(this);
        this.clearForm = this.clearForm.bind(this);
    }

    componentDidMount(){
        if(this.characteristic_id){
            this.setValues(this.characteristic_id);
        }
    }

    onSelectCharacteristic(option){
        let characteristic = this.data_product_characteristics.filter((item) => item.id === option.value);
        let values = characteristic[0].values.map((element) => {
            let obj = {};
            obj["label"] = element.value;
            obj["value"] = element.id;
            return obj;
        });
        this.setState({product_characteristic_id:option.value, values_array:values, values:[]});
    }

    setValues(id){
        let characteristic = this.data_product_characteristics.filter((item) => item.id === parseInt(id));
        let values = characteristic[0].values.map((element) => {
            let obj = {};
            obj["label"] = element.value;
            obj["value"] = element.id;
            return obj;
        });

        let current_values = this.characteristic_values.split(",");

        let filter = [];
        values.filter(function(newData){
            return current_values.filter(function (oldData){
                if(newData.label === oldData){
                    filter.push(newData)
                }
            });
        });
        this.setState({values_array:values, values: filter});
    }

    onSelectValue(options){
        this.setState({values:options});
    }

    storeCharacteristics(){
        this.setState({is_loading:true});
        let obj = [];
        let url = "";
        let message = "";
        this.state.values.map((element) => {
            obj.push(element.label)
        });
        let data = {
            id: this.state.id,
            product_id: this.product_id,
            product_characteristic_id: this.state.product_characteristic_id,
            product_characteristic_values: obj.toString()
        };

        if(this.state.updating){
            url = this.data_update_url;
            message = "La información se ha actualizado de manera exitosa.";
        }else{
            url = this.data_store_url;
            message = "Se ha creado el registro.";
        }

        axios.post(url, data)
            .then((response) => {
                if(response.data.status === "ok") {
                    swal({
                        title: "Operación Exitosa",
                        text: message,
                        type: "success"
                    });
                }
                if(response.data.status === 'error') {
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                }
                if (this.state.updating){
                    let list = this.state.product_characteristics_detail.map((item)=>{
                        if (response.data.data.id === item.id) {
                            item = response.data.data
                        }
                        return item;
                    });
                    this.setState({product_characteristics_detail:list, is_loading:false})
                }else{
                    this.setState({
                        product_characteristics_detail:this.state.product_characteristics_detail.concat(response.data.data),
                        is_loading:false
                    });
                }
                this.clearForm();
            })
            .catch((error) => {
                this.setState({is_loading:false});
                swal({
                    title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"
                });
            });
    }

    edit(data){
        let opt = {};
        opt["value"] = data.id;
        this.onSelectCharacteristic(opt);
        this.setState({
            id:data.id,
            updating: true
        });
    }

    delete(row){
        swal({
            title: "¿Estás seguro?",
            text: "Si eliminas este item no podrás recuperarlo",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, eliminar",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },()=> {
            axios.post(this.data_delete_url,{id:row}).then((r)=>{
                if(r.data.status ==='ok') {
                    swal({  title: "Eliminación Exitosa",
                        text: "El elemento ha sido eliminado de manera exitosa.",
                        type: "success"});
                    if(this.state.id === row){
                        this.clearForm();
                    }
                    this.setState({product_characteristics_detail:this.state.product_characteristics_detail.filter((item) => item.id !== row)});
                }
                if(r.data.status==='error') {
                    swal({  title: "Ha ocurrido un error al Eliminar",
                        text: "Por favor intente una vez más.",
                        type: "error"})
                }
            }).catch((e)=>{
                swal({  title: "Ha ocurrido un error al Eliminar",
                    text: "Por favor intente una vez más.",
                    type: "error"})
            })
        });
    }

    clearForm(){
        this.setState({
            id: '',
            values_array: [],
            product_characteristics: '',
            updating: false
        })
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <form>
                    <div className="row">
                        <div className="col-md-4 border-right">
                            <h5 className="underline mb-20">Registrar / Editar</h5>
                            <div className="form-group">
                                <lable>Característica</lable>
                                <Select
                                    className="form-control"
                                    name="id"
                                    options={this.product_characteristics}
                                    value={this.state.product_characteristic_id}
                                    onChange={this.onSelectCharacteristic}
                                />
                            </div>
                            <div className="form-group">
                                <lable>Valores</lable>
                                <Select
                                    className="form-control"
                                    name="values"
                                    value={this.state.values}
                                    options={this.state.values_array}
                                    multi={true}
                                    onChange={this.onSelectValue}
                                />
                            </div>
                            <div className="form-group" style={{textAlign:"center"}}>
                                <div className="row">
                                    <div className={"col-md-" + (this.state.updating ? 6 : 12)}>
                                        <a className="btn btn-success text-white btn-block" onClick={(e) => { this.storeCharacteristics()}}>
                                            { (this.state.is_loading) ? <em className="fa fa-refresh fa-spin"></em> : 'Guardar'}
                                        </a>
                                    </div>
                                    { this.state.updating ? <div className="col-md-6">
                                        <a className="btn btn-danger text-white btn-block" onClick={this.clearForm}>Limpiar</a>
                                    </div> : null}
                                </div>
                            </div>
                        </div>
                        <div className="col-md-8">
                            <table className="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Catacterística</th>
                                        <td>Valores</td>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.product_characteristics_detail.map((row, ri) => {
                                            return <tr key={ri}>
                                                <td>{row.characteristic.name}</td>
                                                <td>{row.product_characteristic_values}</td>
                                                <td>
                                                    <Tooltip title={'Editar'} position={"top"}>
                                                        <button type="button"  className="btn btn-primary btn-sm" style={{margin:"2px"}} onClick={(e)=> {this.edit(row)}}>
                                                            <em className="fa fa-edit" />
                                                        </button>
                                                    </Tooltip>
                                                    <Tooltip title={'Eliminar'} position={"top"}>
                                                        <button type="button" className="btn btn-danger btn-sm" style={{margin:"2px"}} onClick={(e)=> {this.delete(row.id)}}>
                                                            <em className="fa fa-trash" />
                                                        </button>
                                                    </Tooltip>
                                                </td>
                                            </tr>
                                        })
                                    }
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        )
    }

}

if (document.getElementsByClassName('characteristics-product-component')) {
    let elements = document.getElementsByClassName('characteristics-product-component');
    let count = elements.length;
    for(let i = 0; i < count; i++) {
        let element = elements[i];
        let data_product_characteristics = element.getAttribute("data_product_characteristics");
        let product_id = element.getAttribute("product_id");
        let data_update_url = element.getAttribute("data_update_url");
        let data_store_url = element.getAttribute('data_store_url');
        let data_product_characteristics_detail = element.getAttribute("data_product_characteristics_detail");
        let data_delete_url = element.getAttribute("data_delete_url");

        ReactDOM.render(<ProductCharacteristicsComponent
            data_product_characteristics={data_product_characteristics}
            data_product_characteristics_detail={data_product_characteristics_detail}
            data_delete_url={data_delete_url}
            product_id={product_id}
            data_update_url={data_update_url}
            data_store_url={data_store_url}
        />, element);
    }
}