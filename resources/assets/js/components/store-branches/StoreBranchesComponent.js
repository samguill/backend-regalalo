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

        let autoComplete = new window.google.maps.places.Autocomplete();

        this.getBranches = this.getBranches.bind(this);

        this.storeid = this.props.storeid;
        this.url_brancheslist = this.props.url_brancheslist;

        this.state = {
            id: '',
            name: '',
            latitude: '',
            longitude: '',
            address: '',
            updating: false,
            is_loading: false,
            branches: [],
            zoom: 13,
            maptype: 'roadmap'
        };

        this.onIdChage = this.onIdChage.bind(this);
        this.onNameChage = this.onNameChage.bind(this);
        this.onLatitudeChage = this.onLatitudeChage.bind(this);
        this.onLongitudeChage = this.onLongitudeChage.bind(this);
        this.onAddressChage = this.onAddressChage.bind(this);
        this.clearForm = this.clearForm.bind(this);

    }

    onIdChage(e){ this.setState({id:e.target.value}); }
    onNameChage(e){ this.setState({name:e.target.value}); }
    onLatitudeChage(e){ this.setState({latitude:e.target.value}); }
    onLongitudeChage(e){ this.setState({longitude:e.target.value}); }
    onAddressChage(e){ this.setState({address:e.target.value}); }

    componentDidMount(){
        let map = new window.google.maps.Map(document.getElementById('branche_map'), {
            center: {lat: -12.046374, lng: -77.042793},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        map.addListener('zoom_changed', () => {
            this.setState({
                zoom: map.getZoom()
            });
        });

        map.addListener('maptypeid_changed', () => {
            this.setState({
                maptype: map.getMapTypeId()
            });
        });

        let marker = new window.google.maps.Marker({
            map: map,
            position: {lat: -12.046374, lng: -77.042793}
        });

        let address = document.getElementById('address');
        let autoComplete = new window.google.maps.places.Autocomplete(address);
        autoComplete.addListener('place_changed', () => {
            let place = autoComplete.getPlace();
            let location = place.geometry.location;

            this.setState({latitude: location.lat()});
            this.setState({longitude: location.lng()});

            map.fitBounds(place.geometry.viewport);
            map.setCenter(location);
            marker.setPlace({
                placeId: place.place_id,
                location: location
            });
        });
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
            name: row.name,
            address: row.address,
            latitude: row.latitude,
            longitude: row.longitude,
            updating: true
        });

        let map = new window.google.maps.Map(document.getElementById('branche_map'), {
            center: {lat: row.latitude, lng: row.longitude},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        map.addListener('zoom_changed', () => {
            this.setState({
                zoom: map.getZoom()
            });
        });

        map.addListener('maptypeid_changed', () => {
            this.setState({
                maptype: map.getMapTypeId()
            });
        });

        let marker = new window.google.maps.Marker({
            map: map,
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
            updating: false
        });
        let map = new window.google.maps.Map(document.getElementById('branche_map'), {
            center: {lat: -12.046374, lng: -77.042793},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        map.addListener('zoom_changed', () => {
            this.setState({
                zoom: map.getZoom()
            });
        });

        map.addListener('maptypeid_changed', () => {
            this.setState({
                maptype: map.getMapTypeId()
            });
        });

        let marker = new window.google.maps.Marker({
            map: map,
            position: {lat: -12.046374, lng: -77.042793}
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
                                <lable>Nombre de la sucursal</lable>
                                <input id="name" name="name" onChange={this.onNameChage} className="form-control" type="text" value={this.state.name} />
                            </div>
                            <div className="form-group">
                                <lable>Dirección</lable>
                                <input id="address" name="address" onChange={this.onAddressChage} className="form-control" type="text" value={this.state.address} />
                            </div>
                            <div className="form-group">
                                <lable>Latitud</lable>
                                <input id="latitude" name="latitude" disabled onChange={this.onLatitudeChage} className="form-control" type="text" value={this.state.latitude} />
                            </div>
                            <div className="form-group">
                                <lable>Longitud</lable>
                                <input id="longitude" name="longitude" disabled onChange={this.onLongitudeChage} className="form-control" type="text" value={this.state.longitude} />
                            </div>
                            <div id="branche_map"></div>
                            <div className="form-group" style={{textAlign:"center"}}>
                                <div className="row">
                                    <div className={"col-md-" + (this.state.updating ? 6 : 12)}>
                                        <a className="btn btn-success text-white btn-block">
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
        ReactDOM.render(<StoreBranchesComponent
            storeid={storeid}
            url_brancheslist={url_brancheslist}
        />, element);
    }
}