/**
 * Created by marzioperez on 04/03/18.
 */
import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class ProductCharacteristicValuesComponent extends React.Component {

    constructor(props) {
        super(props);

        this.getBranches = this.getBranches.bind(this);

        this.storeid = this.props.storeid;
        this.url_brancheslist = this.props.url_brancheslist;
        this.url_create_branch = this.props.url_create_branch;
        this.url_update_branch = this.props.url_update_branch;

        this.state = {
            id: '',
            key: '',
            value: '',
            updating: false,
            is_loading: false,
            branches: []
        };

        this.onIdChage = this.onIdChage.bind(this);
        this.onKeyChage = this.onKeyChage.bind(this);
        this.onLatitudeChage = this.onLatitudeChage.bind(this);
        this.onLongitudeChage = this.onLongitudeChage.bind(this);
        this.onAddressChage = this.onAddressChage.bind(this);
        this.clearForm = this.clearForm.bind(this);
        this.onPhoneChage = this.onPhoneChage.bind(this);
        this.onBranchEmailChage = this.onBranchEmailChage.bind(this);
        this.onBH1Chage = this.onBH1Chage.bind(this);
        this.onBH2Chage = this.onBH2Chage.bind(this);

    }

    onIdChage(e){ this.setState({id:e.target.value}); }
    onNameChage(e){ this.setState({key:e.target.value}); }
    onLatitudeChage(e){ this.setState({latitude:e.target.value}); }
    onLongitudeChage(e){ this.setState({longitude:e.target.value}); }
    onAddressChage(e){ this.setState({value:e.target.value}); }
    onPhoneChage(e){ this.setState({phone:e.target.value}); }
    onBranchEmailChage(e){ this.setState({branch_email:e.target.value}); }
    onBH1Chage(e){ this.setState({business_hour_1:e.target.value}); }
    onBH2Chage(e){ this.setState({business_hour_2:e.target.value}); }

    componentDidMount(){
        this.getBranches();

    }

    getBranches(){
        axios.post(this.url_brancheslist, {id:this.storeid})
            .then((response) => {
                if(response.data.status === "ok"){
                    this.setState({branches: response.data.data});
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

    editBranch(row){
        this.setState({
            id: row.id,
            key: row.key,
            value: row.value,
            updating: true
        });
    }

    clearForm(){
        this.setState({
            id: '',
            key: '',
            value: '',
            updating: false
        });

    }

    storeBranch(data){
        let url = "";
        let message = "";

        if(this.state.updating){
            url = this.url_update_branch;
            message = "La información se ha actualizado de manera exitosa.";
        }else{
            url = this.url_create_branch;
            message = "Se ha creado el registro.";
        }
        this.setState({is_loading:true});
        axios.post(url, data)
            .then((response) => {
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text: message,
                        type: "success"});
                    if(this.state.updating){
                        var list = this.state.branches.map((item) => {
                            if(response.data.data.id === item.id){
                                item = response.data.data;
                            }
                            return item;
                        });
                        this.setState({branches: list, is_loading:false});
                    }else{
                        this.setState({branches: this.state.branches.concat(response.data.data), is_loading: false});
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
                console.log(error);
                this.setState({is_loading:false});
            });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <form>
                    <input type="hidden" name="store_id" id="store_id" value={this.props.storeid} />
                    <input type="hidden" name="id" value={this.state.id} onChange={this.onIdChage} />
                    <div className="row">
                        <div className="col-md-4 b-right">
                            <h5 className="underline mb-20">Registrar / Editar</h5>
                            <div className="form-group">
                                <lable>Key</lable>
                                <input id="key" name="key" onChange={this.onKeyChage} className="form-control" type="text" value={this.state.key} />
                            </div>
                            <div className="form-group">
                                <lable>Value</lable>
                                <input id="value" name="value" onChange={this.onValueChage} className="form-control" type="text" value={this.state.value} />
                            </div>


                            <div className="form-group" style={{textAlign:"center"}}>
                                <div className="row">
                                    <div className={"col-md-" + (this.state.updating ? 6 : 12)}>
                                        <a className="btn btn-success text-white btn-block" onClick={(e) => { this.storeBranch({
                                            id: this.state.id,
                                            key: this.state.key,
                                            value: this.state.value,
                                            store_id: this.storeid
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
                                    <td>Key</td>
                                    <td>Value</td>
                                    <td>Acciones</td>
                                </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.branches.map((row, ri) => {
                                            return <tr key={ri}>
                                                <td>{row.key}</td>
                                                <td>{row.value}</td>
                                                <td>
                                                    <Tooltip title={'Editar'} position={"top"}>
                                                        <button type="button"  className="btn btn-primary btn-sm" style={{margin:"2px"}} onClick={(e)=> {this.editBranch(row)}}>
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

if (document.getElementsByClassName('product-characteristic-values')) {
    var elements = document.getElementsByClassName('product-characteristic-values');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var storeid = element.getAttribute("storeid");
        var url_brancheslist = element.getAttribute("brancheslist");
        var url_create_branch = element.getAttribute("url_create_branch");
        var url_update_branch = element.getAttribute("url_update_branch");

        ReactDOM.render(<ProductCharacteristicValuesComponent
            storeid={storeid}
            url_brancheslist={url_brancheslist}
            url_create_branch={url_create_branch}
            url_update_branch={url_update_branch}
        />, element);
    }
}