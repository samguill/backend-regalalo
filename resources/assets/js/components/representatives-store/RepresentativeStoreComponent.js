import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class RepresentativeStoreComponent extends React.Component {

    constructor(props){
        super(props);

        this.representatives = JSON.parse(this.props.default_data);
        this.store_id = this.props.store_id;
        this.data_create_url = this.props.data_create_url;
        this.data_update_url = this.props.data_update_url;

        this.state = {
            id: '',
            name: '',
            document_number: '',
            phone: '',
            position: '',
            representatives: this.representatives,
            is_loading:false,
            updating: false
        };

        this.clearForm = this.clearForm.bind(this);
        this.onIdChange = this.onIdChange.bind(this);
        this.onNameChange = this.onNameChange.bind(this);
        this.onDocumentNumberChange = this.onDocumentNumberChange.bind(this);
        this.onPhoneChange = this.onPhoneChange.bind(this);
        this.onPositionChange = this.onPositionChange.bind(this);
    }

    onIdChange(e){ this.setState({id:e.target.value}); }
    onNameChange(e){ this.setState({name:e.target.value}); }
    onDocumentNumberChange(e){ this.setState({document_number:e.target.value}); }
    onPhoneChange(e){ this.setState({phone:e.target.value}); }
    onPositionChange(e){ this.setState({position:e.target.value}); }

    clearForm(){
        this.setState({
            id: '',
            name: '',
            document_number: '',
            phone: '',
            position: '',
            updating: false
        });
    }

    editRepresentative(row){
        this.setState({
            id: row.id,
            name: row.name,
            document_number: row.document_number,
            phone: row.phone,
            position: row.position,
            updating: true
        });
    }

    storeRepresentative(data){
        this.setState({is_loading:true});
        let url = "";
        let message = "";

        if(this.state.updating){
            url = this.data_update_url;
            message = "La información se ha actualizado de manera exitosa.";
        }else{
            url = this.data_create_url;
            message = "Se ha creado el registro.";
        }

        axios.post(url, data)
            .then((response) => {
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text: message,
                        type: "success"});
                    if(this.state.updating){
                        var list = this.state.representatives.map((item) => {
                            if(response.data.data.id === item.id){
                                item = response.data.data;
                            }
                            return item;
                        });
                        this.setState({representatives: list, is_loading:false});
                    }else{
                        this.setState({representatives: this.state.representatives.concat(response.data.data), is_loading: false});
                    }
                    this.clearForm();
                }
                if(response.data.status === 'error') {
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                    this.setState({is_loading:false});
                }
            })
            .catch((error) => {
                swal({  title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"});
                this.setState({is_loading:false});
            });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <form>
                    <input type="hidden" name="id" value={this.state.id} onChange={this.onIdChange} />

                    <div className="row">
                        <div className="col-md-4 b-right">
                            <h5 className="underline mb-20">Registrar / Editar</h5>
                            <div className="form-group">
                                <lable>Nombre de Representante Legal</lable>
                                <input id="name" name="name" onChange={this.onNameChange} className="form-control" type="text" value={this.state.name} />
                            </div>

                            <div className="form-group">
                                <lable>DNI</lable>
                                <input id="document_number" name="document_number" onChange={this.onDocumentNumberChange} className="form-control" type="text" value={this.state.document_number} />
                            </div>

                            <div className="form-group">
                                <lable>Teléfono</lable>
                                <input id="phone" name="phone" onChange={this.onPhoneChange} className="form-control" type="text" value={this.state.phone} />
                            </div>

                            <div className="form-group">
                                <lable>Cargo</lable>
                                <input id="position" name="position" onChange={this.onPositionChange} className="form-control" type="text" value={this.state.position} />
                            </div>

                            <div className="form-group" style={{textAlign:"center"}}>
                                <div className="row">
                                    <div className={"col-md-" + (this.state.updating ? 6 : 12)}>
                                        <a className="btn btn-success text-white btn-block" onClick={(e) => { this.storeRepresentative({
                                            id: this.state.id,
                                            name: this.state.name,
                                            document_number: this.state.document_number,
                                            phone: this.state.phone,
                                            position: this.state.position,
                                        })}}>
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
                            <table className="table">
                                <thead style={{ background: "#f94e19", color: "#FFF" }}>
                                    <tr>
                                        <td>Nombre</td>
                                        <td>DNI</td>
                                        <td>Teléfono</td>
                                        <td>Acciones</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.representatives.map((row,ri)=>{
                                            return <tr key={ri}>
                                                <td>{row.name}</td>
                                                <td>{row.document_number}</td>
                                                <td>{row.phone}</td>
                                                <td>
                                                    <Tooltip title={'Editar'} position={"top"}>
                                                        <button type="button"  className="btn btn-primary btn-sm" style={{margin:"2px"}} onClick={(e)=> {this.editRepresentative(row)}}>
                                                            <em className="fa fa-edit"></em>
                                                        </button>
                                                    </Tooltip>
                                                    <Tooltip title={'Eliminar'} position={"top"}>
                                                        <button type="button" className="btn btn-danger btn-sm" style={{margin:"2px"}} >
                                                            <em className="fa fa-trash"></em>
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
        );
    }

}

if (document.getElementsByClassName('representantive-store-component')) {
    var elements = document.getElementsByClassName('representantive-store-component');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var default_data = element.getAttribute("default_data");
        var store_id = element.getAttribute("store_id");
        var data_update_url = element.getAttribute("data_update_url");
        var data_create_url = element.getAttribute("data_create_url");

        ReactDOM.render(<RepresentativeStoreComponent
            default_data={default_data}
            store_id={store_id}
            data_create_url={data_create_url}
            data_update_url={data_update_url}
        />, element);
    }
}