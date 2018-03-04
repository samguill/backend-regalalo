import React  from 'react';
import {Link} from 'react-router-dom';
import AutoForm from './AutoForm';
export default class CrudCreate extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (<div className="create-form">
      <ol className="breadcrumb">
      <li className="breadcrumb-item"><Link to="/">Home</Link></li>
      <li className="breadcrumb-item active">Crear</li>
      </ol>
      <AutoForm fields={this.props.fields} url={this.props.actions.create.url} />
      </div>);
  }
}
