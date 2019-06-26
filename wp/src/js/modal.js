import React from 'react';
import ReactDOM from 'react-dom';

export default class Modal extends React.Component{
	constructor(props){
		super (props);
		this.state = {
			rootDOM: document.getElementById('body')
		};
	}
	
	render(){
		return(
			ReactDOM.createPortal(
				<div className='modal' onClick = {()=>preventDefault() }>
				<nav className="modal-menu">
					<ul>
						<li className="modal-menu__item"><a href="about.html">about</a></li>
						<li className="modal-menu__item"><a href="news.html">News</a></li>
						<li className="modal-menu__item"><a href="blog.html">blog</a></li>
						<li className="modal-menu__item"><a href="access.html">access</a></li>
						<li className="modal-menu__item"><a href="contact.html">contact</a></li>
					</ul>
				</nav>
				</div>,
			this.state.rootDOM)
		);
	}
}