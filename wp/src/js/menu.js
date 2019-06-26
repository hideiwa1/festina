import React from 'react';
import ReactDOM from 'react-dom';

import Modal from './modal';

export default class Menu extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			modalShow: false
		};
		this.handleClick = this.handleClick.bind(this);
	}
	
	handleClick(){
		console.log('1');
		this.setState(prevState => ({
			modalShow: !prevState.modalShow
		})
	);
	}

	render(){
		return(
			<div className="menu-trigger" onClick = {this.handleClick}>
				<span></span>
				<span></span>
				<span></span>
			{this.state.modalShow &&
			<Modal />
			}
			</div>
		);
	}
}