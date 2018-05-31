import React  from 'react';
import ReactDOM from 'react-dom';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Dropzone from 'react-dropzone';


export default class StoreUploadLogoComponent extends React.Component {

    constructor(props) {
        super(props);
        this.store_id = this.props.store_id;
        this.data_upload_url = this.props.data_upload_url;
        this.logo_store = this.props.logo_store;

        let logo_store_url = 'http://via.placeholder.com/400x200';
        if(this.logo_store !== ""){
            logo_store_url = this.logo_store;
        }

        this.uploadImage = this.uploadImage.bind(this);

        this.state = {
            is_loading: false,
            logo_store: logo_store_url
        };
    }

    uploadImage(files){
        this.setState({is_loading:true});
        console.log(files);

        const uploaders = files.map(file => {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("store_id", this.store_id);

            return axios.post(this.data_upload_url, formData)
                .then((response) => {
                    this.setState({logo_store: response.data.data, is_loading: false});
                })
                .catch(function (error) {
                    swal({  title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"});
                    this.setState({is_loading:false});
                });
        });

        axios.all(uploaders).then((data) =>{
            console.log(data);
        });

        this.setState({is_loading:false});
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <div className="row">
                    <div className="media-list-files col-md-12">
                        <div className="row">
                            <Dropzone className="dropzone-area col-md-6" onDrop={this.uploadImage}
                                      accept="image/jpeg,image/jpg,image/tiff,image/gif,image/png"
                                      multiple={ false } >
                                <div className="drop-container">
                                    { (this.state.is_loading) ?
                                        <div className="over-loading">
                                            <i className="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                                        </div>
                                        : ''
                                    }
                                    <div className="dropzone-text">
                                        <i className="fa fa-cloud-upload" aria-hidden="true"></i>
                                        <h3>Arrastra el logo de la tienda aquí</h3>
                                    </div>
                                </div>
                            </Dropzone>
                            <div className="col-md-6">
                                <img src={this.state.logo_store} style={{width:'100%'}} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

if (document.getElementsByClassName('logo-store-component')) {
    var elements = document.getElementsByClassName('logo-store-component');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var store_id = element.getAttribute("store_id");
        var data_upload_url = element.getAttribute("data_upload_url");
        var logo_store = element.getAttribute("logo_store");

        ReactDOM.render(<StoreUploadLogoComponent
            store_id={store_id}
            data_upload_url={data_upload_url}
            logo_store={logo_store}
        />, element);
    }
}