import React from 'react';
import ReactDOM from 'react-dom';

import "./css/style.scss";
import Form from "./js/main";
import Menu from "./js/menu";

document.addEventListener('DOMContentLoaded', function(){
	var formDom = document.getElementsByClassName('contact-form');
	for (var i = 0; i < formDom.length; i++){
    ReactDOM.render(
        <Form />, formDom[i]
    );
	}
	
	var menuDom = document.getElementById('js-menu');
	ReactDOM.render(
		<Menu />, menuDom
	);
});
