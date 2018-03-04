import React  from 'react';
import {inject,observer} from 'mobx-react';
import AutoForm from './autoform';
export default
inject('store')(
  observer(
class Search extends React.Component {
  constructor(props) {
    super(props);
    this.toggleVisibility=this.toggleVisibility.bind(this);
  }
  toggleVisibility(){
    this.props.store.params.set('show-advanced-search',!this.props.store.params.get('show-advanced-search'))
  }

  render() {
    return (<div>
        <a onClick={this.toggleVisibility} className="" style={{cursor:"pointer",float:"right",color:"#407590"}}><i className="fa fa-search-plus" aria-hidden="true"></i> {!this.props.store.params.get('show-advanced-search')?"Búqueda avanzada":"Búsqueda Rápida"}</a>
        <div style={{display:this.props.store.params.get('show-advanced-search')?"block":"none"}}>
              <AutoForm fields={this.props.fields} />
        </div>
    </div>);
  }
}
))
