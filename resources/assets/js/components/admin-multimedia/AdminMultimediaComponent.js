/**
 * Created by marzioperez on 24/07/18.
 */
import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Dropzone from 'react-dropzone';

import Tooltip from 'react-tooltip-component';

export default class AdminMultimediaComponent extends React.Component {

    constructor(props) {
        super(props);
        this.site_url = this.props.site_url;
        this.stores = JSON.parse(this.props.stores);
        let first = {
            value: 0,
            label: "General"
        };
        this.stores.unshift(first);
        this.url_multimedia_list = this.props.url_multimedia_list;
        this.store_images_url = this.props.store_images_url;
        this.upload_images_url = this.props.upload_images_url;
        this.delete_images_url = this.props.delete_images_url;

        this.uploadImages = this.uploadImages.bind(this);
        this.onSelectStore = this.onSelectStore.bind(this);
        this.state = {
            files: [],
            is_loading: false,
            store_id: 0
        };
    }

    componentDidMount(){

    }

    onSelectStore(store){
        let store_id = store.value;
        this.setState({store_id:store_id, files: []});
        swal({
            title: "Obteniendo imágenes de la tienda " + store.label,
            text: "Por favor, espere este proceso puede tomar varios segundos.",
            allowOutsideClick:false,
            allowEscapeKey:false,
            allowEnterKey:false,
            onOpen: () => {
                swal.showLoading();
            }
        });

        axios.post(this.store_images_url, {store_id: store_id})
            .then((response) => {
                console.log(response.data.status);
                if (response.data.status === "ok"){
                    this.setState({files:response.data.data});
                }
                console.log(this.state.files);
                swal.close();
            })
            .catch((error) => {
                swal.close();
                swal({
                    type: 'error',
                    title: 'Ha ocurrido un error',
                    text: "Ocurrió un error, iténtalo de nuevo",
                });
            })
    }

    deleteImage(row){
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
            axios.post(this.delete_images_url,{id:row.id, store_id: this.state.store_id}).then((r)=>{
                if(r.data.status==='ok') {
                    swal({  title: "Eliminación Exitosa",
                        text: "El elemento ha sido eliminado de manera exitosa.",
                        type: "success"});
                    this.setState({files:this.state.files.filter((item) => item.id!==row.id)});
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

    uploadImages(files){
        const uploaders = files.map(file => {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("store_id", this.state.store_id);

            return axios.post(this.upload_images_url, formData)
                .then((response) => {
                    this.setState({files: this.state.files.concat(response.data.data)});
                })
                .catch(function (error) {
                    swal({  title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"});
                    console.log(error);
                });
        });
        axios.all(uploaders).then((data) =>{});
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <div className="row">
                    <div className="media-list-files col-md-12">
                        <div className="row">
                            <div className="col-md-6">
                                <div className="row">
                                    <div className="col-md-12">
                                        <div className="form-group">
                                            <label>Seleccione una tienda</label>
                                            <Select
                                                value={this.state.store_id}
                                                options={this.stores}
                                                className="form-control"
                                                onChange={this.onSelectStore}
                                            />
                                        </div>
                                    </div>
                                    <Dropzone className="dropzone-area col-md-12" onDrop={this.uploadImages}
                                              accept="image/jpeg,image/jpg,image/tiff,image/gif,image/png"
                                              multiple={ true } >
                                        <div className="drop-container">
                                            { (this.state.is_loading) ?
                                                <div className="over-loading">
                                                    <i className="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                                                </div>
                                                : ''
                                            }
                                            <div className="dropzone-text">
                                                <i className="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <h3>Arrastra tus archivos aquí</h3>
                                            </div>
                                        </div>
                                    </Dropzone>
                                </div>
                            </div>
                            {
                                this.state.files.map((row, ri) => {
                                    return <div key={ri} className="col-md-3 file">
                                        <div className="file-item-containter">
                                            <div className="file-item" style={{ backgroundImage : "url("+ row.image_path + ")" }}></div>
                                            <div className="file-options">
                                                <div className="row no-glutter">
                                                    <div className="col-md-6">
                                                        <a className="btn btn-danger btn-sm btn-block text-white" onClick={(e) => {this.deleteImage(row)}}>Eliminar</a>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <a className="btn btn-info btn-sm btn-block text-white" href={row.image_path} target="_blank">Ver</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                })
                            }
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementsByClassName('multimedia-images')) {
    var elements = document.getElementsByClassName('multimedia-images');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        let stores = element.getAttribute("data-stores");
        let site_url = element.getAttribute("data-site_url");
        let store_images_url = element.getAttribute("data-store-images-url");
        let upload_images_url = element.getAttribute("data-upload-images-url");
        let delete_images_url = element.getAttribute("data-delete-images-url");

        ReactDOM.render(<AdminMultimediaComponent
            stores={stores}
            site_url={site_url}
            store_images_url={store_images_url}
            upload_images_url={upload_images_url}
            delete_images_url={delete_images_url}
        />, element);
    }
}