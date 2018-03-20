/**
 * Created by marzioperez on 19/03/18.
 */
import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Dropzone from 'react-dropzone';

import Tooltip from 'react-tooltip-component';

export default class StoreMultimediaComponent extends React.Component {

    constructor(props) {
        super(props);
        this.storeid = this.props.storeid;
        this.url_multimedialist = this.props.url_multimedialist;
        this.upload_url = this.props.upload_url;

        this.getImages = this.getImages.bind(this);
        this.uploadImages = this.uploadImages.bind(this);

        this.state = {
            files: [],
            is_loading: false
        };
    }

    componentDidMount(){
        this.getImages();
    }

    getImages(){
        this.setState({is_loading:true});
        axios.post(this.url_multimedialist, {id:this.storeid})
            .then((response) => {
                if(response.data.status === "ok"){
                    this.setState({files: response.data.data});
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
                swal({  title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"});
                this.setState({is_loading:false});
            });
    }

    uploadImages(files){
        this.setState({is_loading:true});
        console.log(files);

        const uploaders = files.map(file => {
            const formData = new FormData();
            formData.append("file", file);

            return axios.post(this.upload_url, formData)
                .then((response) => {
                    console.log(response);
                    this.setState({files: this.state.files.concat(response.data.data), is_loading: false});
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
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <div className="row">
                    <div className="media-list-files col-md-12">
                        <div className="row">
                            <Dropzone className="dropzone-area col-md-6" onDrop={this.uploadImages}
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

                            {
                                this.state.files.map((row, ri) => {
                                    return <div key={ri} className="col-md-3 file">
                                        <div className="file-item-containter">
                                            <div className="file-item" style={{ backgroundImage : "url(http://regalalo.test/"+ row.image_path +")" }}></div>
                                            <div className="file-options">
                                                <div className="row no-glutter">
                                                    <div className="col-md-6">
                                                        <a className="btn btn-danger btn-sm btn-block text-white">Eliminar</a>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <a className="btn btn-info btn-sm btn-block text-white">Ver</a>
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

if (document.getElementsByClassName('store-images')) {
    var elements = document.getElementsByClassName('store-images');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var storeid = element.getAttribute("storeid");
        var url_multimedialist = element.getAttribute("multimedialist");
        var upload_url = element.getAttribute("upload_url");

        ReactDOM.render(<StoreMultimediaComponent
            storeid={storeid}
            url_multimedialist={url_multimedialist}
            upload_url={upload_url}
        />, element);
    }
}