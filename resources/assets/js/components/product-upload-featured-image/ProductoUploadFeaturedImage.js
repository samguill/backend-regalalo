import React  from 'react';
import ReactDOM from 'react-dom';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Dropzone from 'react-dropzone';


export default class ProductoUploadFeaturedImage extends React.Component {

    constructor(props) {
        super(props);
        this.product_id = this.props.product_id;
        this.data_upload_url = this.props.data_upload_url;
        this.featured_image = this.props.featured_image;

        let featured_image_url = 'http://via.placeholder.com/400x200';
        if(this.featured_image !== ""){
            featured_image_url = this.featured_image;
        }

        this.uploadImage = this.uploadImage.bind(this);

        this.state = {
            is_loading: false,
            featured_image: featured_image_url
        };
    }

    uploadImage(files){
        this.setState({is_loading:true});

        const uploaders = files.map(file => {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("product_id", this.product_id);

            return axios.post(this.data_upload_url, formData)
                .then((response) => {
                    this.setState({featured_image: response.data.data, is_loading: false});
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
                                        <h3>Arrastra la imagen del producto aquí</h3>
                                    </div>
                                </div>
                            </Dropzone>
                            <div className="col-md-6">
                                <img src={this.state.featured_image} style={{width:'100%'}} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

if (document.getElementsByClassName('featured-image-product-component')) {
    var elements = document.getElementsByClassName('featured-image-product-component');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var product_id = element.getAttribute("product_id");
        var data_upload_url = element.getAttribute("data_upload_url");
        var featured_image = element.getAttribute("featured_image");

        ReactDOM.render(<ProductoUploadFeaturedImage
            product_id={product_id}
            data_upload_url={data_upload_url}
            featured_image={featured_image}
        />, element);
    }
}