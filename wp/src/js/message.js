import React from 'react';

export default class Message extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			data: this.props.data,
			errMsg: {},
			valid: {
				['name']: ['half', 'max','require'],
				['email']: ['email', 'require'],
				['tel']: ['tel', 'require']
			}
		}
		this.handleChange = this.handleChange.bind(this);
	}
	
	validate(name, val){ 
		let isValid = true,
			errMsg = [],
			valid = this.state.valid[name];
		const errMsgRequire = '入力必須です',
			  errMsgName = '半角英数字で入力してください。　',
			  errMsgMax = '10文字以下で入力してください。　',
			  errMsgTel = '半角数字で入力してください。　',
			  errMsgEmail = 'Email形式で入力してください。　';
		if(valid.indexOf('require') >= 0){
			isValid = val.length !== 0;
			if(!isValid) errMsg.push(errMsgRequire);
		}
		if(errMsg.indexOf(errMsgRequire) <0){
		for(let i in valid){
			switch(valid[i]){
				case 'half':
					isValid = /^[0-9a-zA-Z]+$/.test(val);
					if(!isValid) errMsg.push(errMsgName);
					break;
				case 'max':
					isValid = val.length < 10;
					if(!isValid) errMsg.push(errMsgMax);
					break;
				case 'email':
					isValid = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(val);
					errMsg = (isValid)? '': errMsgEmail;
					break;
				case 'tel':
					isValid = /^\d{10,11}$/.test(val);
					errMsg = (isValid)? '': errMsgTel;
					break;
			}
		}
		}
		return errMsg;
	}
	
	handleChange(e){
		const name = e.target.name;
		let val = e.target.value;
		let  errMsg = this.validate(name, val)
		this.setState({
			data: {
				...this.state.data,
				[name]: val,
			},
			errMsg: {
				[name]: errMsg,
			}
		});
	}
	
	render(){
		const msg = (this.state.errMsg['name'])? this.state.errMsg['name'].map((i, index) =><span className='errMsg' key={index}>{i}</span>
		) : '';
		console.log(msg);
		
		return(
			<div>
			
			<p>
			{msg}
			<label>お名前</label>
			<input type="text" name="name" onChange = {this.handleChange}/>
			</p>

			<p>
			<span>{this.state.errMsg['email']}</span><br/>
			<label>Email</label>
			<input type="text" name="email" onChange = {this.handleChange} />
			</p>
			
			<p>
			<span>{this.state.errMsg['tel']}</span><br/>
			<label>電話番号</label>
			<input type="text" name="tel" onChange = {this.handleChange} />
			</p>
			
			<p>
			<span>{this.state.errMsg['comment']}</span><br/>
			<label>お問合わせ内容</label>
			<textarea name="comment" rows="5" onChange = {this.handleChange}></textarea>
			</p>
			</div>
		);
	}
}
