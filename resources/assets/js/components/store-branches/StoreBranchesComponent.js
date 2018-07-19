/**
 * Created by marzioperez on 04/03/18.
 */
import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class StoreBranchesComponent extends React.Component {

    constructor(props) {
        super(props);

        this.getBranches = this.getBranches.bind(this);
        this.weeks = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sabado','Domingo'];
        this.storeid = this.props.storeid;
        this.url_brancheslist = this.props.url_brancheslist;
        this.url_create_branch = this.props.url_create_branch;
        this.url_update_branch = this.props.url_update_branch;
        this.url_delete_day_open = this.props.url_delete_day_open;

        this.state = {
            id: '',
            name: '',
            latitude: '',
            longitude: '',
            address: '',
            phone: '',
            branch_email: '',
            updating: false,
            is_loading: false,
            branches: [],
            branchopeninghours:[],
            zoom: 13,
            maptype: 'roadmap'
        };

        this.onIdChage = this.onIdChage.bind(this);
        this.onNameChage = this.onNameChage.bind(this);
        this.onLatitudeChage = this.onLatitudeChage.bind(this);
        this.onLongitudeChage = this.onLongitudeChage.bind(this);
        this.onAddressChage = this.onAddressChage.bind(this);
        this.clearForm = this.clearForm.bind(this);
        this.onPhoneChage = this.onPhoneChage.bind(this);
        this.onBranchEmailChage = this.onBranchEmailChage.bind(this);
        this.onchangeDayOpen = this.onchangeDayOpen.bind(this);
        this.onchangeStartHourOpen = this.onchangeStartHourOpen.bind(this);
        this.onchangeEndHourOpen = this.onchangeEndHourOpen.bind(this);
        this.addOpeningHours = this.addOpeningHours.bind(this);
        this.deleteDayOpen = this.deleteDayOpen.bind(this);
        this.iniMap = this.iniMap.bind(this);

    }

    onIdChage(e){ this.setState({id:e.target.value}); }
    onNameChage(e){ this.setState({name:e.target.value}); }
    onLatitudeChage(e){ this.setState({latitude:e.target.value}); }
    onLongitudeChage(e){ this.setState({longitude:e.target.value}); }
    onAddressChage(e){ this.setState({address:e.target.value}); }
    onPhoneChage(e){ this.setState({phone:e.target.value}); }
    onBranchEmailChage(e){ this.setState({branch_email:e.target.value}); }

    onchangeDayOpen(e,k){
        var branchopeninghours = this.state.branchopeninghours.slice();
        branchopeninghours[k].weekday = e.value;
        this.setState({branchopeninghours:branchopeninghours});

    }

    onchangeStartHourOpen(e,k){

        var branchopeninghours = this.state.branchopeninghours.slice();
        branchopeninghours[k].start_hour = e.target.value;
        this.setState({branchopeninghours:branchopeninghours});

    }

    onchangeEndHourOpen(e,k){
        var branchopeninghours = this.state.branchopeninghours.slice();
        branchopeninghours[k].end_hour = e.target.value;
        this.setState({branchopeninghours:branchopeninghours});

    }

    addOpeningHours(){

        var branchopeninghours = this.state.branchopeninghours.slice();
        branchopeninghours.push({id:'',weekday:'',start_hour:'',end_hour:'',store_branche_id:this.state.id});
        this.setState({branchopeninghours:branchopeninghours});

    }

    componentDidMount(){
        this.getBranches();
        this.iniMap();
    }

    iniMap(){
        let mapa = new window.google.maps.Map(document.getElementById('branche_map'), {
            center: {lat: -12.046374, lng: -77.042793},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        mapa.addListener('zoom_changed', () => {
            this.setState({
                zoom: 13
            });
        });

        mapa.addListener('maptypeid_changed', () => {
            this.setState({
                maptype: mapa.getMapTypeId()
            });
        });

        let marker = new window.google.maps.Marker({
            map: mapa,
            position: {lat: -12.046374, lng: -77.042793}
        });

        let address = document.getElementById('address');
        let autoComplete = new window.google.maps.places.Autocomplete(address);
        autoComplete.addListener('place_changed', () => {
            let place = autoComplete.getPlace();
            let location = place.geometry.location;

            this.setState({latitude: location.lat()});
            this.setState({longitude: location.lng()});

            mapa.fitBounds(place.geometry.viewport);
            mapa.setCenter(location);
            marker.setPlace({
                placeId: place.place_id,
                location: location
            });
        });
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

    deleteDayOpen(e,k){
        var branchopeninghours = this.state.branchopeninghours.slice();

        axios.post(this.url_delete_day_open, {id:branchopeninghours[k].id})
            .then((response) => {
                if(response.data.status === "ok"){

                    swal({  title: "Operación Exitosa",
                        text: 'Se eliminó el horario.',
                        type: "success"});

                    branchopeninghours.splice(k, 1);
                    this.setState({branchopeninghours:branchopeninghours});
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
        var branchopeninghours = this.state.branchopeninghours.slice();

        if(row.branchopeninghours===undefined){

            branchopeninghours.push({id:'',weekday:'',start_hour:'',end_hour:'',store_branche_id:this.state.id});
            this.setState({branchopeninghours:branchopeninghours});
        }else{

            branchopeninghours =  row.branchopeninghours;
        }


        this.setState({
            id: row.id,
            name: row.name,
            address: row.address,
            latitude: row.latitude,
            longitude: row.longitude,
            phone: row.phone,
            branch_email: row.branch_email,
            branchopeninghours:branchopeninghours,
            updating: true
        });

        let mapa = new window.google.maps.Map(document.getElementById('branche_map'), {
            center: {lat: row.latitude, lng: row.longitude},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        mapa.addListener('zoom_changed', () => {
            this.setState({
                zoom: mapa.getZoom()
            });
        });

        mapa.addListener('maptypeid_changed', () => {
            this.setState({
                maptype: mapa.getMapTypeId()
            });
        });

        let marker = new window.google.maps.Marker({
            map: mapa,
            position: {lat: row.latitude, lng: row.longitude}
        });
    }

    clearForm(){
        this.setState({
            id: '',
            name: '',
            address: '',
            latitude: '',
            longitude: '',
            phone: '',
            branch_email: '',
            branchopeninghours:[],
            updating: false
        });
        this.iniMap();
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
                        <div className="col-md-5 b-right">
                            <h5 className="underline mb-20">Registrar / Editar</h5>
                            <div className="form-group">
                                <lable>Nombre de la sucursal</lable>
                                <input id="name" name="name" onChange={this.onNameChage} className="form-control" type="text" value={this.state.name} />
                            </div>
                            <div className="form-group">
                                <lable>Dirección (tipea la dirección)</lable>
                                <input id="address" name="address" onChange={this.onAddressChage} className="form-control" type="text" value={this.state.address} />
                            </div>
                            <div className="form-group">
                                <lable>Teléfono (sin guiones y espacios)</lable>
                                <input id="phone" name="phone" onChange={this.onPhoneChage} className="form-control" type="text" value={this.state.phone} />
                            </div>
                            <div className="form-group">
                                <lable>E-mail (aquí se notificarán los pedidos)</lable>
                                <input id="branch_email" name="branch_email" onChange={this.onBranchEmailChage} className="form-control" type="text" value={this.state.branch_email} />
                            </div>
                          <div className="form-group">
                                <lable>Horarios de atención (24hrs)</lable>
                                {this.state.branchopeninghours.map((i, k) => {
                                    return ( <div className="row">
                                        <div className="col-sm-5"><Select className="form-control"  onChange={(e)=>{this.onchangeDayOpen(e,k)}} value={this.state.branchopeninghours[k].weekday} options={this.weeks.map((val,key)=>{return {label:val,value:key}})}/></div>
                                        <div className="col-xs-1"><input className="form-control" type="time" onChange={(e)=>{this.onchangeStartHourOpen(e,k)}} value={this.state.branchopeninghours[k].start_hour}/></div>
                                        <div className="col-xs-1"><input className="form-control" type="time" onChange={(e)=>{this.onchangeEndHourOpen(e,k)}} value={this.state.branchopeninghours[k].end_hour}/></div>
                                        <div className="col-xs-1">{(this.state.branchopeninghours[k].id!='')?<button type="button" onClick={(e)=>{this.deleteDayOpen(e,k)}}  className="btn btn-danger btn-sm" style={{margin:"2px"}} > <em className="fa fa-trash"></em></button>:''}</div>
                                    </div>);

                                })}
                                <div className="col-xs-1"><button onClick={this.addOpeningHours} type="button" className="btn btn-success btn-sm" style={{margin:"2px"}} > <em className="fa fa-plus"></em></button></div>
                            </div>

                            <div className="form-group">
                                <lable>Latitud</lable>
                                <input id="latitude" name="latitude" disabled onChange={this.onLatitudeChage} className="form-control" type="text" value={this.state.latitude} />
                            </div>
                            <div className="form-group">
                                <lable>Longitud</lable>
                                <input id="longitude" name="longitude" disabled onChange={this.onLongitudeChage} className="form-control" type="text" value={this.state.longitude} />
                            </div>
                            <div id="branche_map"/>
                            <div className="form-group" style={{textAlign:"center"}}>
                                <div className="row">
                                    <div className={"col-md-" + (this.state.updating ? 6 : 12)}>
                                        <a className="btn btn-success text-white btn-block" onClick={(e) => { this.storeBranch({
                                            id: this.state.id,
                                            name: this.state.name,
                                            address: this.state.address,
                                            latitude: this.state.latitude,
                                            longitude: this.state.longitude,
                                            phone:this.state.phone,
                                            branch_email:this.state.branch_email,
                                            branchopeninghours:this.state.branchopeninghours,
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
                        <div className="col-md-7">
                            <table className="table">
                                <thead style={{ background: "#f94e19", color: "#FFF" }}>
                                <tr>
                                    <td>Nombre</td>
                                    <td>Dirección</td>
                                    <td>Acciones</td>
                                </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.branches.map((row, ri) => {
                                            return <tr key={ri}>
                                                <td>{row.name}</td>
                                                <td>{row.address}</td>
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

if (document.getElementsByClassName('store-branches')) {
    var elements = document.getElementsByClassName('store-branches');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var storeid = element.getAttribute("storeid");
        var url_brancheslist = element.getAttribute("brancheslist");
        var url_create_branch = element.getAttribute("url_create_branch");
        var url_update_branch = element.getAttribute("url_update_branch");
        var url_delete_day_open = element.getAttribute("url_delete_day_open");

        ReactDOM.render(<StoreBranchesComponent
            storeid={storeid}
            url_brancheslist={url_brancheslist}
            url_create_branch={url_create_branch}
            url_update_branch={url_update_branch}
            url_delete_day_open={url_delete_day_open}
        />, element);
    }
}